<?PHP

	// Ce module simule une base de données
	// A la place d'appeler une base SQL, on utilise des tables en dure
	// Ne permet pas de modifier les valeurs

include "acces_bdd.php";


$i=1;
$sql = 'SELECT * FROM maisons';
$maisons = array();
//echo $sql;
$resultat = exec_sql($sql);
	while($row = $resultat->fetch_assoc()) {
		foreach($row as $key => $value) {
				$maisons[$i][$key] = $value;
		}
	$i+=1;	
	}
//print_r($maisons);

/*
	
	$maisons = [
		1 => [
			"nom" => "Appartement",
			"user" => 1
			],
		2 => [
			"nom" => "Résidence secondaire",
			"user" => 1
			]
	];
*/

//echo '</br>';
//print_r($maisons);

$i=1;
$sql = 'SELECT * FROM etages';
$etages = array();
//echo $sql;
$resultat = exec_sql($sql);
	while($row = $resultat->fetch_assoc()) {
		foreach($row as $key => $value) {
				$etages[$row["numero"]][$key] = $value;
		}
	$i+=1;	
	}

//print_r($etages);

/*
	$etages = [
		1 => [
			"maison" => 1,
			"niveau" => 0,
			"nom" => "1er étage",
			"plan" => "plan0.jpg"
		],
		2 => [
			"maison" => 2,
			"niveau" => 0,
			"nom" => "Rez de chaussée",
			"plan" => "plan1.jpg"
		],
		3 => [
			"maison" => 2,
			"niveau" => 1,
			"nom" => "Etage",
			"plan" => "plan2.jpg"
		],
		4 => [
			"maison" => 2,
			"niveau" => 2,
			"nom" => "Combles",
			"plan" => "plan3.jpg"
		],
		5 => [
			"maison" => 1,
			"niveau" => 1,
			"nom" => "2e étage",
			"plan" => "plan3.jpg"
		]
	];
*/

$i=1;
$sql = 'SELECT * FROM pieces';
$pieces = array();
//echo $sql;
$resultat = exec_sql($sql);
	while($row = $resultat->fetch_assoc()) {
		foreach($row as $key => $value) {
				$pieces[$i][$key] = $value;
		}
	$i+=1;	
	}

//print_r($pieces);

