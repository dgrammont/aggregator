<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

$id  = Api::obtenir("id", FILTER_VALIDATE_INT);

$sql = "DELETE FROM `blogs` WHERE `blogs`.`id` = {$id}";
$nb = $bdd->exec($sql);

if ($nb == 1){
	echo "Success";
}
else{
	echo "Failed";
}