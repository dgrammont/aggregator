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

//------------si des données  sont soumises on les enregistre dans la table data.channels ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) { // si le token est valide
		try{
			if(isset($_POST['action']) && ($_POST['action'] == 'insert')){
				$sql = sprintf("INSERT INTO `data`.`channels` (`name`, `description`, `field1`, `field2`, `field3`, `field4`, `field5`, `field6`, `field7`, `field8`, `status`, `thing_id`,`write_api_key` ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);"
							  , $bdd->quote($_POST['name'])
							  , $bdd->quote($_POST['description'])
							  , $bdd->quote($_POST['field1'])
							  , $bdd->quote($_POST['field2'])
							  , $bdd->quote($_POST['field3'])
							  , $bdd->quote($_POST['field4'])
							  , $bdd->quote($_POST['field5'])
							  , $bdd->quote($_POST['field6'])
							  , $bdd->quote($_POST['field7'])
							  , $bdd->quote($_POST['field8'])
							  , $bdd->quote($_POST['status'])
							  , $bdd->quote($_POST['thing_id'])
							  , $bdd->quote($_POST['write_api_key'])
							  ); 	
				$bdd->exec($sql);

			}
			if(isset($_POST['action']) && ($_POST['action'] == 'update')){
				$sql = sprintf("UPDATE `channels` SET `name` = %s, `description`=%s, `field1`=%s, `field2`=%s, `field3`=%s, `field4`=%s, `field5`=%s, `field6`=%s , `field7`=%s , `field8`=%s , `status`=%s, `thing_id`=%s WHERE `channels`.`id` = %s;"
							  , $bdd->quote($_POST['name'])
							  , $bdd->quote($_POST['description'])
							  , $bdd->quote($_POST['field1'])
							  , $bdd->quote($_POST['field2'])
							  , $bdd->quote($_POST['field3'])
							  , $bdd->quote($_POST['field4'])
							  , $bdd->quote($_POST['field5'])
							  , $bdd->quote($_POST['field6'])
							  , $bdd->quote($_POST['field7'])
							  , $bdd->quote($_POST['field8'])
							  , $bdd->quote($_POST['status'])
							  , $bdd->quote($_POST['thing_id'])
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
		
		header("Location: channels.php");
		return;
	}	
}
// -------------- sinon lecture de la table data.channels  -----------------------------
else
{
	try{
		if (isset($_GET['id'])){
	 
			$sql = sprintf("SELECT * FROM `channels` WHERE `id`=%s", $bdd->quote($_GET['id']));
			$stmt = $bdd->query($sql);
			if ($channel =  $stmt->fetchObject()){
			   $channel->action = "update";
			   
		   } 
		}else {
			$channel = new stdClass();
			$channel->action = "insert";
			$channel->id = 0;
			$channel->thing_id = 0;
			$channel->name = "";
			$channel->description = "";
			$channel->field1 = "";
			$channel->field2 = "";
			$channel->field3 = "";
			$channel->field4 = "";
			$channel->field5 = "";
			$channel->field6 = "";
			$channel->field7 = "";
			$channel->field8 = "";
			$channel->status = "";
			$channel->tags = "";
			$channel->write_api_key = Api::genererKey($bdd);
			$channel->last_write_at = "";
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


	
	function afficherFormChannel($channel,$selectThing ){
		
		global $lang;
		// Création du tokenCSRF
		$tokenCSRF = STR::genererChaineAleatoire(32);
		$_SESSION['tokenCSRF'] = $tokenCSRF;
		
		echo Form::hidden('action', $channel->action);
		echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );

		$options = array( 'class' => 'form-control', 'readonly' => null);
		echo Form::input( 'int', 'id', $channel->id, $options, 'Id' );
		echo Form::input( 'text', 'write_api_key', $channel->write_api_key, $options, $lang['write_API_Key'] );
		echo Form::input( 'text', 'last_write_at', $channel->last_write_at, $options, $lang['last_write_at'] );
								
		$options = array( 'class' => 'form-control');
		echo Form::input( 'text', 'name', $channel->name, $options, $lang['name']);
		echo Form::textarea( 'description', $channel->description, $options); 						
		echo Form::select("thing_id", $selectThing, $lang['thing'], $channel->thing_id);
		echo Form::input( 'field1', 'field1', $channel->field1, $options, $lang['field'] . " 1");
		echo Form::input( 'field2', 'field2', $channel->field2, $options, $lang['field'] . " 2");
		echo Form::input( 'field3', 'field3', $channel->field3, $options, $lang['field'] . " 3");
		echo Form::input( 'field4', 'field4', $channel->field4, $options, $lang['field'] . " 4");
		echo Form::input( 'field5', 'field5', $channel->field5, $options, $lang['field'] . " 5");
		echo Form::input( 'field6', 'field6', $channel->field6, $options, $lang['field'] . " 6");
		echo Form::input( 'field7', 'field7', $channel->field7, $options, $lang['field'] . " 7");
		echo Form::input( 'field8', 'field8', $channel->field8, $options, $lang['field'] . " 8");
		echo Form::input( 'status', 'status', $channel->status, $options, $lang['status']);
	}	
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title><?= $lang['channel'] ?> - Aggregator</title>
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
							<?php  afficherFormChannel($channel,$selectThing );	?>

							<div class="form-group">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
								<a  class="btn btn-info" role="button" href="channels"><?= $lang['Cancel']?></a>
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
	