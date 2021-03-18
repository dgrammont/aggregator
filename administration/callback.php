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

$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

//------------si des données  sont soumises on les enregistre dans la table data.callbacks ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) { // si le token est valide
		try{
			if(isset($_POST['action']) && ($_POST['action'] == 'insert')){
				$sql = sprintf("INSERT INTO `data`.`callbacks` (`idDevice`, `type`, `url`, `write_api_key`, `payload`) VALUES ( %s, %s, %s, %s, %s);"
							  , $bdd->quote($_POST['idDevice'])
							  , $bdd->quote($_POST['type'])
							  , $bdd->quote($_POST['url'])
							  , $bdd->quote($_POST['write_api_key'])
							  , $bdd->quote($_POST['payload'])
							  ); 	
				$bdd->exec($sql);

			}
			if(isset($_POST['action']) && ($_POST['action'] == 'update')){
				$sql = sprintf("UPDATE `callbacks` SET `idDevice` = %s, `type`=%s, `url`=%s, `write_api_key`=%s, `payload`=%s WHERE `callbacks`.`id` = %s;"
							  , $bdd->quote($_POST['idDevice'])
							  , $bdd->quote($_POST['type'])
							  , $bdd->quote($_POST['url'])
							  , $bdd->quote($_POST['write_api_key'])
							  , $bdd->quote($_POST['payload'])
							  , $_POST['id']
							  );

				$bdd->exec($sql);
			}
		}
		catch (\PDOException $ex) 
		{
		    echo($ex->getMessage());
			return;
		}		
		
		// destruction du tokenCSRF
		unset($_SESSION['tokenCSRF']);
		
		header("Location: comSigfox.php");
		return;
	}	
}
// -------------- sinon lecture de la table data.callbacks  -----------------------------
else
{
	try{
		if (isset($_GET['id'])){
	 
			$sql = sprintf("SELECT * FROM `callbacks` WHERE `id`=%s", $bdd->quote($_GET['id']));
			$stmt = $bdd->query($sql);
			if ($callback =  $stmt->fetchObject()){
			   $callback->action = "update";
			   
		    } 
		}else {
			$callback = new stdClass();
			$callback->action = "insert";
			$callback->id = 0;
			$callback->idDevice = "";
			$callback->type = NULL;
			$callback->url = "";
			$callback->write_api_key = "";
			$callback->payload = "";
		}

		// Création du selectThing
		
		if($_SESSION['droits'] > 1)
			$sql = "SELECT id,name FROM `things` ORDER BY id;";
		else
			$sql = "SELECT id,name FROM `things` where user_id = {$_SESSION['id']} ORDER BY id;";
		
		$stmt = $bdd->query($sql);
		
		$selectThing = array();
		while ($thing = $stmt->fetchObject()){
			$selectThing[$thing->id] = $thing->name;
		}
	}
	catch (\PDOException $ex) 
	{
	    echo($ex->getMessage());
		return;
	}


	
	function afficherFormCallback($callback){
		
		global $lang;
		// Création du tokenCSRF
		$tokenCSRF = STR::genererChaineAleatoire(32);
		$_SESSION['tokenCSRF'] = $tokenCSRF;
		
		echo Form::hidden('action', $callback->action);
		echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );

		$options = array( 'class' => 'form-control', 'readonly' => null);
		echo Form::input( 'int', 'id', $callback->id, $options, 'Id' );
		
		$options = array( 'class' => 'form-control');
		echo Form::input( 'text', 'idDevice', $callback->idDevice, $options, "Device Id");
		echo Form::input( 'text', 'type', $callback->type, $options, "Type");
		echo Form::input( 'text', 'url', $callback->url, $options, "Url");
		echo Form::input( 'text', 'write_api_key', $callback->write_api_key, $options, $lang['write_API_Key'] );
		echo Form::input( 'text', 'payload', $callback->payload, $options, "Custom payload");
		
		
	}	
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Callback - Aggregator</title>
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
			<div class="col-md-6 col-sm-12 col-12">
				<div class="popin">
					<form class="form-horizontal" method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
							<?php  afficherFormCallback($callback);	?>

							<div class="form-group">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
								<a  class="btn btn-info" role="button" href="comSigfox"><?= $lang['Cancel']?></a>
							</div>	
					</form>
				</div>
			</div>
			
			<div class="col-md-6 col-sm-12 col-12">
			    <div class="popin">
				<?= $lang['channel_aide'] ?>
				</div>
			</div>
		</div>	

		<?php require_once '../piedDePage.php'; ?>
	</div>
	
</body>
	