/*
	
	$pieces = [
		1 => [
			"etage" => 1,
			"nom" => "Palier",
			"coord" => "382,348, 382,252, 276,252, 277,173, 380,175, 380,136, 221,134, 185,160, 186,196, 221,226, 218,345"
		],
		2 => [
			"etage" => 1,
			"nom" => "Chambre 2",
			"coord" => "220,348, 223,202, 26,201, 26,346"
		],
		3 => [
			"etage" => 1,
			"nom" => "Chambre 3",
			"coord" => "219,155, 219,28, 31,26, 31,158"
		],
		4 => [
			"etage" => 1,
			"nom" => "Chambre 4",
			"coord" => "385,175, 577,174, 573,28, 386,28"
		],
		5 => [
			"etage" => 1,
			"nom" => "Chambre 5",
			"coord" => "385,348, 572,347, 573,204, 381,204"
		],
		6 => [
			"etage" => 1,
			"nom" => "WC",
			"coord" => "187,161, 97,159, 96,199, 184,200"
		]
	];
*/
	
	$capteurs = [
		1 => [
			"piece" => 3,
			"numero" => "adr_1",
			"nom" => "Eclairage principal",
			"categorie" => "poussoir",
			"icone" => "none",
			"coord" => "50, 50"
		],
		2 => [
			"piece" => 3,
			"numero" => "adr_2",
			"nom" => "Température",
			"categorie" => "thermometre",
			"icone" => "none",
			"coord" => "100, 50"
		],
		3 => [
			"piece" => 3,
			"numero" => "adr_3",
			"nom" => "Thermostat",
			"categorie" => "thermostat",
			"icone" => "none",
			"coord" => "150, 50"
		],
		4 => [
			"piece" => 3,
			"numero" => "adr_4",
			"nom" => "Volet",
			"categorie" => "variateur",
			"icone" => "none",
			"coord" => "50, 100"
		],
		5 => [
			"piece" => 6,
			"numero" => "adr_5",
			"nom" => "Caméra",
			"categorie" => "camera",
			"icone" => "none",
			"coord" => "50, 100"
		],
		6 => [
			"piece" => 6,
			"numero" => "adr_6",
			"nom" => "Odeur",
			"categorie" => "odorometre",
			"icone" => "none",
			"coord" => "50, 100"
		]
	];
	
	
	// DEBUG : dump une table (permet de vérifier la syntaxe du PHP)
	
	//var_dump($maisons);
	//var_dump($etages);
	//var_dump($pieces);
	//var_dump($capteurs);
	//var_dump($valeurs);
	//var_dump($categories);
	
	// Transforme un objet en json
	
	function buildJson($objet) {
	
		$s = '{';
		$main_sep = "";
		
		foreach ($objet as $k => $v) {
			
			$s = $s.$main_sep;
			$main_sep = ", ";
			
			$s = $s.'"'.$k.'": ';
			if (is_int($v)) {
				$s = $s.$v;
			} elseif (is_array($v)) {
				$sep = "[";
				foreach ($v as $sk => $sv) {
					$s = $s.$sep.buildJson($sv, $sk);
					$sep = ", ";
				}
				$s = $s."]";
			} else {
				$s = $s.'"'.$v.'"';
			}
		}
		$s=$s.'}';
		return $s;
	}
	
	// La base est arborescente : maisons > etages > pieces > capteurs
	// La function suivante retourne tous les enfants d'un élément père
	
	function getEnfants($table, $idParent) {
		
		$sep = '{';
		$s='';
		foreach ($table as $id => $enfant) {
			$indice = $enfant["parent"];
//			echo $indice;
//			echo '</br>';
			if (is_null($idParent) || ($indice == $idParent)) {	
				$s = $s.$sep;
				$sep = ", ";
				
				$s = $s.'"'.$id.'":';
				$s = $s.buildJson($enfant);
				
			}
		}
		if ($sep == "{") {
			$s = $s." null";
		} else {
			$s = $s."}";
		}
		return $s;
	}
//	echo getEnfants($pieces,1);	
/*	
	function getEnfants($table, $nomParent, $idParent) {
		
		$sep = '{';
		
		foreach ($table as $id => $enfant) {
		
			if (is_null($idParent) || ($enfant[$nomParent] == $idParent)) {
				
				$s = $s.$sep;
				$sep = ", ";
				
				$s = $s.'"'.$id.'":';
				$s = $s.buildJson($enfant);
				
			}
		}
		if ($sep == "{") {
			$s = $s." null";
		} else {
			$s = $s."}";
		}
		return $s;
	}
*/

	// Function qui retourne le json des maisons
	
	
	function getMaisons($idUser) {
	
		global $maisons;
		
		//return getEnfants($maisons, "user", $idUser);
		return getEnfants($maisons, $idUser);
		
	}
	
	
	
	// Function qui retourne le json des étages d'une maison
	
	function getEtages($idMaison) {
	
		global $etages;
	
		//return getEnfants($etages, "maison", $idMaison, "etages");
		return getEnfants($etages, $idMaison);
		
	}
		
	// Function qui retourne le json des pièces d'un étage
	
	function getPieces($idEtage) {
	
		global $pieces;
	
		//return getEnfants($pieces, "etage", $idEtage, "pieces");
		return getEnfants($pieces, $idEtage);
		
	}

	// Function qui retourne le json des capteurs d'une pièce
	
	function getCapteurs($idPiece) {
	
		global $capteurs;
	
		return getEnfants($capteurs, "piece", $idPiece, "capteurs");
		
	}
	
	// TEST

	
	//echo getEtages(1)."<br/>";
	//echo getPieces(1)."<br/>";
	//echo getCapteurs(1)."<br/>";
	//echo getDetailCapteur(1);
	
	
	
	

?>
