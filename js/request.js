// ------------------------------------------------------------------------------------------------------------ 
// ------------------------------------------------------------------------------------------------------------ 
// Ce module sert à appeler le serveur
// Le serveur retourne une chaîne json qui est analysée
// En cas d'erreur, la fonction appelServeur retourne false
// Sinon, elle retourne la variable contenant le json interprété

// La fonction beurk est appellée si on n'arrive pas à joindre le serveur

function beurk(a, b) {
	erreur_message("Impossible de joindre le serveur : " + a);
}


// ------------------------------------------------------------------------------------------------------------ 
// La fonction retour_ok est appellée lors du retour de la réponse du serveur
// Elle transforme la chaine en json et appelle à son tour la fonction retour donnée par l'utilisateur

function retour_ok(s, retour) {

	// DEBUG
	
	writeDebug(s);
	
	// La chaîne retour est du json : on l'interpète dans la variable rep
	
	try {
	
		rep = JSON.parse(s);
		
		// S'il y a le mot clef erreur et qu'il est différent de 0, c'est qu'il y a une erreur dans les paramètres
		// d'interrogation du serveur
		
		if (rep == null) {
		
			writeDebug("ATTENTION: Le parsing retourne un tableau vide: json = [" + s + "]");

		} else if ((rep["erreur"] != null) && (rep["erreur"] != 0)) {
			document.write(s,"<br/>");
			document.writeln("<p>Erreur code " + rep["erreur"] + " : " + rep["description"] + "</p>");
			return null;
		}
	
	} catch (e) {
		document.write(s,"<br/>");
		document.write("<p>Parsing error:", e, "</p>");
		return null;
	}
	
	writeDebug("Traitement json ok: ");
	debug_dump(rep);
	
	retour(rep);

}

// ------------------------------------------------------------------------------------------------------------ 
// Rquête


function makeHttpObject() {
	
	try {return new XMLHttpRequest();}
	catch (erreur) {}
	
	try {return new ActiveXObject("Msxml2.XMLHTTP");}
	catch (erreur) {}
	
	try {return new ActiveXObject("Microsoft.XMLHTTP");}
	catch (erreur) {}
	
	throw new Error("La création de l’objet pour les requêtes HTTP n’a pas pu avoir lieu.");
}

function simpleHttpRequest(url, retour) {
	var requete = makeHttpObject();
	requete.open("GET", url, true);
	requete.send(null);
	requete.onreadystatechange = function() {
		if (requete.readyState == 4) {
			if (requete.status == 200)
				return retour_ok(requete.responseText, retour);
			else
				beurk(requete.status, requete.statusText);
		}
	};
}

// ------------------------------------------------------------------------------------------------------------ 
// ------------------------------------------------------------------------------------------------------------ 
// La fonction fonction principale

function appelServeur(key, value, retour) {
	writeDebugSepa();
	writeDebug("Appel serveur: " + key + "=" + value);
	simpleHttpRequest("serveur.php?" + key + "=" + value, retour);
}
