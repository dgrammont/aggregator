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



// Lecture des paramètres obligatoires
$id = Api::obtenir("id", FILTER_VALIDATE_INT);
// Lecture des paramètres facultatifs
$time_zone = Api::facultatif("time_zone", "+00:00");

$bdd = Api::connexionBD(BASE, $time_zone);

$feedback = "";
$feedbackDisplay = "none";
$feedbackStyle = "";

// Si des données  sont soumises on les enregistre dans la table data.feeds ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) {
	    
		$dossier = 'import/';
		
		
		if(isset($_FILES['datacsv'])){			
			$fichier = basename($_FILES['datacsv']['name']);
			
			if ($fichier == ""){ 
			    echo 'Vous devez sélectionner un fichier !';
				return;
		    }
		}
		
		//  Test de l'extension
		$infosfichier = pathinfo($_FILES['datacsv']['name']);
		$extension_upload = $infosfichier['extension'];
		$extensions_autorisees = array('csv','txt');
		if (!in_array($extension_upload, $extensions_autorisees)) {
			echo 'Erreur type de fichier non autorisé !';
			return;
		}	
		//  Déplacement du fichier
		if(!move_uploaded_file($_FILES['datacsv']['tmp_name'], $dossier . $fichier)){
			echo 'Erreur upload fichier !';
			return;
		}	
		// Ouverture du fichier importé
		$fp = fopen("{$dossier}{$fichier}", "r");
		$nb = -1;
		$nbErreur = 0;
		$nbSuccess = 0;
		$tabErreur = "";
		
		// Préparation de la requête
		$sql  = "INSERT INTO `data`.`feeds` (`id_channel`, `date`, `field1`, `field2`, `field3`, `field4`, `field5`, `field6`, `field7`, `field8`, `latitude`,`longitude`,`elevation`,`status`)";
        $sql .=	" VALUES ({$id}, :date, :field1, :field2, :field3, :field4, :field5, :field6, :field7, :field8, :latitude, :longitude, :elevation, :status)";
		$stmt = $bdd->prepare($sql);
		$stmt->bindParam(':date',  $date);
		$stmt->bindParam(':field1', $field1);
		$stmt->bindParam(':field2', $field2);
		$stmt->bindParam(':field3', $field3);
		$stmt->bindParam(':field4', $field4);
		$stmt->bindParam(':field5', $field5);
		$stmt->bindParam(':field6', $field6);
		$stmt->bindParam(':field7', $field7);
		$stmt->bindParam(':field8', $field8);
		$stmt->bindParam(':latitude', $latitude);
		$stmt->bindParam(':longitude', $longitude);
		$stmt->bindParam(':elevation', $elevation);
		$stmt->bindParam(':status', $status);
		
	    while (!feof($fp)) {    
			$nb++;
			$ligne =  fgetcsv($fp, 1024);
			if (!($ligne)){
				$tabErreur .= "ligne {$nb} : fin de fichier <br>";             				
				continue;
			}	
			
			// La première ligne contient le nom des champs
            if ($nb == 0) {
                $champs = $ligne;
                continue;
            }
			
			// les autres lignes contiennent les valeurs
			$field1 = NULL;
			$field2 = NULL;
			$field3 = NULL;
			$field4 = NULL;
			$field5 = NULL;
			$field6 = NULL;
			$field7 = NULL;
			$field8 = NULL;
			$created_at = "";
			$latitude = NULL;
			$longitude = NULL;
			$elevation = NULL;
			$status = NULL;

			for ($i=0; $i< count($ligne) ; $i++){
				if ($ligne[$i] != '' && $ligne[$i] != 'nan'){
					${$champs[$i]} = $ligne[$i];
				}	
			}
			
			// La colonne created_at est obligatoire et la cellule ne doit pas être vide
			if (!isset($created_at) || $created_at == "") {
                  	$tabErreur .= "ligne {$nb} : created_at est vide <br>";
					$nbErreur++;
                  	continue;
            }
			
			$date = substr($created_at, 0, 19);
			
			// Exécution de la requéte préparée
			try{
				$stmt->execute();
				$nbSuccess++;
			}
			
			catch (\PDOException $ex) 
			{
				$tabErreur .= "ligne {$nb} : " . $ex->getMessage() . "<br>";
				$nbErreur++;				
			}	
		}
	   
	   
	    
	    // fermeture du fichier et destruction du tokenCSRF
		fclose($fp);
	    unset($_SESSION['tokenCSRF']);
		
		// Affichage des opérations
		$feedback  = "Nombre de lignes importées avec success: $nbSuccess <br>";
		$feedback .= "Nombre de lignes avec des erreurs: $nbErreur <br>";
		$feedbackDisplay = "block";
		if ($nbErreur > 0){
			$feedbackStyle = "alert-danger";
			$feedback .= '<hr>';
			$feedback .= substr($tabErreur, 0, 512);			
		}else{
			$feedbackStyle = "alert-success";
		}	
	}
}

