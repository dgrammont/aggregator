<?php
/*----------------------------------------------------------------------------------
    @fichier  sigfox.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     13 Aout 2020
    @version  v2.0 - Second release						
    @details  Reception des datas du cloud Sigfox 
	
    Parametres : 
	    id     (Requis)  L'id du device emetteur des datas.
		time   (Requis)  le timestamp des datas
		data   (Requis)  les datas au format brute
		
		type	  (optionnal) le type de trame (0 aucun 1 mesures 2 batterie)
		seqNumber (optionnal) le numero de séquence
		field1    (optionnal) la valeur décodée du champs 1
		field2    (optionnal) la valeur décodée du champs 2
		field3    (optionnal) la valeur décodée du champs 3
		field4    (optionnal) la valeur décodée du champs 4
		field5    (optionnal) la valeur décodée du champs 5
		field6    (optionnal) la valeur décodée du champs 6		
------------------------------------------------------------------------------------*/

	require_once('./definition.inc.php');
	require_once('./api/Api.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;
	
	// Fonction callback
	// Retourne le code réponse html
	function callback($url, $post){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $post,
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cookie: request_method=POST"
		  ),
		));

		$ret = curl_exec($curl);
		
		if (empty($ret)) {

			$reponse = curl_error($curl);
		
		} else {
			$info = curl_getinfo($curl);		
			if (empty($info['http_code'])) {
				$reponse = "No HTTP code";
			} else {
				$reponse = $info['http_code'];
			}
		}
		curl_close($curl); // close cURL handler
		return $reponse;
	}
	// Fin de la fonction callback
	
	
	
	
	$bdd = Api::connexionBD(BASE);
	
	$dateTime = new DateTime();
	$dateTime->setTimezone(new DateTimeZone('UTC'));
	
	if ($_SERVER["CONTENT_TYPE"] == "application/x-www-form-urlencoded"){
		
		// Contrôle de la présence des paramètres id time et data en GET ou POST
		$idDevice    = Api::obtenir("id");
		$time        = Api::obtenir("time");
		$data        = Api::obtenir("data");
		
		// Lecture des paramètres falcultatifs seqNumber, type, field1 ... field6
		$seqNumber   = Api::facultatif("seqNumber", 'NULL');
		$type        = Api::facultatif("type", 'NULL');
		$field1      = Api::facultatif("field1", 'NULL');
		$field2      = Api::facultatif("field2", 'NULL');
		$field3      = Api::facultatif("field3", 'NULL');
		$field4      = Api::facultatif("field4", 'NULL');
		$field5      = Api::facultatif("field5", 'NULL');
		$field6      = Api::facultatif("field6", 'NULL');
		
		$dateTime->setTimestamp($time);

		try{

			// Recherche des callbacks
			$sql = sprintf("SELECT `write_api_key`,`url`,`payload` FROM `callbacks` WHERE `idDevice` = %s AND `type` = %s",
				$bdd->quote($idDevice),
				$bdd->quote($type)
			);
					
			$stmt = $bdd->query($sql);
			$reponse = "";		
			
			while ($callback = $stmt->fetchObject()){
				// si une clé présente appel de la fonction callback
				if (isset($callback->write_api_key)){

					$coef = explode(" ", $callback->payload);
					// Mise à l'echelle des valeurs
					$val1 = $field1/$coef[0]; 
					$val2 = $field2/$coef[1]; 
					$val3 = $field3/$coef[2]; 
					$val4 = $field4/$coef[3];
					$val5 = $field5/$coef[4];
					$val6 = $field6/$coef[5];
					$post = "api_key={$callback->write_api_key}&field1={$val1}&field2={$val2}&field3={$val3}&field4={$val4}&field5={$val5}&field6={$val6}";
					
					$retour = callback($callback->url, $post);
					if ($retour == "200")
						$reponse .= "OK ";
					else
						$reponse .= "NOK ";
				}
			}

			// On enregistre les datas reçues dans la table sigfox
			$sql = sprintf("INSERT INTO sigfox (idDevice, seqNumber, time, data, type, field1, field2, field3, field4, field5, field6, callbacks) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
				$bdd->quote($idDevice),
				$bdd->quote($seqNumber),
				$bdd->quote($dateTime->format('Y-m-d H:i:s')),
				$bdd->quote($data),
				$type,
				$field1,
				$field2,
				$field3,
				$field4,
				$field5,
				$field6,
				$bdd->quote($reponse)
			);
			
			
			$reponse = $bdd->exec($sql);		
			
		}
		catch (\PDOException $ex) 
		{
		   Api::envoyerErreur('503','Service Unavailable',$ex->getMessage());       	   
		}
	}
	elseif ($_SERVER["CONTENT_TYPE"] == "application/json"){
		// lecture du flux d'entrée (corps de la requête)
		$json = file_get_contents('php://input');
		$data = json_decode($json);
		
		
		$lat = $data->computedLocation->lat;
		$lng = $data->computedLocation->lng;
		try{
			
			// Enregistrement qualité de la réception
			$sql = sprintf("UPDATE `sigfox` SET `lqi` = %s,  `operatorName` = %s WHERE `sigfox`.`seqNumber` = %s AND `sigfox`.`idDevice` = %s"  ,
				$bdd->quote($data->lqi),
				$bdd->quote($data->operatorName),
				$bdd->quote($data->seqNumber),
				$bdd->quote($data->device)
			);
			
			$reponse = $bdd->exec($sql);
			
			// Enregistrement position calculée
			if ($data->computedLocation->status == 1){
				$sql = sprintf("UPDATE `sigfox` SET `latitude` = %s, `longitude` = %s, `radius` = %s, `source` = %s WHERE `sigfox`.`seqNumber` = %s AND `sigfox`.`idDevice` = %s"  ,
					$data->computedLocation->lat,
					$data->computedLocation->lng,
					$data->computedLocation->radius,
					$data->computedLocation->source,
					$bdd->quote($data->seqNumber),
					$bdd->quote($data->device)
				);
				$reponse = $bdd->exec($sql);
			
			}
		}
		catch (\PDOException $ex) 
		{
		   Api::envoyerErreur('503','Service Unavailable',$ex->getMessage());       	   
		}		
	}	
	else{
		
		Api::envoyerErreur('504','content-type', $_SERVER["CONTENT_TYPE"]);
		
	}	
?>