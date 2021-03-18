<?php
    /** @file		      : channels.php
	 *  @description      : Lecture des informations d'un canal avec l'api HTTP GET
	 *  @author           : Philippe SIMIER Lycée Touchard Le Mans
	 *	@version          : 1.0 du 25 mai 2020
		
		réécriture       : RewriteRule ^channels/([0-9]+).([a-zA-Z]+)$   api/channels.php?channelId=$1&type=$2 [QSA]
		requête          : channels/<channelId>.<type>
		requête réécrite : api/channels.php?channelId=752839&type=json
		
	    Parameters : 
			channelId (Required) Channel ID for the channel of interest.
			type      (Required) le type de sortie JSON XML
			
				
		Retour :  les données au format json ou xml suivant la demande
	**/
	
	require_once('../definition.inc.php');
	require_once('Api.php');

	use Aggregator\Support\Api;

    // Lecture des paramétres obligatoires
    $channelId = Api::obtenir("channelId", FILTER_VALIDATE_INT);
    $type      = Api::obtenir("type");
	
	// Lecture des paramétres facultatifs
	$callback  = Api::facultatif("callback", NULL);
	
    // Connexion à la base avec session heure UTC
    $bdd = Api::connexionBD(BASE, "+00:00");
	
	// construction de la requête SQL pour obtenir les information sur un canal
	$sql = "SELECT * FROM `vue_channels` WHERE id = {$channelId}";
	
	try{
        $stmt = $bdd->query($sql);
        if ($channel =  $stmt->fetchObject()){
			
			if ($channel->public_flag ==0) 
				$channel->public_flag = false;
			else
				$channel->public_flag = true;
			
			header("Access-Control-Allow-Origin: *");
			header('Content-type: application/json');
			if ($callback !== NULL){ 	
				echo $callback . '('; 
			}
			echo json_encode($channel, JSON_NUMERIC_CHECK);
			if ($callback !== NULL){ 	echo ')'; }
			
		}
    }
    catch(\PDOException $ex) {
		echo($ex->getMessage());    
	}	


?>