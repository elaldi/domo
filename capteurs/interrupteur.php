<?PHP

	include "variables.php";
	
	// Lecture de l'état de l'interrupteur
	// Retourne la valeur et termine l'exécution de la page
	
	if (isset($_GET["lecture"])) {
		$nomVariable = "interrupteur_".$_GET["lecture"];
		echo litVariable($nomVariable, "Ouvert");
		exit();
	}
	
	// Commande d'allumage : change la valeur de la variable
	// et quitte la page sans rien retourner
	
	if (isset($_GET["allumer"])) {
		$nomVariable = "interrupteur_".$_GET["allumer"];
		ecritVariable($nomVariable, "Ouvert");
		exit();
	}
	
	// Idem avec l'extinction
	
	if (isset($_GET["eteindre"])) {
		$nomVariable = "interrupteur_".$_GET["eteindre"];
		ecritVariable($nomVariable, "Fermé");
		exit();
	}
	
	// Si on est ici c'est que l'on veut le code HTML
	// de l'affichage du capteur
	
	// On ne fait aucun contrôle....
	
	// Numéro de l'interrupteur
	
	$numero = $_GET["numero"];
	
	// On construit l'identifiant de l'élément html
	// qui contiendra la valeur de l'état
	
	$elementId = "etat_interrupteur_".$numero;
	
	// S'en est fini du PHP, c'est plus facile de continuer en HTML
	
?>


	
Etat : <i><b><span id="<?=$elementId?>">valeur</span></i></b>

<br/>
<a onclick=commandeCapteur("capteurs/interrupteur.php?allumer=<?=$numero?>")>Allumer</a>
&nbsp;
<a onclick=commandeCapteur("capteurs/interrupteur.php?eteindre=<?=$numero?>")>Eteindre</a>

	<script>
		
		// Déclare l'url qu'il faut utiliser pour mettre à jour
		// l'état du capteur
		// Toutes les secondes, l'url sera appelée et le résultat sera
		// Placé au bon endroit dans l'affichage du capteur
		
		capteursRaf_register("<?=$elementId?>", "capteurs/interrupteur.php?lecture=<?=$numero?>");
	</script>

