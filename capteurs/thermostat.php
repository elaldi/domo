<?PHP

	include "variables.php";

	// Capteur thermomètre : affiche la tempéature

	// Les paramètres d'appel sont les suivants
	// adresse = xxx   : L'adresse du capteur qui permet au serveur domotique de savoir de quel capteur on parle
	
	// Cas de la lecture : on ne retourne que la valeur de la température
	
	if (isset($_GET["temperature"])) {
		echo rand (-3, 45);
		exit();
	}
	
	if (isset($_GET["get_mini"])) {
		echo litVariable("thermostat_mini", 10);
		exit();
	}
	if (isset($_GET["get_maxi"])) {
		echo litVariable("thermostat_maxi", 20);
		exit();
	}
	
	if (isset($_GET["mini_moins"])) {
		$mini = litVariable("thermostat_mini", 10);
		$mini -= $_GET["mini_moins"];
		ecritVariable("thermostat_mini", $mini);
		exit();
	}
	if (isset($_GET["mini_plus"])) {
		$mini = litVariable("thermostat_mini", 10);
		$mini += $_GET["mini_plus"];
		ecritVariable("thermostat_mini", $mini);
		exit();
	}
	
	if (isset($_GET["maxi_moins"])) {
		$maxi = litVariable("thermostat_maxi", 20);
		$maxi -= $_GET["maxi_moins"];
		ecritVariable("thermostat_maxi", $maxi);
		exit();
	}
	if (isset($_GET["maxi_plus"])) {
		$maxi = litVariable("thermostat_maxi", 20);
		$maxi += $_GET["maxi_plus"];
		ecritVariable("thermostat_maxi", $maxi);
		exit();
	}
	
	if (isset($_GET["set_mini"])) {
		ecritVariable("thermostat_mini", $_GET["set_mini"]);
		exit();
	}
	if (isset($_GET["set_maxi"])) {
		ecritVariable("thermostat_maxi", $_GET["set_maxi"]);
		exit();
	}
	
	// Cas d'affichage du contrôle du capteur
	
	$numero = $_GET["numero"];
	
	
	
?>
	<table>
	<tr><td>
		Température : <b><span id=thermostat_temp_<?=$numero?>>????</span>°</b> Celsius.
	</td></tr>
	<tr><td>
	Mini :
	<a onclick="commandeCapteur('capteurs/thermostat.php?mini_moins=1')">[-]&nbsp;</a>
	<b><span id=thermostat_mini_<?=$numero?>>????</span>°</b>
	<a onclick="commandeCapteur('capteurs/thermostat.php?mini_plus=1')">[+]&nbsp;</a>
	</td></tr>
	<tr><td>
	Maxi : 
	<a onclick="commandeCapteur('capteurs/thermostat.php?maxi_moins=1')">[-]&nbsp;</a>
	<b><span id=thermostat_maxi_<?=$numero?>>????</span>°</b> Celsius.
	<a onclick="commandeCapteur('capteurs/thermostat.php?maxi_plus=1')">[+]&nbsp;</a>
	</td></tr>
	</table>
	
	<script>
		// capteursRaf_register déclare les elements à mettre à jour toutes les secondes
		// en lui indiquant quelle url utiliser pour obtenir les données lues sur le capteur
		// Ici : l'élément thermometre_temp_numero sera mis à jour en appelant l'url capteurs/thermometre.php?lecture=numero
		
		capteursRaf_register("thermostat_temp_<?=$numero?>", "capteurs/thermostat.php?temperature=<?=$numero?>", interval=10);
		capteursRaf_register("thermostat_mini_<?=$numero?>", "capteurs/thermostat.php?get_mini=<?=$numero?>");
		capteursRaf_register("thermostat_maxi_<?=$numero?>", "capteurs/thermostat.php?get_maxi=<?=$numero?>");
		
	</script>
	
	
