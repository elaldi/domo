$(function() {
    var temperatureCapteur1 = [];
    var temperatureCapteur2 = [];
    var capteur1Active = 0;
    var capteur2Active = 0;
    var temp;
    var d;
    var seriesCap = [];
    var dateDebut = "";
    var dateFin = "";
    var startGraph;
    var startTime;
    var nomPiece = "chambre";
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
                text: 'Données des capteurs de la pièce'
            },
            xAxis: {
                type: 'datetime',
                labels: {
                    overflow: 'justify'
                }
            },
            yAxis: {
                title: {
                    text: 'Temperature (°C)'
                },
                minorGridLineWidth: 0,
                gridLineWidth: 1,
                alternateGridColor: null,
            },
            tooltip: {
                valueSuffix: ' °C'
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
                    },
                    pointInterval: 1000, // one hour
                    pointStart: Date.UTC(anneeDate(startGraph), moisDate(startGraph)-1, jourDate(startGraph), heureDate(startTime), minuteDate(startTime), secondeDate(startTime))
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
            if (dateFin===""){
                if(dateDebut===""){
                    if(d.length<timeDefault){
                        var i = 0;
                    }
                    else {
                        var i = d.length-timeDefault;
                    };
                    startGraph = d[i].date;
                    startTime = d[i].heure;
                    for(i ; i<d.length ; i++) {
                        data = d[i].temperature;
                        arrayData.push(data);
                    };
                }
            else{
                var i = 0 ;
                while(i<d.length && anneeDate(dateDebut)!=anneeDate(d[i].date) && moisDate(dateDebut)!=moisDate(d[i].date) && jourDate(dateDebut)!=jourDate(d[i].date) ){
                    i++;
                };
                startGraph = d[i].date;
                startTime = d[i].heure;
                for(i ; i<d.length ; i++) {
                    data = d[i].temperature;
                    arrayData.push(data);
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
                startGraph = d[j].date;
                startTime = d[i].heure;
                for(j ; j<i ; i++) {
                    data = d[j].temperature;
                    arrayData.push(data);
                };
            }
            else{
                var i = 0 ;
                while(anneeDate(dateDebut)!=anneeDate(d[i].date) || moisDate(dateDebut)!=moisDate(d[i].date) || jourDate(dateDebut)!=jourDate(d[i].date) ){
                    i++;
                };
                startGraph = d[i].date;
                startTime = d[i].heure;
                while(anneeDate(dateFin)!=anneeDate(d[i].date) || moisDate(dateFin)!=moisDate(d[i].date) || jourDate(dateFin)!=jourDate(d[i].date) ){
                    data = d[i].temperature;
                    arrayData.push(data); 
                    i++;
                };
            };
        };
    };
    
    
    
    $('#lectureCap1').on('click', function(){
        $.getJSON('document.json',function(data){
            capteur1Active = 1 ;
            temperatureCapteur1 = [];
            seriesCap = [];
            d = data.capteur1;
            createArrayData(numberDataDefault, temperatureCapteur1);
            seriesCap.push({
                name: 'Capteur1',
                data: temperatureCapteur1,
            });
        
            if(capteur2Active===1){
                $.getJSON('document2.json',function(data){
                    temperatureCapteur2 = [];
                    d = data.capteur2;
                    createArrayData(numberDataDefault, temperatureCapteur2);
                    seriesCap.push({
                        name: 'Capteur2',
                        data: temperatureCapteur2,
                    });
                });
            };
            traceGraphe('#container');
        });
    });
    
    
    
    $('#lectureCap2').on('click', function(){
        $.getJSON('document2.json',function(data){
            capteur2Active = 1 ;
            temperatureCapteur2 = [];
            seriesCap = [];
            d = data.capteur2;
            createArrayData(numberDataDefault, temperatureCapteur2);
            seriesCap.push({
                name: 'Capteur2',
                data: temperatureCapteur2,
            });
        
            if(capteur1Active===1){
                $.getJSON('document.json',function(data){
                    temperatureCapteur1 = [];
                    d = data.capteur1;
                    createArrayData(numberDataDefault, temperatureCapteur1);
                    seriesCap.push({
                        name: 'Capteur1',
                        data: temperatureCapteur1,
                    });
                });
            };
            traceGraphe('#container');
        });
    });
    
    
    
    $('#desCap2').on('click', function(){
        capteur2Active = 0 ;
        seriesCap = [];
        $('#container').empty();
        if(capteur1Active === 1){
            seriesCap.push({
                name: 'Capteur1',
                data: temperatureCapteur1,
            });
            traceGraphe('#container');
        };
    });
    
    
    $('#desCap1').on('click', function(){
        capteur1Active = 0 ;
        seriesCap = [];
        $('#container').empty();
        if(capteur2Active === 1){
            seriesCap.push({
                name: 'Capteur2',
                data: temperatureCapteur2,
            });
            traceGraphe('#container');
        };
    });

    function actualisation() {
        if (dateFin==="") {
            if (capteur1Active===1 || capteur2Active===1) {
                seriesCap = [];
                if (capteur1Active===1){
                    $.getJSON('document.json',function(data){
                        temperatureCapteur1 = [];
                        d = data.capteur1;
                        createArrayData(numberDataDefault, temperatureCapteur1);
                        seriesCap.push({
                            name: 'Capteur1',
                            data: temperatureCapteur1,
                        });
                        console.log(seriesCap[0]+"1");
                        if (capteur2Active!==1){
                            traceGraphe('#container');
                        }
                        else if (capteur2Active===1){
                            $.getJSON('document2.json',function(data){
                                temperatureCapteur2 = [];
                                d = data.capteur2;
                                createArrayData(numberDataDefault, temperatureCapteur2);
                                seriesCap.push({
                                    name: 'Capteur2',
                                    data: temperatureCapteur2,
                                });
                                traceGraphe('#container');
                            });
                        };
                    });    
                    
                }
                else if (capteur2Active===1){
                    $.getJSON('document2.json',function(data){
                        temperatureCapteur2 = [];
                        d = data.capteur2;
                        createArrayData(numberDataDefault, temperatureCapteur2);
                        seriesCap.push({
                            name: 'Capteur2',
                            data: temperatureCapteur2,
                        });
                        traceGraphe('#container');
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
