<?PHP

include "acces_bdd.php";

// Debug

foreach ($_GET as $key => $value) {
    echo $key . ' => ' . $value . '<br />';
}

$table = $_GET["table"];
$numero = $_GET["numero"];


// Update l'enregistrement

$sql = "UPDATE ".$table." SET ";

$sep = "";

foreach ($_GET as $key => $value) {
	if (($key != "table") && ($key != "numero")) {	
		$sql .= $sep . $key . '="' . $value . '"';
		$sep = ", ";
	}
}

//colonnes_table("maisons");

$sql .= " WHERE numero = ".$numero;

exec_sql($sql);


?>