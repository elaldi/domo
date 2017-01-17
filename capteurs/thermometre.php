<?PHP

	// Capteur thermomètre : affiche la tempéature

	// Les paramètres d'appel sont les suivants
	// adresse = xxx   : L'adresse du capteur qui permet au serveur domotique de savoir de quel capteur on parle
	
	// Cas de la lecture : on ne retourne que la valeur de la température
	
	if (isset($_GET["lecture"])) {
		echo rand (-3, 45);
		exit();
	}
	
	// Cas d'affichage du contrôle du capteur
	
	$numero = $_GET["numero"];
	
?>
	La température est de : <b><span id=thermometre_temp_<?=$numero?>>????</span>°</b> Celsius.
	
	<script>
		// capteursRaff_register déclare les elements à mettre à jour toutes les secondes
		// en lui indiquant quelle url utiliser pour obtenir les données lues sur le capteur
		// Ici : l'élément thermometre_temp_numero sera mis à jour en appelant l'url capteurs/thermometre.php?lecture=numero
		
		capteursRaf_register("thermometre_temp_<?=$numero?>", "capteurs/thermometre.php?lecture=<?=$numero?>", interval=5);
		
	</script>
	
	
