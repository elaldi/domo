<?php

include "acces_bdd.php";
$table = $_GET["table"];

// Debug

foreach ($_GET as $key => $value) {
    echo $key . ' => ' . $value . '<br />';
}

// Récherche d'un nouveau numéro de maison

$numero = max_colonne($table, "numero")+1;


// Création de la maison

$sql = "INSERT INTO " . $table. " (numero, ";

$sep = "";

foreach ($_GET as $key => $value) {
	if ($key != "table"){	
		$sql = $sql . $sep . $key ;
		$sep = ", ";
	}
}

$sql .= ")";

$sql .= "VALUES (" . $numero;


$sep = ", ";

foreach ($_GET as $key => $value) {	
	if ($key != "table"){
		$sql = $sql . $sep . '"' . $value . '"';
		$sep = ", ";
	}
}

$sql .= ");";

echo $sql . "<p>";

exec_sql($sql);


?>