// Les functions de ce module gère l'affichage et le fonctionnement des capteurs

// ***** Affichage
//
// L'affichage d'un capteur dépend de sa catégorie. Pour un simple poussoir il n'y a qu'une action : appuyer.
// Pour un theromostat, on peut lire la température, changer les température mini et maxi...
//
// Pour créer un capteur, il faut :
//    > lui donner un nom et utiliser ce nom dans "categorie" dans la base (exemples : 'poussoir', 'thermostat')
//    > créer un page nom.php (exemples: poussoir.php thermostat.php). La page retourne le code HTML correspond aux affichages et
//		actions possibles avec ce capteur
//    > la page doit être placée dans le répertoire capteurs (l'url est alors : (...)domo/capteurs/poussoir.php)
//
// L'application principale appelera la page pour afficher le capteur au bon endroit. L'appel se fait avec le paramètre
//      numero=xxxx
// Le numéro correspond à l'identifiant unique du capteur dans la station domotique. Cela permet de distinguer le poussoir de la salle
// de bains de celui du salon !
//
// ***** Interaction
//
// Il faut pouvoir rafraichir les valeurs indiquées, par exemple lorsque la porte s'ouvre ou lorsque la température change
// La manière dont le capteur interagit avec le serveur domotique peut changer d'un capteur à l'autre.
// 
// Pour le rafraichissement des valeurs affichées, la présente bibliotheque appel une fonction à interval régulier qui
// elle-même appelle des fonctions de rafraichissement.
//
// Si un capteur veut que la valeur affichée soit rafraichie régulièrement, il suffit de déclarer:
//		- La zone a rafraichir
//		- L'adresse à appeler pour récupérer le contenu
//

// **********************************************************************************************************************************
// Affichage
//
// L'application principale doit afficher dans un element de la page le code HTML d'un capteur connaissant sa catégorie et son numéro
// Le principe ce fait en deux temps :
//		1) appel de la fonction demandeAffichageCapteur(categorie, numero, element)
//		2) afficheCapteur(element, html)
//
// La fonction demandeAffichageCapteur appel la page categorie.php. Le retour se fait de manière asynchrone.
// Lorsque le résultat est reçu, la fonction afficheCapteur est appeléee


// ------------------------------------------------------------------------------------------------------------ 
// Crée un objet requete HTTP


function creeObjtHttpPourCapteur() {
	
	try {return new XMLHttpRequest();}
	catch (erreur) {}
	
	try {return new ActiveXObject("Msxml2.XMLHTTP");}
	catch (erreur) {}
	
	try {return new ActiveXObject("Microsoft.XMLHTTP");}
	catch (erreur) {}
	
	throw new Error("La création de l’objet pour les requêtes HTTP n’a pas pu avoir lieu.");
}

// ------------------------------------------------------------------------------------------------------------ 
// Envoi une commande (n'attend pas de retour)
// A utiliser par exemple dans onlick sur une commande capteur

function commandeCapteur(url) {

	var requete = creeObjtHttpPourCapteur();
	
	// Lance la requête
	
	requete.open("GET", url, true);
	requete.send(null);
	
	writeDebug("Commande capteur: " + url);
}


// ------------------------------------------------------------------------------------------------------------ 
// Fonction appelée lorsque le résultat de la requête est arrivé
// - element:	element HMTL dans lequel on veut placer l'affichage du capteur
// - codeHtml:  le code source à placer dans l'élément

function afficheCapteur(element, codeHtml) {

	// Insère le code dans l'élément cible
	
	element.innerHTML = codeHtml;
	
	// S'il y a une division avec id = exec, exécute le code javascript à l'intérrieur
	

	var script = element.querySelector("script");
	if (script != null) {
		try {eval(script.textContent);}
		catch (erreur) {
			writeDebug("ERREUR dans l'évaluation du script [<b>" + erreur + '</b>]');
			writeDebug(script.textContent);
		}
	}
}


// ------------------------------------------------------------------------------------------------------------ 
// Demande l'affichage d'un capteur
// - categorie: la catégorie du capteur donne le nom de la page à afficher
// - numero:    le numéro permet de savoir de quel capteur exactement on parle
// - element:	element HMTL dans lequel on veut placer l'affichage du capteur


function demandeAffichageCapteur(categorie, numero, element) {

	// Crée un requéteur http

	var requete = creeObjtHttpPourCapteur();
	
	// Lance la requête
	
	requete.open("GET", "capteurs/" + categorie + ".php?numero=" + numero, true);
	requete.send(null);
	requete.onreadystatechange = function() {
		if (requete.readyState == 4) {
			if (requete.status == 200)
				// Normal
				afficheCapteur(element, requete.responseText);
			else
				// Erreur
				afficheCapteur(element, "<b>Erreur dans l'affichage du capteur " + categorie + " numéro " + numero + "</b");
		}
	};
}

// ------------------------------------------------------------------------------------------------------------ 
// Demande le rafraichissement d'une valeur de l'affichage d'un capteur


function demandeRafraichissementCapteur(raf) { //element, url) {


	// Crée un requéteur http

	var requete = creeObjtHttpPourCapteur();
	
	// Lance la requête
	
	requete.open("GET", raf.url, true);
	requete.send(null);
	requete.onreadystatechange = function() {
		if (requete.readyState == 4) {
			if (requete.status == 200) {
				if (raf.traitement==null) {
					document.getElementById(raf.elementId).innerHTML = requete.responseText;
				} else {
					raf.traitement(raf.elementId, requete.responseText);
				}
			} else
				writeDebug("ERREUR: Impossible de raffraichir l'élément capteur " + raf.elementId + 'avec ' + raf.url);
				// Erreur
				var i = 1; // On ne fait rien, tant pis !!
		}
	};
}


// **********************************************************************************************************************************
// Rafraichissement
//

var capteursRaf = [];
var capteursRaf_timer = null;
var capteurRaf_interval = 1000;

// Reset

function capteurRaf_reset() {
	capteursRaf = [];
	if (capteursRaf_timer != null) {
		clearInterval(capteursRaf_timer);
		capteursRaf_timer = null;
	}
}

// Fonction de rafraichissement

function capteursRaf_rafraichit() {

	for (id in capteursRaf) {
	
		var raf = capteursRaf[id];

		// Interval indique le nombre de secondes entre deux rafraichissements effectifs
		// Un compteur partant de interval est décrémenté toutes les secondes
		
		raf.compteur -= 1;
		if (raf.compteur <= 0) {
			// Reinitialie le compteur
			
			raf.compteur = raf.interval;
			
			// Demande le rafraichissement
			
			demandeRafraichissementCapteur(raf);
		}
	}
}


// Fonction appelée dans l'affichage du capteur pour mettre à jour la visualisation

function capteursRaf_register(elementId, url, interval=1, traitement=null) {

	// Ajoute le rafraichissement dans la liste
	
	var raf = {
		elementId: 	elementId,
		url: 		url,
		traitement: traitement,
		interval: 	interval,
		compteur:	0
		}

	capteursRaf.push(raf);
	
	// Premier appel
	
	demandeRafraichissementCapteur(raf); //.element, raf.url);
	
	
	if (capteursRaf_timer == null) {
		capteursRaf_timer = setInterval(capteursRaf_rafraichit, capteurRaf_interval);
	}
	
}



