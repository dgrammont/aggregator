<?php

/** 
  fichier		   : sound.php
  description      : Lecture des informations statistiques pour un fichier son
  author           : Lycée Touchard Le Mans
  version          : 1.1 du 27 mars 2021

  Parameters :
  file (Required) Channel ID for the channel of interest.
  type      (Required) le type de sortie JSON TEXT HTML
  
  Retour :  les données au format json ou texte suivant la demande
 **/




require_once('../definition.inc.php');
require_once('Api.php');
require_once('Str.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// Lecture des paramétres obligatoires
$file = Api::obtenir("file");
$type = Api::obtenir("type");

// Mise en forme des données
header("Access-Control-Allow-Origin: *");
header("cache-control: max-age=0, private, must-revalidate");
header('Content-type: application/json; charset=utf-8');

$commande = "sox ../{$file} -n stats 2>&1";
exec($commande, $output, $exitcode);


$key = 0;
echo "{ \"{$key}\" : \"File {$file}\"\n";
$key++;
foreach ($output as $value) {
    echo ", \"{$key}\" : \"{$value}\"\n";
	$key++;
} 
echo "}";