function afficherFormImport($id){
	
	global $lang;
	// Création du tokenCSRF
	$tokenCSRF = STR::genererChaineAleatoire(32);
	$_SESSION['tokenCSRF'] = $tokenCSRF;
			
	echo Form::hidden( 'action', 'import');
	echo Form::hidden( 'tokenCSRF', $_SESSION["tokenCSRF"] );
    echo Form::hidden( 'MAX_FILE_SIZE', '8000000');
    echo Form::hidden( 'id', $id);	
	$options = array( 'class' => 'form-control', 'required' => 'required');
	echo Form::input( 'file', 'datacsv', '', $options, "Fichier");
	
	$timeZone['select_time_zone'] = array(
	'-08:00' => '(GMT -07:00) Los_Angeles - Pacific Time (US&Canada)',
	'-07:00' => '(GMT -07:00) Denver - Mountain Time (US&Canada)',
	'-06:00' => '(GMT -06:00) Chicago - Central Time (US&Canada)',
	'-05:00' => '(GMT -05:00) New_York - Eastern Time (US&Canada)',
	'-04:00' => '(GMT -04:00) Halifax - Atlantique Time (Canada)',
	'-03:00' => '(GMT -03:00) Brasilia - America/Sao_Paulo',
	'-02:00' => '(GMT -02:00) Atlantic/South_Georgia',
	'-01:00' => '(GMT -01:00) Atlantic/Cape_Verde',
	'+00:00' => '(GMT +00:00) UTC',
    '+01:00' => '(GMT +01:00) Paris - Europe/Moscow',
    '+02:00' => '(GMT +02:00) Athens - Europe/Athens',
	'+03:00' => '(GMT +03:00) Moscow - Europe/Moscow',
	'+04:00' => '(GMT +04:00) Volgograd - ',
	'+05:00' => '(GMT +05:00) Karachi');
	
	echo Form::select("time_zone", $timeZone['select_time_zone'] , "Time zone", "+00:00" );
}


?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Import/Export - Aggregator</title>
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
				<div class="alert <?= $feedbackStyle ?>" role="alert" style="display: <?= $feedbackDisplay ?>">				
				    <?= $feedback ?>
				</div>				
				<div class="popin">
				    <h3>Import</h3>
					<p><?= $lang['upload_CSV'] ?></p>
					<form class="form-horizontal" method="post"  name="importCSV" enctype="multipart/form-data">
					
						<?php afficherFormImport($id) ?>
						
						<div class="form-group">
							</br>
							<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > Upload </button>
							
						</div>					
					</form>
					<br>
					<hr>
					<h3>Export</h3>
					<p><?= $lang['download_CSV'] ?></p><br>
					<a  class="btn btn-primary" role="button" href="../api/feeds.php?channelId=<?= $id ?>&type=csv"> Download </a>
					<a  class="btn btn-info" role="button" href="channels"> <?= $lang['Cancel'] ?></a>
					
				</div>
			</div>
			
			<div class="col-md-6 col-sm-12 col-12">
			    <div class="popin">
				    <?= $lang['importexport_aide'] ?>
					<div>
					<pre>
created_at,field1,field2,field3,field4,field5,field6,field7,field8,latitude,longitude,elevation,status
2021-04-18 16:04:42 UTC,11.5837,23,52.30,,,,,,,,,
					</pre>
					</div>
				
				</div>
			</div>
		</div>	

		<?php require_once '../piedDePage.php'; ?>
	</div>	
</body>