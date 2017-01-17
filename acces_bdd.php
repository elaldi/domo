<?PHP

$sql_debug = 1;


function debug_sql($sql, $message=NULL) {
	//global $sql_debug;
	if ($sql_debug) {
		echo "<p><i>Debug SQL";
		if ($message) {
			echo " : " . $message; 
		}
		echo "</i></p><p><b>" . $sql . "</b></p>";
	}
}

function ouvre_bdd() {
	$servername = "localhost:8888";
	$username = "root";
	$password = "root";
	$dbname = "Test";

	// Create connection
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}
	
	return $conn;
}

function ferme_bdd($conn) {
	$conn->close();
}


function exec_sql($sql) {
	
	//debug_sql($sql, "exec_sql");
	
	$bdd = ouvre_bdd();
	
	$result = $bdd->query($sql);

	ferme_bdd($bdd);
	
	return $result;

}

function lire_table($sql) {
	
	echo "<p>Lecture avec : ".$sql . "<p>";

	$result = exec_sql($sql);
	
	if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
			foreach ($row as $key => $value) {
				echo $key . ": <b>" . $value . "</b>, ";
			}
		echo "<br/>";
		}
	} else {
    	echo "0 results<br/>";
	}
}

function max_colonne($table, $colonne) {
	

	$sql = "SELECT max(" . $colonne . ") AS maxi FROM " . $table . ";";
	
	$result = exec_sql($sql);
	$row = $result->fetch_assoc();
	
	$maxi = $row["maxi"];
	
	return $maxi;

}

function colonnes_table($table) {
	
	$result = exec_sql("SHOW COLUMNS FROM " . $table . ";");
	
	$cols = array();
	
	while($row = $result->fetch_assoc()) {
		$cols[] =  $row["Field"];
	}
	
	return $cols;
}
/*
function etages_croissants($etages){
	$vect = array();
	$i=1;
	$j=1;
	$min=$etages[1]["niveau"];
	echo $min;
	$indice = 1;
	while (count($etages)>0){
		while($i<=count($etages)){
			if ($etages[$i]["niveau"]<$min){
				$min = $etages[$i]["niveau"];
				$indice = $i;
			}
			$i+=1;
			echo $i;
			echo '</br>';
			echo count($etages);
			echo '</br>';
		}
	$vect[$j] = $etages[$indice];
	echo 'indice = ' . $indice;
	echo '</br>';
	unset($etages[$indice]); 		
	$i=1;
	$j+=1;
	print_r($vect);
	echo '</br>';
	print_r($etages);
	}
	return($vect);
}

$etages = array(
		1 => array(
			"maison" => 1,
			"niveau" => 0,
			"nom" => "1er étage",
			"plan" => "plan0.jpg"
		),
		2 => array(
			"maison" => 2,
			"niveau" => 0,
			"nom" => "Rez de chaussée",
			"plan" => "plan1.jpg"
		),
		3 => array(
			"maison" => 2,
			"niveau" => 1,
			"nom" => "Etage",
			"plan" => "plan2.jpg"
		),
		4 => array(
			"maison" => 2,
			"niveau" => 2,
			"nom" => "Combles",
			"plan" => "plan3.jpg"
		),
		5 => array(
			"maison" => 1,
			"niveau" => 1,
			"nom" => "2e étage",
			"plan" => "plan3.jpg"
		)
	);

echo count($etages);
print_r($etages);
print_r($etages[1]);
unset($etages[1]);
print_r($etages);
echo '</br>';
etages_croissants($etages);
*/

//etages_croissants($etages);

?>
