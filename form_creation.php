<?PHP
	include "acces_bdd.php";
	
	$table = $_GET["table"];
	$parent = $_GET["parent"];
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>

<h1>Création d'un élément quelconque</h1>

La table est : <?PHP echo $table; ?>;



<form action="creer_ligne.php">

<input type="hidden" name="table" value="<?=$table?>" />
<input type="hidden" name="parent" value="<?=$parent?>" />

<?PHP
	
	$cols = colonnes_table($table);
	
	foreach ($cols as $value) {	
		$br = "";
		if ($value != "numero" & $value != "parent") {
			//$value = trim($value);
			$sqlb = "SELECT libelle FROM sys_libelles WHERE nom_colonne = '" . $value . "';";
			$resultat = exec_sql($sqlb);
			$ligne = $resultat->fetch_assoc();
			foreach($ligne as $cle => $valeur) {
					$joli_nom = $valeur;
			}		
			echo $joli_nom . ' : <input type="text" ';
			$br = "<br/>";
			echo  ' name = "' . $value .'">' . $br;
		}
	}


?>    
    <input type="submit" value="Créer">

</form>

<?php
//LIEN POUR DESSINER UNE PIECE
if ($table == "pieces"){
	$sql = 'SELECT plan FROM etages WHERE numero=' . $parent;
	$resultat = exec_sql($sql);
	while($row = $resultat->fetch_assoc()) {
		$image = $row["plan"];
		$lien = 'dessin_pieces.php?image="' . $image . '"';
		echo '<br />' ;
		//echo '<a href=' . $lien . '> Dessiner pièce </a> ';
	}
	//echo '<a href=dessin_pieces.html > Dessiner pièce </a> ';
}

?>

<a href=<?php echo $lien ?>><input type="button" value="Dessiner une pièce"/></a>&nbsp;




</body>
</html>