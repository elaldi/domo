<html>

<head>
	<script type="text/javascript" src="js/request.js" ></script>
</head>

<body>
	<script>
	
		// Fonction appelée lorsque les résultats arrivent
		// Se contente d'imprimer le résultat
	
		function traitementTableauRetour(table) {
			for (id in table) {
				document.write(id + ' --&gt; ' + table[id]["nom"] + '<br/>');
			}
		}
		
		// Lance la requête de consultation des données
		// Consulte les données
		
		appelServeur("maisons", "1", traitementTableauRetour)
		
	</script>
	
</body>

</html>
