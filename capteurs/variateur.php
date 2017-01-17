<?PHP

	include "variables.php";

	// Capteur thermomètre : affiche la tempéature

	// Les paramètres d'appel sont les suivants
	// adresse = xxx   : L'adresse du capteur qui permet au serveur domotique de savoir de quel capteur on parle
	
	// Cas de la lecture : on ne retourne que la valeur de la température
	
	$nomFichier = "variateur_";
	
	if (isset($_GET["lecture"])) {
		echo litVariable($nomFichier.$_GET["lecture"], 80);
		exit();
	}
	// Adresse du variateur
	
	$numero = $_GET["numero"];
	$nomFichier = $nomFichier . $_GET["numero"];
	
	if (isset($_GET["moins"])) {
		$val = litVariable($nomFichier, 80);
		$val -= $_GET["moins"];
		if ($val < 0) {
			$val = 0;
		}
		ecritVariable($nomFichier, $val);
		exit();
	}
	
	if (isset($_GET["plus"])) {
		$val = litVariable($nomFichier, 80);
		$val += $_GET["plus"];
		if ($val > 100) {
			$val = 100;
		}
		ecritVariable($nomFichier, $val);
		exit();
	}
	
?>
	Position :
	<a onclick="commandeCapteur('capteurs/variateur.php?numero=<?=$numero?>&moins=10')"> &lt; &lt; &lt; &nbsp;</a>
	<a onclick="commandeCapteur('capteurs/variateur.php?numero=<?=$numero?>&moins=1')"> &lt; &nbsp;</a>
	<b><span id=variateur_<?=$numero?>>????</span>%</b>
	<a onclick="commandeCapteur('capteurs/variateur.php?numero=<?=$numero?>&plus=1')"> &nbsp; &gt; &nbsp;</a>
	<a onclick="commandeCapteur('capteurs/variateur.php?numero=<?=$numero?>&plus=10')"> &nbsp; &gt; &gt; &gt;</a>
	</td></tr>
	<tr><td>
	
	<script>
		// capteursRaff_register déclare les elements à mettre à jour toutes les secondes
		// en lui indiquant quelle url utiliser pour obtenir les données lues sur le capteur
		// Ici : l'élément thermometre_temp_numero sera mis à jour en appelant l'url capteurs/thermometre.php?lecture=numero
		
		capteursRaf_register("variateur_<?=$numero?>", "capteurs/variateur.php?lecture=<?=$numero?>");
		
	</script>
	
	
