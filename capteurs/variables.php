<?PHP

	// Lecture de fichier
	
	function litVariable($nom, $default) {
	
		$nomFichier = 'files/' . $nom . '.txt';
		if ( !file_exists($nomFichier) ) {
        	return $default;
		}
		
		$file = fopen($nomFichier, 'r+');
		$res = fgets($file);
		fclose($file);
		
		return $res;
	}
	
	function ecritVariable($nom, $valeur) {
		
		$nomFichier = 'files/' . $nom . '.txt';
		
		$file = fopen($nomFichier, 'w+');
		fputs($file, $valeur);
		fclose($file);
	}
?>

