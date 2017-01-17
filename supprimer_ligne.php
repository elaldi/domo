<?PHP

include "acces_bdd.php";

// Debug

//foreach ($_GET as $key => $value) {
//    echo $key . ' => ' . $value . '<br />';
//}

$table = $_GET["table"];
$numero = $_GET["numero"];


//TROUVER LA TABLE
$heredite = array('null', 'maisons', 'etages', 'pieces');
$i = 0;


while ($heredite[$i] != $table){
	$i +=1;
}

//On commence par supprimer directement la ligne.

$sql = 'DELETE FROM ' .$table . ' WHERE numero=' . $numero;
//$sql = 'DELETE FROM maisons WHERE numero=3';
echo $sql;
exec_sql($sql);

//Il faut maintenant supprimer tous les enfants.

$list_parents = array($table => $numero);

while ($i < count($heredite)-1){
	$table_parent = $heredite[$i];
	$i +=1;
	$table = $heredite[$i];
	$j=0;
	//echo 'La table est ' . $table . ' et la table parente est ' . $table_parent . '<br/>';
	while($j < count($list_parents[$table_parent])){ 
		$sql = 'SELECT numero FROM ' . $table . ' WHERE parent = ' . $list_parents[$table_parent][$j];
		//echo $sql . '<br/>';
		$resultat = exec_sql($sql);
		while($row = $resultat->fetch_assoc()) {
			$list_parents[$table][] = $row["numero"];
			//echo $row["numero"];
		}
		$sqlb = 'DELETE FROM ' . $heredite[$i] . ' WHERE parent=' . $list_parents[$table_parent][$j];
		exec_sql($sqlb);
		echo $sqlb;
		$j+=1;
		//print_r($list_parents);
		//echo '<br/>';
	}		
}

?>