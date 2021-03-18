<?php
include "authentification/authcheck.php" ;
	
require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../api/Form.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\Form;


// connexion à la base
    
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
		



// -------------- lecture de la table data.sigfox  -----------------------------

	if (isset($_GET['id'])){
		try{
		$sql = sprintf("SELECT latitude,longitude,radius FROM `sigfox` WHERE `id`=%s", $bdd->quote($_GET['id']));

		$stmt = $bdd->query($sql);
		$locate =  $stmt->fetchObject();

		}
		catch (\PDOException $ex) 
		{
		    echo($ex->getMessage());
			return;			
		}
   
	}   

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Sigfox - Locate</title>
    <!-- Bootstrap CSS version 4.1.1 -->
	<link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/ruche.css" />
    
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"></script>
	<script src="../scripts/gmaps.js"></script>
    

		
	<script>
			
	$(function () {
    
		/*****************  creation et affichage de la map **************/
		
		var	map = new GMaps({
			div: '#map-canvas',
			lat: <?php echo $locate->latitude; ?> , 
			lng: <?php echo $locate->longitude; ?> ,
			zoom : 10 ,
			mapType : 'terrain'
		});
	

		/************  placement d'une puce au milieu de la map ********/
		map.addMarker({
			lat: <?php echo $locate->latitude ; ?>, 
			lng: <?php echo $locate->longitude; ?>,
			title: "Position sigfox",
			infoWindow: {
			  content: '<p> <?php echo "Sigfox location : </br> lat : {$locate->latitude} <br /> lng : {$locate->longitude} <br /> radius : {$locate->radius}"; ?></p>' 
			  
			}
			
		});
		
		// https://hpneo.dev/gmaps/documentation.html#GMaps-createMarker
		
		map.drawCircle({
		    lat: <?php echo $locate->latitude ; ?>,
			lng: <?php echo $locate->longitude; ?>,
			radius: <?php echo $locate->radius; ?>,
			strokeColor: "#00FF00",
			strokeOpacity: 0.3,
			strokeWeight: 1,
			fillColor: "#00FF00",
			fillOpacity: 0.3
		});
		 
    });
	</script>
				
</head>
<body>

<?php require_once '../menu.php'; ?>

    <div class="container" style="padding-top: 65px;">
		
	    <div class="row">
			<!-- Localisation géographique -->
			<div class="col-md-12 col-sm-12 col-xs-12">	
				<div class="popin">
					<div id="map-canvas" style = "height: 500px; width: 100%;" ></div>
				</div>
			</div>	
		</div>
	<?php require_once '../piedDePage.php'; ?>
</div>
	
</body>

	
		
