<?php

	include "base.php";
	
	// Cette page répond aux questions sur la configuration de la maison et sur la valeur des capteurs
	// Les réponses sont faites au format json
	//
	// Il n'y a aucun contrôle de sécurité !!!!
	//
	// - etages = id maison 			: Retourne les étages de la maisons
	// - pieces = id étage  			: Les pièces d'un étage
	// - capteurs = id piece 			: Les capteurs présents dans la pièce
	// - detail_capteur = id capteur 	: Le détail d'un capteur
	//
	// En cas d'erreur retourne un json {"erreur": code, "description": description détaillée de l'erreur"}
	
	if (isset($_GET["maisons"])) {
		echo getMaisons($_GET["maisons"]);
		exit();
	}
	
	
	if (isset($_GET["etages"])) {
		echo getEtages($_GET["etages"]);
		exit();
	}
	
	if (isset($_GET["pieces"])) {
		echo getPieces($_GET["pieces"]);
		exit();
	}
	
	if (isset($_GET["capteurs"])) {
		echo getCapteurs($_GET["capteurs"]);
		exit();
	}
	
	echo '{"code": 1, "description": "Requête non valide"}';
	
	
?>

