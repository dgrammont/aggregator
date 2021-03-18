<?php
    /** fichier		     : feeds.php
	    description      : Lecture des données pour tous les champs d'un canal avec HTTP GET
	    author           : Philippe SIMIER Lycée Touchard Le Mans
		version          : 1.1 du 12 mai 2020
		
		réécriture       : RewriteRule ^channels/([0-9]+)/feeds.([a-zA-Z]+)$   api/feeds.php?channelId=$1&type=$2 [QSA]
		endpoint         : channels/<channelId>/feeds.<type>
		endpoint réécrit : api/feeds.php?channelId=539387&type=json
		
	    Parameters : 
			channelId (Required) Channel ID for the channel of interest.
			type      (Required) le type de sortie JSON CSV
			results   (Optional) Number of entries to retrieve. The maximum number is 8000
			callback  (Optional) le nom de la fonction de retour JSONP
			start	  (Optional) la date de départ
			end	      (Optional) la date de fin
			round     (Optional) Le nombre optionnel de décimales à arrondir.
				
		Retour :  les données au format json ou csv suivant la demande
	**/
	
	require_once('../definition.inc.php');
	require_once('Api.php');
	require_once('Str.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;	
   
	// Lecture des paramétres obligatoires
	$channelId = Api::obtenir("channelId", FILTER_VALIDATE_INT);
	$type      = Api::obtenir("type");
		
	// Lecture des paramétres facultatifs
	$results   = Api::facultatif("results", "8000", FILTER_VALIDATE_INT);
	$callback  = Api::facultatif("callback", NULL);
	$start     = Api::facultatif("start", NULL);
	$end       = Api::facultatif("end", NULL);
    $round     = Api::facultatif("round", 3);
	
	// Connexion à la base avec session heure UTC
	$bdd = Api::connexionBD(BASE, "+00:00");
	
	// construction de la requête SQL pour obtenir les valeurs enregistrées dans la table feeds
	try{
		
		$sql = "SELECT * FROM `feeds` where `id_channel` = ". $channelId;
		if ($start !== NULL and $end !== NULL){
			$sql .= " and `date` between '" . $start . "' and '" . $end. "'";
		}	
		$sql .= " ORDER BY `date` DESC limit " . $results;

	
		// Mise en forme des données 
		if ($type == "json"){
			// Lecture des informations correspondant au channel dans la vue channels things
			$sqlChannels = "SELECT * FROM `vue_channels` WHERE `id` = " . $channelId;
			
			$stmt = $bdd->query($sqlChannels);
			if ($result =  $stmt->fetchObject()){
					
				$channel = array(
					'id' => intval($result->id),
					'name' => $result->name,
					'description' => $result->description,
					'latitude' =>    $result->latitude,
					'longitude' =>	 $result->longitude,
				);
				if ($result->field1 != "") $channel['field1'] = $result->field1;
				if ($result->field2 != "") $channel['field2'] = $result->field2;
				if ($result->field3 != "") $channel['field3'] = $result->field3;
				if ($result->field4 != "") $channel['field4'] = $result->field4;
				if ($result->field5 != "") $channel['field5'] = $result->field5;
				if ($result->field6 != "") $channel['field6'] = $result->field6;
				if ($result->field7 != "") $channel['field7'] = $result->field7;
				if ($result->field8 != "") $channel['field8'] = $result->field8;
				$channel['elevation'] =	$result->elevation;
				$channel['last_entry_id'] = intval($result->last_entry_id);				

				
				$stmt = $bdd->query($sql);
				
				$feeds = array();
				while ($feed =  $stmt->fetchObject()){
					$data = array(
							'created_at' => Str::formatDate($feed->date),
							'entry_id' => intval($feed->id), 
						);
						
						
					if ($result->field1 != "") $data['field1'] = Str::floatToString($feed->field1, $round);
					if ($result->field2 != "") $data['field2'] = Str::floatToString($feed->field2, $round);
					if ($result->field3 != "") $data['field3'] = Str::floatToString($feed->field3, $round);
					if ($result->field4 != "") $data['field4'] = Str::floatToString($feed->field4, $round);
					if ($result->field5 != "") $data['field5'] = Str::floatToString($feed->field5, $round);
					if ($result->field6 != "") $data['field6'] = Str::floatToString($feed->field6, $round);	
					if ($result->field7 != "") $data['field7'] = Str::floatToString($feed->field7, $round);	
					if ($result->field8 != "") $data['field8'] = Str::floatToString($feed->field8, $round);			
					array_push($feeds, $data);							
				}
				
				$feedsReverse = array_reverse($feeds);
				
				$output = array(
					'channel' => $channel,
					'feeds' => $feedsReverse
				);
				
				header("Access-Control-Allow-Origin: *");
				header("cache-control: max-age=0, private, must-revalidate");
				header('Content-type: application/json; charset=utf-8');
				if ($callback !== NULL){ 	
					echo $callback . '('; 
				}
				echo json_encode($output);
				if ($callback !== NULL){ 	echo ')'; }
			}else{
				echo "-1";
			}
		}
		if($type == "csv"){

			$nom_fichier = "channel_". $channelId . ".csv";

			header('Content-Type: application/csv-tab-delimited-table');
			header('Content-Disposition:attachment;filename='.$nom_fichier);
			
			
			echo "created_at,entry_id,field1,field2,field3,field4,field5,field6,field7,field8,latitude,longitude,elevation,status\n";
			$stmt = $bdd->query($sql);
			while ($data = $stmt->fetchObject()){
					echo $data->date." UTC,";
					echo $data->id.",";
					echo Str::floatToString($data->field1, $round).",";
					echo Str::floatToString($data->field2, $round).",";
					echo Str::floatToString($data->field3, $round).",";
					echo Str::floatToString($data->field4, $round).",";
					echo Str::floatToString($data->field5, $round).",";
					echo Str::floatToString($data->field6, $round).",";
					echo Str::floatToString($data->field7, $round).",";
					echo Str::floatToString($data->field8, $round).",";
					echo $data->latitude.",";
					echo $data->longitude.",";
					echo $data->elevation.",";			
					echo $data->status."\n";
			}
		}
	}
	catch(\PDOException $ex) {
		echo($ex->getMessage());    
	}
?>