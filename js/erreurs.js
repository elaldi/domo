// Gère les messages d'erreur dans une zone appelée message_errer

var idZoneMessageErreur = "message_erreur";

function getZoneErreur() {
	return document.getElementById(idZoneMessageErreur);
}

function erreur_reset() {
	err = getZoneErreur();
	err.style.display = "none";
	err.innerHTML = "";
}

function erreur_message(message, titre=null, newLine=true) {
	
	err = getZoneErreur();
	err.style.display = "block";
	err.style.color = "white";
	err.style.backgroundColor = "red";
	err.style.marginTop = "10px";
	err.style.marginBottom = "10px";
	err.style.padding = "10px 10px 10px 10px";
	
	if (titre != null) {
		err.innerHTML += '<b>' + titre + '<b>: ';
	}
	err.innerHTML += message;
	if (newLine) {
		err.innerHTML += '<br/>';
	}
}