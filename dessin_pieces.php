<?php
$image = $_GET["image"];
?>

<html>
	<head>
		<style>
		body
		{
		    background-color: #a9a1a1;
		}
		canvas
		{
		    aaapointer-events: none;       /* make the canvas transparent to the mouse - needed since canvas is position infront of image */
		    aaaposition: absolute;
		}
		</style>
		
		<script>
		
			// Le plan de fond
			
			var lePlan = null;
		
			// Récupère le canevas et le contexte de dessin
			
			function getCanevas() {
				return document.getElementById("canevas");
			}
			
 			function getHdc() {
			
				var can = getCanevas();
				
			    // get it's context
			    hdc = can.getContext('2d');
			
			    // set the 'default' values for the colour/width of fill/stroke operations
			    hdc.fillStyle = 'red';
			    hdc.strokeStyle = 'red';
			    hdc.lineWidth = 2;
			    
			    return hdc;
			}
			
			// Dessine le plan sur le fond du canevas
			
			function drawPlan() {
				var can = getCanevas();
			    var ctx = getHdc();
			    ctx.drawImage(lePlan, 0, 0, can.width, can.height);
			}
		
			// Charge l'image dans le canevas
			
			function loadImage(nomImage) {
			
			    var ctx = getHdc();

			    lePlan = new Image();
			    lePlan.src = 'plans/' + nomImage;
			    
			    lePlan.onload = drawPlan;
			  }			
			
		</script>
		
		
		
	
	
		<script type="text/javascript">
			//radius of click around the first point to close the draw
			var END_CLICK_RADIUS = 5;
			//the max number of points of your poygon
			var MAX_POINTS = 20;
			
			var mouseX = 0;
			var mouseY = 0;
			var isStarted = false;
			var points = null;
			
			var canvas = null;
			var ctx = null;
			
			window.onload = function() {

				//initializing canvas and draw color
				canvas = getCanevas();
				ctx = getHdc("canevas");
				changeColor("red");
				
				canvas.addEventListener("click", function(e) {
					var x = e.clientX-canvas.offsetLeft;
					var y = e.clientY-canvas.offsetTop;
					if(isStarted) {
						//drawing the next line, and closing the polygon if needed
						if(Math.abs(x - points[0].x) < END_CLICK_RADIUS && Math.abs(y - points[0].y) < END_CLICK_RADIUS) {
							isStarted = false;
						} else {
							points[points.length] = new Point(x, y);
							if(points.length >= MAX_POINTS) {
								isStarted = false;
							}
						}
					} else if(points == null) {
						//opening the polygon
						points = new Array();
						points[0] = new Point(x, y);
						isStarted = true;
					}
				}, false);
				
				//we just save the location of the mouse
				canvas.addEventListener("mousemove", function(e) {
					mouseX = e.clientX - canvas.offsetLeft;
					mouseY = e.clientY - canvas.offsetTop;
				}, false);
				
				//refresh time
				setInterval("draw();", 100);
			}
			
			//changes the color of the draw
			function changeColor(color) {
				ctx.strokeStyle = color;
			}
			
			//object representing a point
			function Point(x, y) {
				this.x = x;
				this.y = y;
			}
			
			//resets the application
			function reset() {
				isStarted = false;
				points = null;
			}
			
			//alerts the point list
			function save() {
				if(points == null) {
					alert("Rien a sauvegarder !");
				} else {
					var s = "";
					var sep = "";
					for(var a in points) {
						//inversing y axis by (canvas.height - points[a].y)
						//s += "(" + points[a].x + "," + (canvas.height - points[a].y) + ")\n";
						s += sep + points[a].x + "," + points[a].y;
						sep = ", ";
					}
					alert(s);
				}
			}
			
			//draws the current chape
			function draw() {
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				drawPlan();
			
				ctx.beginPath();

				if(points != null && points.length > 0) {
					ctx.moveTo(points[0].x, points[0].y);
					
					for(i = 1 ; i < points.length ; i++) {
						ctx.lineTo(points[i].x, points[i].y);
					}
					
					if(isStarted) {
						ctx.lineTo(mouseX, mouseY);
					} else {
						ctx.lineTo(points[0].x, points[0].y);
					}
				}
				
				ctx.stroke();
			}
		</script>
		
		<script>
			// Choisit le plan à traiter
			// Aucun contrôle...
			function changePlan() {
				var nom = document.forms["selectionPlan"]["fname"].value;
				loadImage(nom);
				return false;
			}
		</script>
	</head>
	<body>
		<div>
			<form name="selectionPlan" action="demo_form.asp" onSubmit="return changePlan()" method="post">
			Nom de l'image à mapper: <input type="text" name="fname">
			<input type="submit" value="Submit">
			</form>
		</div>
	
	
	
		<canvas id="canevas" style="border: 1px solid black;" width=600 height=400></canvas>
	
		<br /><br />
		<input type="button" value="Sauvegarder" onClick="save();" />&nbsp;
		<input type="button" value="Effacer" onClick="reset(); " />&nbsp;
		
		<script>
			loadImage(<?php echo $image ?>);
		</script>
	</body>
</html>