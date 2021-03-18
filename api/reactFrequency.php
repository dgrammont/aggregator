<?php

/** fichier      : api/reactFrequency.php
    description  : Script pour éxecuter les reacts frequency
    author       : Philippe SIMIER Lycée Touchard Le Mans
    utilisation  : php reactFrequency.php 10    (execute les reacts dont la fréquence d'éxécution est définit sur 10 minutes)

    installation dans crontab sur DMZ EDITOR=nano crontab -e
**/

	require_once('/var/www/Ruche/definition.inc.php');
	require_once('/var/www/Ruche/api/Api.php');
	require_once('/var/www/Ruche/api/React.class.php');

	use Aggregator\Support\Api;
	use Aggregator\Support\React;

	if ($argc === 2){

	    $bdd = Api::connexionBD(BASE, "+00:00");

	    try {
		// Y-a t'il des reacts  toutes les $argv[1] mins a exécuter
		$sql = "SELECT id FROM `reacts` WHERE `run_on_insertion`=0 AND `run_interval` = {$argv[1]};";

		$stmt = $bdd->query($sql);

		// Exécution des reacts
			while ($res =  $stmt->fetchObject()){
				$react = new React($bdd, $res->id );
				if( $react->perform() == 1){
					echo $react->getName() . " exécuté\n";
				}
			}
		}
		catch(\PDOException $ex) {
			echo "Internal Server Error\n";
			return 1;
		}
	} else {
		echo "usage : reactFrequency 10 \n";

    }
