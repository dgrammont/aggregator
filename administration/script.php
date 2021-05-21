<?php
/**
 * @fichier  administration/support/administration/index.php							    		
 * @auteur   Léo Cognard (Touchard Washington le Mans)
 * @date     Mai 2021
 * @version  v1.0 - First release						
 * @details  Page du formulaire du script 
 */

include "authentification/authcheck.php" ;
	
require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../api/Form.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\Form;


/* connexion à la base*/
    
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
		

/*------------si des données  sont soumises on les enregistre dans la table data.timeControls --------- */
if( !empty($_POST['envoyer'])){
    if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) {
		try{
			if(isset($_POST['action']) && ($_POST['action'] == 'insert')){
				$sql = sprintf("INSERT INTO `data`.`scripts` (`user_id`, `name`, `code`, `language`) VALUES ( %s, %s, %s, %s);"
							  , $_POST['user_id']
							  , $bdd->quote($_POST['name'])
							  , $bdd->quote($_POST['code'])
							  , $bdd->quote($_POST['language'])
							  ); 
				$bdd->exec($sql);			
			}
			if(isset($_POST['action']) && ($_POST['action'] == 'update')){
				$sql = sprintf("UPDATE `data`.`scripts` SET `user_id` = %s, `name` = %s, `code` = %s, `language` = %s  WHERE `scripts`.`id` = %s;"
							  , $_POST['user_id'] 
							  , $bdd->quote($_POST['name'])
							  , $bdd->quote($_POST['code'])
							  , $bdd->quote($_POST['language'])
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
	
	/* destruction du tokenCSRF */
	unset($_SESSION['tokenCSRF']);
		
	header("Location: scripts.php");
	return;
    }    
}

/* -------------- sinon lecture de la table data.scripts  ----------------------------- */
else
{
	
	$id =  Api::facultatif("id", "", FILTER_VALIDATE_INT);
	
	if ($id !== ""){
		try{
		$sql = sprintf("SELECT * FROM `scripts` WHERE `id`=%s", $bdd->quote($id));
		$stmt = $bdd->query($sql);
			if ($script =  $stmt->fetchObject()){
				$script->action = 'update';
				$script->output = "";
			} 
		}
		catch (\PDOException $ex) 
		{
		    echo($ex->getMessage());
			return;			
		}
   
   
	}else {
		/*Création d'un nouvel objet script par défault*/
		$script = new stdClass();
		$script->action = 'insert';
		$script->id = 0;
  	    $script->user_id = $_SESSION['id'];
	    $script->name = "";
		$script->language = "shell";
		$script->code = $lang['insertCode'];
		$script->output = "";

	}
 
    
    try{	
		/* Création du $selectUser */
		$sql = "SELECT id,login FROM users ORDER BY id;";
		$stmt = $bdd->query($sql);
		
		$selectUser = array();
		while ($user = $stmt->fetchObject()){
			$selectUser[$user->id] = $user->login;
		}		
	}
	catch (\PDOException $ex) 
	{
	    echo($ex->getMessage());
        return;		
	}
}
/**
 * 
 * @global type $lang
 * @global type $script
 * @param type $selectUser
 * @details Affiche le formulaire de création d'un script 
 */
function afficherFormScript($selectUser){
	
	global $lang;
	global $script;
	
	$selectLanguage = array( 'shell' => "shell",
							 'php' => "php",
							 'python' => "python"
						    );
	
	/* Création du tokenCSRF */
	$tokenCSRF = STR::genererChaineAleatoire(32);
	$_SESSION['tokenCSRF'] = $tokenCSRF;
	
	echo Form::hidden('action', $script->action);
	echo Form::hidden('id', $script->id);
	echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );
								
	if($_SESSION['droits'] > 1) /*  un selecteur pour les administrateur */
		echo Form::select("user_id", $selectUser, $lang['user'], $script->user_id);
	else
		echo Form::hidden("user_id", $script->user_id );
	$options = array( 'class' => 'form-control');
	echo Form::input( 'text', 'name', $script->name, $options , $lang['name']);
	echo Form::select( 'language', $selectLanguage, $lang['language'], $script->language);
	$options = array( 'class' => 'form-control',
					  'rows' => '20',
                      'cols' => '80'					  
				    );
	echo Form::textarea( 'code', $script->code, $options , $lang['code']);
	
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Script - Aggregator</title>
    <!-- Bootstrap CSS version 4.1.1 -->
	<link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/ruche.css" />
	<link rel="stylesheet" href="../css/codemirror.css" />
    
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../scripts/bootstrap.min.js"></script>
	<script src="../scripts/codemirror/codemirror.js"></script>
	<script src="../scripts/codemirror/clike.js"></script>
	<script src="../scripts/codemirror/shell.js"></script>
	<script src="../scripts/codemirror/php.js"></script>
	<style type=text/css>
		.CodeMirror {border: 1px solid #ced4da; border-radius: .25rem; }
	</style>
	<script>		
    $(document).ready(function(){
		let editor = CodeMirror.fromTextArea(document.getElementById('code'), {
			mode: 'text/x-php',
			lineNumbers: true,
			matchBrackets: true
			});
			
		$( "#run" ).click(function( ) {
			console.log( "Handler for run called." );
			let code = editor.getValue();
			let lang = $("#language").children("option:selected").val();
			console.log( "language " + lang );
	
			$.post("run.php", {code: code, language: lang}, function(result){
				$("#output").val(result);
			});
			
			
			return false;
		});	
	});	
	</script>
</head>
<body>

<?php require_once '../menu.php'; ?>

<div class="container-fluid" style="padding-top: 65px;">
		
		<div class="row">
			<div class="col-md-8 col-sm-12 col-12">
				<div class="popin">
					<form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
						
							<?php afficherFormScript($selectUser); ?>
							
							<div class="form-group" style="margin:10px;">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
								<a  class="btn btn-info" role="button" href="scripts"><?= $lang['Cancel']?></a>
								<button  class="btn btn-primary" value="run" id="run" ><?=$lang['run']?> </button>
								
							</div>	
							<?php 
							$options = array( 'class' => 'form-control',
									'rows' => '10',
									'cols' => '80',
									'style' => 'font-family:monospace'
							);
							echo Form::textarea( 'output', $script->output, $options , $lang['output']); ?>
					</form>
				</div>
			</div>

			<div class="col-md-4 col-sm-12 col-12">
			    <div class="popin">
					<?= $lang['script_aide'] ?>
				</div>
			</div>
			
		</div>
		
		<?php require_once '../piedDePage.php'; ?>
</div>
</body>

	
		