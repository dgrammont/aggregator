<?php
    /** fichier		 : administration/send_request.php
	    description  : Ce controleur envoie une requête http puis affiche la réponse 
					   
	    author       : Philippe SIMIER Lycée Touchard Le Mans
		
	**/


	include "authentification/authcheck.php" ;

	require_once('../definition.inc.php');
	require_once('../api/Api.php');
	require_once('../api/ThingHTTP.class.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\ThingHTTP;
	use Aggregator\Support\ThingHTTPException;

	// connexion à la base
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
	$id  = Api::obtenir("id", FILTER_VALIDATE_INT);
	
	try{
		$http = new ThingHTTP($bdd,$id);
		echo $http->send_request();
	}
	//catch exception
	catch(ThingHTTPException $e) {
		Api::envoyerErreur(500, $e->getMessage(), $e->getMessage());
	}
?>