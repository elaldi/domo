$(function() {
    var temperatureCapteur1 = [];
    var typeCapteur1 = "température (°C)";
    var lienJSONCapteur1 = "capteur1.json";
    var capteur1Active = 0;
    var nomCapteur1 = "capteur1";
    var allumer1 = "#lectureCap1";
    var etteindre1 = "#desCap1";
    
    var temperatureCapteur2 = [];
    var typeCatpeur2 = "température (°C)";
    var lienJSONCapteur2 = "capteur2.json";
    var capteur2Active = 0;
    var nomCapteur2 = "capteur2";
    var allumer2 = "#lectureCap2";
    var etteindre2 = "#desCap2";
    
    var temperatureCapteur = [temperatureCapteur1,temperatureCapteur2];
    var lienJSONCapteur = [lienJSONCapteur1,lienJSONCapteur2];
    var capteurActive = [capteur1Active,capteur2Active];
    var nomCapteur = [nomCapteur1,nomCapteur2];
    var allumer = [allumer1,allumer2];
    var etteindre = [etteindre1,etteindre2];
    
    var nombreCapteur = nomCapteur.length;
    
    var unite = "°C";
    var typeCapteur = "Température (°C)"

    var seriesCap = [];
    
    var dateDebut = "";
    var dateFin = "";
    
    var nomPiece = "chambre";
    var conteneur = "#container";
    var d; /*Variable de récupération de données*/
    
    var numberDataDefault = 800;
    
    /*jourDate : permet de calculer le jour d'une date française en string de la forme "JJ/MM/AAA"*/
    function jourDate(date){
        return(parseInt(date[0]+date[1]));
    };
    
    /*moisDate : permet de calculer le mois d'une date française en string de la forme "JJ/MM/AAA"*/
    function moisDate(date){
        return(parseInt(date[3]+date[4]));
    };
    
    /*aneeDate : permet de calculer l'année d'une date française en string de la forme "JJ/MM/AAA"*/
    function anneeDate(date){
        return(parseInt(date[6]+date[7]+date[8]+date[9]));
    };
    
    function heureDate(time){
        return(parseInt(time[0]+time[1]));
    }
    
    function minuteDate(time){
        return(parseInt(time[3]+time[4]));
    }
    
    function secondeDate(time){
        return(parseInt(time[6]+time[7]));
    }
    
    /*traceGraphe(idBloc) : permet de tracer un graphe en ligne classique, à modifier pour pouvoir changer aussi les titres et les sous-titres, l'unité de l'axe en ordonnée. Il prend en paramètre idBloc, correspondant au bloc on l'on place notre graphique. Il doit exister en variable global startGraph, date à laquelle on commence à récupérer les données, et seriesCap, qui sert connaitre les données en abscisses.*/
    function traceGraphe(idBloc){
        $(idBloc).empty();
        $(idBloc).highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Capteurs pour la pièce ' + nomPiece
            },
            subtitle: {
                text: 'Données de ' + typeCapteur
            },
            xAxis: {
                type: 'datetime',
                labels: {
                    overflow: 'justify'
                }
            },
            yAxis: {
                title: {
                    text: typeCapteur
                },
                minorGridLineWidth: 0,
                gridLineWidth: 1,
                alternateGridColor: null,
            },
            tooltip: {
                valueSuffix: unite
            },
            plotOptions: {
                spline: {
                    lineWidth: 4,
                    states: {
                        hover: {
                            lineWidth: 5
                        }
                    },
                    marker: {
                        enabled: false
                    }
                }
            },
            series: seriesCap,
            navigation: {
                menuItemStyle: {
                    fontSize: '10px'
                }
            }
        });
    };
    
    /*createArrayData(timeDefault) : cette fonction permet de créer le tableau de donnée qui sera présent en abscisse. Il demande en paramètre une durée de default (un int) si l'on ne donne pas de date de début et de fin d'affichage de donées, le tableau de donnée qu'il faut modifier (array). De plus il demande en variable global la variable d qui correspond au fichier JSON lu. Il faut trouver une solution pour récupérer la bonne donnée dans le fichier JSON, la fonction ne pouvant récupérer que la température.*/
    function createArrayData(timeDefault, arrayData) {
            var data;
            var date;
            var heure;
            if (dateFin===""){
                if(dateDebut===""){
                    if(d.length<timeDefault){
                        var i = 0;
                    }
                    else {
                        var i = d.length-timeDefault;
                    };
                    for(i ; i<d.length ; i++) {
                        data = d[i].temperature;
                        date = d[i].date;
                        heure = d[i].heure;
                        arrayData.push([Date.UTC(anneeDate(date), moisDate(date), jourDate(date), heureDate(heure), minuteDate(heure), secondeDate(heure)),data]);
                    };
                }
            else{
                var i = 0 ;
                while(i<d.length && anneeDate(dateDebut)!=anneeDate(d[i].date) && moisDate(dateDebut)!=moisDate(d[i].date) && jourDate(dateDebut)!=jourDate(d[i].date) ){
                    i++;
                };
                for(i ; i<d.length ; i++) {
                    data = d[i].temperature;
                    date = d[i].date;
                    heure = d[i].heure;
                    arrayData.push([Date.UTC(anneeDate(date), moisDate(date), jourDate(date), heureDate(heure), minuteDate(heure), secondeDate(heure)),data]);
                };
                
            };
        }
        else {
            if(dateDebut===""){
                var i = 0 ;
                while(i<d.length && anneeDate(dateFin)!=anneeDate(d[i].date) && moisDate(dateFin)!=moisDate(d[i].date) && jourDate(dateFin)!=jourDate(d[i].date) ){
                    i++;
                };
                if(i<timeDefault){
                    var j = 0;
                }
                else {
                    var j = i-timeDefault;
                };
                for(j ; j<i ; j++) {
                    data = d[j].temperature;
                    date = d[j].date;
                    heure = d[j].heure;
                    arrayData.push([Date.UTC(anneeDate(date), moisDate(date), jourDate(date), heureDate(heure), minuteDate(heure), secondeDate(heure)),data]);
                };
            }
            else{
                var i = 0 ;
                while(anneeDate(dateDebut)!=anneeDate(d[i].date) || moisDate(dateDebut)!=moisDate(d[i].date) || jourDate(dateDebut)!=jourDate(d[i].date) ){
                    i++;
                };
                while(anneeDate(dateFin)!=anneeDate(d[i].date) || moisDate(dateFin)!=moisDate(d[i].date) || jourDate(dateFin)!=jourDate(d[i].date) ){
                    data = d[i].temperature;
                    date = d[i].date;
                    heure = d[i].heure;
                    arrayData.push([Date.UTC(anneeDate(date), moisDate(date), jourDate(date), heureDate(heure), minuteDate(heure), secondeDate(heure)),data]);
                    i++;
                };
            };
        };
    };
    
    /*var i = 0;
    for(i ; i<nombreCapteur; i++){
        $(allumer[i]).on('click', function(){
        $.getJSON(lienJSONCapteur[i],function(data){
            capteurActive[i] = 1 ;
            temperatureCapteur[i] = [];
            seriesCap = [];
            d = data.capteur;
            createArrayData(numberDataDefault, temperatureCapteur[i]);
            seriesCap.push({
                name: nomCapteur[i],
                data: temperatureCapteur[i],
            });
            var k = 0;
            for (k ; k<nombreCapteur && k!=i ; k++){
                if(capteurActive[k]===1){
                    $.getJSON(lienJSONCapteur[k],function(data){
                        temperatureCapteur[k] = [];
                        d = data.capteur;
                        createArrayData(numberDataDefault, temperatureCapteur[k]);
                        seriesCap.push({
                            name: nomCapteur[k],
                            data: temperatureCapteur[k],
                        });
                    });
                };
            };
            traceGraphe(conteneur);
        });
    });
    };*/
    
    
    $(allumer1).on('click', function(){
        $.getJSON(lienJSONCapteur1,function(data){
            capteur1Active = 1 ;
            temperatureCapteur1 = [];
            seriesCap = [];
            d = data.capteur1;            
            console.log("coucou");
            createArrayData(numberDataDefault, temperatureCapteur1);
            seriesCap.push({
                name: nomCapteur1,
                data: temperatureCapteur1,
            });
        
            if(capteur2Active===1){
                $.getJSON(lienJSONCapteur2,function(data){
                    temperatureCapteur2 = [];
                    d = data.capteur2;
                    createArrayData(numberDataDefault, temperatureCapteur2);
                    seriesCap.push({
                        name: nomCapteur2,
                        data: temperatureCapteur2,
                    });
                });
            };
            traceGraphe(conteneur);
        });
    });
    
    
    
    $(allumer2).on('click', function(){
        $.getJSON(lienJSONCapteur2,function(data){
            capteur2Active = 1 ;
            temperatureCapteur2 = [];
            seriesCap = [];
            d = data.capteur2;
            createArrayData(numberDataDefault, temperatureCapteur2);
            seriesCap.push({
                name: nomCapteur2,
                data: temperatureCapteur2,
            });
        
            if(capteur1Active===1){
                $.getJSON(lienJSONCapteur1,function(data){
                    temperatureCapteur1 = [];
                    d = data.capteur1;
                    createArrayData(numberDataDefault, temperatureCapteur1);
                    seriesCap.push({
                        name: nomCapteur1,
                        data: temperatureCapteur1,
                    });
                });
            };
            traceGraphe(conteneur);
        });
    });
    
    
    
    $(etteindre2).on('click', function(){
        capteur2Active = 0 ;
        seriesCap = [];
        $(conteneur).empty();
        if(capteur1Active === 1){
            seriesCap.push({
                name: nomCapteur1,
                data: temperatureCapteur1,
            });
            traceGraphe(conteneur);
        };
    });
    
    
    $(etteindre1).on('click', function(){
        capteur1Active = 0 ;
        seriesCap = [];
        $(conteneur).empty();
        if(capteur2Active === 1){
            seriesCap.push({
                name: nomCapteur2,
                data: temperatureCapteur2,
            });
            traceGraphe(conteneur);
        };
    });

    function actualisation() {
        if (dateFin==="") {
            if (capteur1Active===1 || capteur2Active===1) {
                seriesCap = [];
                if (capteur1Active===1){
                    $.getJSON(lienJSONCapteur1,function(data){
                        temperatureCapteur1 = [];
                        d = data.capteur1;
                        createArrayData(numberDataDefault, temperatureCapteur1);
                        seriesCap.push({
                            name: nomCapteur1,
                            data: temperatureCapteur1,
                        });
                        if (capteur2Active!==1){
                            traceGraphe(conteneur);
                        }
                        else if (capteur2Active===1){
                            $.getJSON(lienJSONCapteur2,function(data){
                                temperatureCapteur2 = [];
                                d = data.capteur2;
                                createArrayData(numberDataDefault, temperatureCapteur2);
                                seriesCap.push({
                                    name: nomCapteur2,
                                    data: temperatureCapteur2,
                                });
                                traceGraphe(conteneur);
                            });
                        };
                    });    
                    
                }
                else if (capteur2Active===1){
                    $.getJSON(lienJSONCapteur2,function(data){
                        temperatureCapteur2 = [];
                        d = data.capteur2;
                        createArrayData(numberDataDefault, temperatureCapteur2);
                        seriesCap.push({
                            name: nomCapteur2,
                            data: temperatureCapteur2,
                        });
                        traceGraphe(conteneur);
                    }); 
                };      
            };
        };
    };

    setInterval(actualisation,5000);
    calInit("calendarMainDebut", "Calendrier", "champ_date_Debut", "jsCalendar", "day", "selectedDay");
    calInit("calendarMainFin", "Calendrier", "champ_date_Fin", "jsCalendar", "day", "selectedDay");
   
    $('#selectionDate').on('click', function(){
        dateDebut=$('#champ_date_Debut').val();
        dateFin=$('#champ_date_Fin').val();
    });
});
