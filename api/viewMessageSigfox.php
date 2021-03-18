<?php
    /** fichier		     : viewMessageSigfox.php
	    description      : Lecture des données pour tous les champs d'un message Sigfox
	    author           : Philippe SIMIER Lycée Touchard Le Mans
		version          : 1.0 du 21 juillet 2020
		
		réécriture       : 
		endpoint         : 
		endpoint réécrit : api/viewMessageSigfox.php?id=5&type=json
		
	    Parameters : 
			id        (Required) Channel id for the sigfox message.
			type      (Required) le type de sortie JSON CSV
			callback  (Optional) le nom de la fonction de retour JSONP
			
				
		Retour :  les données au format json ou csv suivant la demande
	**/
	
	require_once('../definition.inc.php');
	require_once('Api.php');
	require_once('Str.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;	
   
	// Lecture des paramétres obligatoires
	$id = Api::obtenir("id", FILTER_VALIDATE_INT);
	$type      = Api::obtenir("type");
		
	$callback  = Api::facultatif("callback", NULL);

	
	// Connexion à la base avec session heure UTC
	$bdd = Api::connexionBD(BASE, "+00:00");
	
	// construction de la requête SQL pour obtenir les valeurs enregistrées dans la table feeds
	try{
		
		$sql = "SELECT * FROM `sigfox` where `id` = {$id}";
		$stmt = $bdd->query($sql);
		// Mise en forme des données 
		if ($type == "json"){
			
			if ($result =  $stmt->fetchObject()){
					
				$data['field1'] = $result->field1;
				$data['field2'] = $result->field2;
				$data['field3'] = $result->field3;
				$data['field4'] = $result->field4;
				$data['field5'] = $result->field5;
				$data['field6'] = $result->field6;
				$data['type']   = $result->type;
				$data['time']   = $result->time;
				$data['seqNumber'] = $result->seqNumber;
				$data['idDevice'] = $result->idDevice;
				
				header("Access-Control-Allow-Origin: *");
				header("cache-control: max-age=0, private, must-revalidate");
				header('Content-type: application/json; charset=utf-8');
				if ($callback !== NULL){ 	
					echo $callback . '('; 
				}
				echo json_encode($data);
				if ($callback !== NULL){ 	echo ')'; }
			}else{
				echo "-1";
			}
		}

	}
	catch(\PDOException $ex) {
		echo($ex->getMessage());    
	}
?>