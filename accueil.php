<?php
    require_once 'cookieConfirm.php';
	require_once('definition.inc.php');
	require_once('./api/Api.php');
	require_once('./lang/lang.conf.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;
	
	$bdd = Api::connexionBD(BASE);
	
	function makeMarker(){
		
		global $bdd;
		try{
			if (!isset($_SESSION['id'])) // Personne n'est connecté donc objet publique
				$sql = 'SELECT * FROM `things` where status = "public";';
			else if ($_SESSION['id'] != 0)
				$sql = "SELECT * FROM `things` where user_id = ". $_SESSION['id'];
			else   // C'est root qui est connecté, tous les objets sont affichés
				$sql = "SELECT * FROM `things`";
								
			$reponse = $bdd->query($sql);
			$marker = "var markers = [\n";
			$infoWindowContent = "var infoWindowContent = [\n";
			$deb = true;
			while ($thing = $reponse->fetchObject()){
				if (!$deb) {
					$marker .= ","; 
				    $infoWindowContent .= ",";
				}
				$marker .= "['{$thing->name}', {$thing->latitude} , {$thing->longitude} ]\n";
				$infoWindowContent .= "['<div class=\"info_content\"><h5>{$thing->name}</h5></div>']\n";
				$deb = false;
			}
			$marker .= "];\n";
			$infoWindowContent .= "];\n"; 
			$reponse->closeCursor();
			echo $marker;
			echo $infoWindowContent;
			
		}
		catch(\PDOException $ex){
			echo $ex->getMessage();
			return;
		}
		
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title><?= $lang['Browse_Sites'] ?> - Aggregator</title>
		<!-- Bootstrap CSS version 4.1.1 -->
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/ruche.css" />
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="scripts/bootstrap.min.js"></script>
		<script src="scripts/file-explore.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function () {
                $(".file-tree").filetree();
				$(".channels").click(afficheModal);
				$(".btn-afficher").click(afficherVue);
				
				// Asynchronously Load the map API 
				var script = document.createElement('script');
				script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyBKUqx5vjYkrX15OOMAxFbOkGjDfAPL1J8&language=<?= $langue ?>&sensor=false&callback=initialize";
				document.body.appendChild(script);
			});
			
		function initialize() {
			var map;
			var bounds = new google.maps.LatLngBounds();
			var mapOptions = {
				mapTypeId: 'roadmap'
			};
							
			// Display a map on the page
			map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			map.setTilt(45);
				
			// Multiple Markers
			<?php makeMarker() ?>
											
			// Display multiple markers on a map
			var infoWindow = new google.maps.InfoWindow(), marker, i;
			
			// Parcoure le tableau des marqueurs et place chacun sur la carte  
			for( i = 0; i < markers.length; i++ ) {
				var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
				bounds.extend(position);
				marker = new google.maps.Marker({
					position: position,
					map: map,
					title: markers[i][0]
				});
				
				// Autorise chaque marqueur à avoir une fenêtre d'informations    
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infoWindow.setContent(infoWindowContent[i][0]);
						infoWindow.open(map, marker);
					}
				})(marker, i));

				// Centre automatiquement la carte en ajustant tous les marqueurs sur l'écran
				map.fitBounds(bounds);
			}

			// Si le niveau de zoom est supérieur à 18
			// Remplace le niveau de zoom sur la carte une fois la fonction fitBounds exécutée
			var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
				
				if(this.getZoom() > 15){
					this.setZoom(15);
				}
				google.maps.event.removeListener(boundsListener);
			});	
		}
			
	    function afficheModal(event){
			
			var url = $(this).attr("href");
			console.log(url);
			
			$.getJSON( url , function( data, status, error ) {
				console.log(data.channel);
				var contenu = "<div>";
				$.each( data.channel, function( key, val ) {
					if (key.indexOf("field") != -1){
						contenu += '<div id = "choix" class="form-check">'
						contenu += '<input class="form-check-input" type="checkbox" value="' + key.substring(5,6) + '" id="'+ key +'">';
						contenu += '<label class="form-check-label" for="'+ key +'">';
						contenu += val;
						contenu += '</label>';
						contenu += '</div>';
					}	
				});
				contenu += "</div>";
				
				$("#modal-contenu").html( contenu );
				var title = data.channel.id + " : " + data.channel.name; 
				console.log(title);
				$("#ModalLongTitle").html( title );
				$(".btn-afficher").attr("id", data.channel.id );  // On fixe l'attribut id du button avec l'id du canal
				$(".btn-afficher").attr("name", data.channel.name );  // On fixe l'attribut name du button avec le nom du canal
				$("#ModalCenter").modal('show');
			});
			
			event.preventDefault();   // bloque l'action par défaut sur le lien cliqué
		}
		
		function afficherVue(event){
			var channel_id = $(this).attr("id");
			var channel_name = $(this).attr("name");
			
			var choix = [];
			var anyBoxesChecked = false;
			$('#choix  input[type="checkbox"]').each(function() {
				if ($(this).is(":checked")) {
					choix.push($(this).val());
					anyBoxesChecked = true;
				}
			});
			if (anyBoxesChecked == false) {
				console.log("pas de choix");
			} 

			console.log("choix : " + choix); 
			var url = "./thingView?channel=" + channel_id; 
			for (i = 0; i < choix.length; i++){
				url += '&field' + i + '=' + choix[i];	
			}
			console.log(url);
			//window.location.href=url;
			window.open(url,'_blank');	
			$("#ModalCenter").modal('hide');
			
		}	   
		</script>

	</head>

	<body>
		<?php require_once 'menu.php'; ?>
		<div class="container" style="padding-top: 75px;">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="popin" style="margin: 0px; padding : 4px;">
					<ul class="file-tree ">
					<?php
						try{
							
						function listerChannels($id){
							global $lang;
							global $bdd;
							$sql = 'SELECT count(*) as nb FROM `channels` WHERE `thing_id`='. $bdd->quote($id);
							$url = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
							
							if ($bdd->query($sql)->fetchObject()->nb > 0){
								$sql = 'SELECT * FROM `channels` WHERE `thing_id`='.  $bdd->quote($id);
								$reponse = $bdd->query($sql);
								echo "<li  class='folder-data'><a href='#'>{$lang['Data_visualisation']}</a>\n";
								echo "<ul id=\"channel\">\n";
								while($channel = $reponse->fetchObject()){
									echo "<li>\n";
									echo "<a class='channels' href='{$url}/channels/{$channel->id}/feeds.json?results=0' target='_blank' >{$channel->name}</a>\n";
									echo "</li>\n";								
								}
								echo "</ul>\n";
								echo "</li>\n";
							}
						}	
						
						function listerMatlabVisu($id){
							global $lang;
							global $bdd;
							$sql = "SELECT count(*) as nb FROM `Matlab_Visu` WHERE `things_id`={$id}";					
							if ($bdd->query($sql)->fetchObject()->nb > 0){
								$sql = "SELECT * FROM `Matlab_Visu` WHERE `things_id`={$id}";
								$reponse2 = $bdd->query($sql);
								echo "<li  class='folder-matlab'><a href='#'>{$lang['Data_Analysis']}</a>\n";
								echo "<ul>\n";
									while ($matalVisu = $reponse2->fetchObject()){
										echo "<li class='analysis'>\n";
										echo '<a target=_blank" href="./MatlabVisualization?id='. $matalVisu->thing_speak_id.'&name='. urlencode($matalVisu->name) .'">'.$matalVisu->name. '</a>';
										echo '</li>';
									}
								echo "</ul>\n";
								echo "</li>\n";
							}	
						}
						
						
							if (!isset($_SESSION['id']))
								$sql = 'SELECT * FROM `things` where status = "public";';
							else if ($_SESSION['droits'] == 1)
								$sql = "SELECT * FROM `things` where user_id = ". $_SESSION['id'];
							else   // C'est un administrateur qui est connecté
								$sql = "SELECT * FROM `things`";
							
							$reponse = $bdd->query($sql);
							while ($thing = $reponse->fetchObject()){
									echo '<li class="folder-root ' .$thing->class .'">	<a href="#">' . $thing->name . '</a>'; 
										echo '<ul>';
										listerChannels($thing->id);
										listerMatlabVisu($thing->id);
										echo '</ul>';
									echo '</li>';
									
							}
							$reponse->closeCursor();
						}
						catch (\PDOException $ex){
							echo "erreur BDD";
							die('Erreur : ' . $ex->getMessage());
						}
						?>								
					</ul>
				</div>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="popin" style="margin: 0px;">
					<div  id="map-canvas" style = "height: 500px; width: 100%;" ></div>
					</div>
				</div>
			</div>
		<?php require_once 'piedDePage.php'; 
		      require_once 'cookieConsent.php'; 
	    ?>
		</div>
		<!--Fenêtre Modal -->
		<div class="modal" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenter" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="ModalLongTitle">Message !</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body" id="modal-contenu">
				...
			  </div>
			  <div class="modal-footer">
			    <button type="button" class="btn btn-primary btn-afficher"><?= $lang['display'] ?></button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang['close'] ?></button>
			  </div>
			</div>
		  </div>
		</div>
		<!--Fin de fenêtre Modal -->
	</body>
</html>

