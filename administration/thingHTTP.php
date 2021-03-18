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

$bdd = Api::connexionBD(BASE);

//------------si des données  sont soumises on les enregistre dans la table data.thinghttps ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) {
		if(isset($_POST['action']) && ($_POST['action'] == 'insert')){
			$sql = sprintf("INSERT INTO `data`.`thinghttps` (`user_id`, `api_key`, `url`, `auth_name`, `auth_pass`, `method`, `content_type`, `http_version`, `host`, `body`, `name`, `parse`) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);"
						  , $bdd->quote($_POST['user_id'])   
						  , $bdd->quote($_POST['api_key'])
						  , $bdd->quote($_POST['url'])
						  , $bdd->quote($_POST['auth_name'])
						  , $bdd->quote($_POST['auth_pass'])
						  , $bdd->quote($_POST['method'])
						  , $bdd->quote($_POST['content_type'])
						  , $bdd->quote($_POST['http_version'])
						  , $bdd->quote($_POST['host'])
						  , $bdd->quote($_POST['body'])
						  , $bdd->quote($_POST['name'])
						  , $bdd->quote($_POST['parse'])				  
						  ); 	
			$bdd->exec($sql);
		}
		if(isset($_POST['action']) && ($_POST['action'] == 'update')){
			$sql = sprintf("UPDATE `thinghttps` SET `user_id` = %s, `url` = %s, `method`=%s, `auth_name`=%s, `auth_pass`=%s, `content_type`=%s, `http_version`=%s, `host`=%s, `body`=%s, `name`=%s , `parse`=%s  WHERE `thinghttps`.`id` = %s;"
						  , $bdd->quote($_POST['user_id'])
						  , $bdd->quote($_POST['url'])
						  , $bdd->quote($_POST['method'])
						  , $bdd->quote($_POST['auth_name'])
						  , $bdd->quote($_POST['auth_pass'])
						  , $bdd->quote($_POST['content_type'])
						  , $bdd->quote($_POST['http_version'])
						  , $bdd->quote($_POST['host'])
						  , $bdd->quote($_POST['body'])
						  , $bdd->quote($_POST['name'])
						  , $bdd->quote($_POST['parse'])
						  , $_POST['id']
						  );

			$bdd->exec($sql);
		}
	// destruction du tokenCSRF
	unset($_SESSION['tokenCSRF']);
	
	header("Location: thingHTTPs.php");
	return;
	
	}	
}
// -------------- sinon lecture de la table data.channels  -----------------------------
else
{
	if (isset($_GET['id'])){
 
		$sql = sprintf("SELECT * FROM `thinghttps` WHERE `id`=%s", $bdd->quote($_GET['id']));
		$stmt = $bdd->query($sql);
		if ($thinghttp =  $stmt->fetchObject()){
		   $thinghttp->action = "update";
		   
	   } 
	}else {
		$thinghttp = new stdClass();
		$thinghttp->action = "insert";
		$thinghttp->id = 0;
		$thinghttp->user_id = $_SESSION['id'];
		$thinghttp->api_key = Str::genererChaineAleatoire();
		$thinghttp->url = "";
		$thinghttp->auth_name = "";
		$thinghttp->auth_pass = "";
		$thinghttp->method = "";
		$thinghttp->content_type = "";
		$thinghttp->http_version = "";
		$thinghttp->host = "";
		$thinghttp->body = "";
		$thinghttp->name = "";
		$thinghttp->parse = "";		
   }

    // Création du selectUser 
	$sql = "SELECT id,login FROM users ORDER BY id;";
	$stmt = $bdd->query($sql);
	
	$selectUser = array();
	while ($user = $stmt->fetchObject()){
		$selectUser[$user->id] = $user->login;
	}


	
	function afficherFormThingHTTP($thinghttp){    
		
		global $lang;
		global $selectUser;
		
		// Création du tokenCSRF
		$tokenCSRF = STR::genererChaineAleatoire(32);
		$_SESSION['tokenCSRF'] = $tokenCSRF;
		
		echo Form::hidden('action', $thinghttp->action);
		echo Form::hidden('user_id', $thinghttp->user_id);
		echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );
		
							
		$options = array( 'class' => 'form-control', 'readonly' => null);
		echo Form::input( 'int', 'id', $thinghttp->id, $options, 'Id' );
		echo Form::input( 'text', 'api_key', $thinghttp->api_key, $options, $lang['API_Key'] );
							
		if($_SESSION['droits'] > 1) //  un selecteur pour les administrateur
		    echo Form::select("user_id", $selectUser, $lang['user'], $thinghttp->user_id);
		else
			echo Form::hidden("user_id", $thinghttp->user_id);
							
		$options = array( 'class' => 'form-control');							
		echo Form::input( 'text', 'name', $thinghttp->name, $options, $lang['name'] );
		echo Form::input( 'text', 'url', $thinghttp->url, $options, 'url' );
		echo Form::input( 'text', 'auth_name', $thinghttp->auth_name, $options, 'auth name' );
		echo Form::input( 'text', 'auth_pass', $thinghttp->auth_pass, $options, 'Auth Password' );
		$listMethod = array('GET'=>'GET' , 'POST'=>'POST' , 'PUT'=>'PUT' , 'DELETE'=>'DELETE' );
		echo Form::select("method", $listMethod , $lang['method'], $thinghttp->method);
		echo Form::input( 'text', 'content_type', $thinghttp->content_type, $options, 'content type' );
		$listVersion = array('1.0' => '1.0', '1.1' => '1.1');
		echo Form::select("http_version", $listVersion , "http version", $thinghttp->http_version);
		echo Form::input( 'text', 'host', $thinghttp->host, $options, 'host' );
		$optionsArea = array( 'class' => 'form-control', 'rows' => '4');
		echo Form::textarea("body", $thinghttp->body, $optionsArea, 'body');
		echo Form::input( 'text', 'parse', $thinghttp->parse, $options, 'parse' );
	}
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title><?= $lang['thingHTTP'] ?> - Aggregator</title>
		<!-- Bootstrap CSS version 4.1.1 -->
		<link rel="stylesheet" href="../css/bootstrap.min.css" >
		<link rel="stylesheet" href="../css/ruche.css" />
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="../scripts/bootstrap.min.js"></script>
		
	</head>
<body>

	<?php require_once '../menu.php'; ?>

	<div class="container-fluid" style="padding-top: 65px;">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="popin">
					<form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
						
						<?php afficherFormThingHTTP($thinghttp); ?>
														
						<div class="form-group">
						    </br>
							<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
							<a  class="btn btn-info" role="button" href="thingHTTPs"><?= $lang['Cancel'] ?></a>
						</div>	
					</form>
				</div>
			</div>
			
			<div class="col-md-6 col-sm-12 col-xs-12">
			    <div class="popin">
					<?= $lang['thingHTTP_aide'] ?>
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>	