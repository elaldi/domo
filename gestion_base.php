

<?php

include "acces_bdd.php";
$parent = $_GET["parent"];
$numero = $_GET["numero"];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Gestion de la base de données domotique</title>
</head>

<body>

<h2>Gestion de la base de données domotique</h2>


<form action="gestion_base.php">

<input type="hidden" name="table" value="<?=$table?>" />

<?PHP


$heredite = array('null', 'maisons', 'etages', 'pieces');
$i = 0;



while ($heredite[$i] != $parent){
	$i +=1;
}

if (i<3){
	$table = $heredite[$i+1];
} else {
	$table = 'null';
}

// On retrouve l'arborescence pour présenter les racines
?>
<h3>Arboresence</h3>
<?php
$j = $i;
$num = $numero;
while ($j > 0) {
	$sql = "SELECT * FROM " . $heredite[$j] . " WHERE numero = " . $num;
	$resultat = exec_sql($sql);
	while($row = $resultat->fetch_assoc()) {
		if ($i == $j){
			echo $row["nom"] . ' < ';
		} else {
			echo '<a href=gestion_base.php?parent='. $heredite[$j] .'&numero=' . $num .'>' . $row["nom"] . '</a> < ';
		}
		$num = $row["parent"];
		$j-=1;
	}
}
echo '<a href=gestion_base.php?parent=null&numero=null> Accueil </a> <br />' ;

if ($i > 0) { 
	$sql = "SELECT nom FROM " . $parent . " WHERE numero = " . $numero;
	$resultat = exec_sql($sql);
	while($row = $resultat->fetch_assoc()) {
		$phrase = " de " . $row["nom"];
	}
} else {
	$phrase = "";
}


?>
<h3>Liste des <?php echo $table . $phrase ?></h3>
<?php

// On veut afficher les éléments de la table
// Il y aura 4 fonctions à chaque fois
// Voir détails pour voir les enfants (sauf pour pièces) en cliquant sur le nom
// Créer un élément dans la table
// Modifier un des élément de la table en cliquant à côté
// Supprimer un élément de la table en cliquant à côté


// AFFICHAGE DES ELEMENTS

$sql = 'SELECT * FROM ' . $table;
if ($numero != 'null') {
	$sql .=  ' WHERE parent =' . $numero;
}
//echo $sql . "<p>";
$resultat = exec_sql($sql);


while($row = $resultat->fetch_assoc()) {
	//echo $row["nom"] . '<br />';
	$lien = 'gestion_base.php?parent=' . $table . '&numero=' . $row["numero"];
	//echo $lien . '<br />';
	//$hyper = '<a href=\' ;
	if ($i != count($heredite)-2){
		echo '<a href=' . $lien . '>' .$row["nom"] . '</a>' ;
	} else {
		echo $row["nom"];
	}
	//LIEN POUR MODIFIER
	echo "\t";
	$lien = 'form_modif_ligne.php?table=' . $table . '&numero=' . $row["numero"];
	//echo '<a href=' . $lien . '> Modifier  </a> ' ;
	?>
	<a href=<?php echo $lien ?>><input type="button" value="Modifier"/></a>&nbsp;
    <?php
	//LIEN POUR SUPPRIMER
	echo "\t";
	$lien = 'supprimer_ligne.php?table=' . $table . '&numero=' . $row["numero"];
	//echo '<a href=' . $lien . '> Supprimer </a> <br />' ;
	?>
	<a href=<?php echo $lien ?>><input type="button" value="Supprimer"/></a>&nbsp;
    <?php
	echo '<br />' ;
}

echo '<br />' ;
echo '<br />' ;


//LIEN POUR AJOUTER UN ELEMENT
if ($parent == "null"){
	$numero=1;
}
$lien = 'form_creation.php?table=' . $table . '&parent=' .$numero ;
//echo '<a href=' . $lien . '> Ajouter </a> ';
?>
<a href=<?php echo $lien ?>><input type="button" value="Ajouter"/></a>&nbsp;
<?php


/*
//LIEN RETOUR A LA BASE
echo '<br />' ;
echo '<a href= > Retour </a> ';
*/



?>   

</body>
</html>