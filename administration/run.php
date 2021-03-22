<?php

    /** @file		      : administration/run.php
	 *  @description      : Exécute le code transmis dans la variable code
	 *  @author           : Philippe SIMIER Lycée Touchard Le Mans
	 *	@version          : 1.0 du 04 mars 2021
		
		
	    Parameters : 
			code 		(Required) le code à éxécuter.
			language    (Required) le language utilisé
			
				
		Retour :  la sortie standard du code exécutée ou l'erreur renvoyée par l'interpréteur
	**/
	
	
	require_once('../definition.inc.php');
	require_once('../api/Api.php');
	require_once('../api/Channel.class.php');
	
	use Aggregator\Support\Api;
	$bdd = Api::connexionBD(BASE);
		
	$code = Api::obtenir("code");
	$language = Api::obtenir("language");
	
	
	// Execution du code
	if ($language === "php"){
	
		try {
			$result = eval($code);
		} 
		catch (ParseError $e) {
			echo "line " . $e->getLine() . " : " . $e->getMessage();
		}
	}
	
	if ($language === "shell"){
		
		exec($code, $output, $exitcode);
		echo "Returned with status {$exitcode} and output:\n";
		echo implode("\n", $output) ;
	}
	
	if ($language === "python"){
		
		$filename = __DIR__ .'/../temp/code.py';
		
		if (!$handle = fopen($filename, 'w+')) {
            echo "Impossible d'ouvrir le fichier ({$filename})";
            exit;
		}
		
		if (fwrite($handle, $code) === FALSE) {
			echo "Impossible d'écrire dans le fichier ($filename)";
			exit;
		}	
		
		fclose($handle);
		
		exec("python3 ".__DIR__ ."/../temp/code.py 2>&1", $output, $exitcode);
		echo "Returned with status {$exitcode} and output:\n";
		echo implode("\n", $output) ;		
		
		unlink($filename);
		
	}	