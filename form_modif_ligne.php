<?PHP
	include "acces_bdd.php";
	
	$table = $_GET["table"];
	$numero = $_GET["numero"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>

<h1>Modification d'une table quelconque</h1>

La table est : <?PHP echo $table; ?> et le numéro est <?PHP echo $numero; ?>

<?PHP


	// Lecture de l'enregistrement à modifier
	
	$sql = "SELECT * FROM " . $table . " WHERE numero=" . $numero . ";";
	
	$result = exec_sql($sql);
	
	$row = $result->fetch_assoc();
	

?>

<form action="modifier_ligne.php">

<input type="hidden" name="table" value="<?=$table?>" />

<?PHP

	foreach ($row as $key => $value) {
		
		$br = "";
		
		if ($key == "parent") {
			// On ne fait rien car on ne veut pas modifier le parent	
		} else {
			
			if ($key == "numero") {
				echo '<input type="hidden"';
			} else {
				$sqlb = "SELECT libelle FROM sys_libelles WHERE nom_colonne = '" . $key . "';";
				$resultat = exec_sql($sqlb);
				$ligne = $resultat->fetch_assoc();
				$joli_nom = $ligne["libelle"];
				echo $joli_nom . ' : <input type="text"';
				$br = "<br />";	
			}
			echo ' name = "' . $key . '" value = "' . $value . '">' . $br;
			
		} 
	} 

?>    
    <input type="submit" value="Modifier">

</form>

<?PHP

$sql = "SHOW COLUMNS FROM maisons";
//lire_table($sql);

$cols = colonnes_table($table);

//var_dump($cols);

//foreach ($cols as $key => $value) {
//	echo $value . '<p>';
//}


?>



</body>
</html>
