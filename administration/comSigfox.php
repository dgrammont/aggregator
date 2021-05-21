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
$idDevice    = Api::obtenir("id");

// Si le formulaire supprimer_message a été soumis
if(isset($_POST['supprimer_message'])){
	// Si un élément a été sélectionné création de la liste des id à supprimer
	if (count($_POST['array_message']) > 0){
		$Clef=$_POST['array_message'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `sigfox` WHERE `id` IN " . $supp;
		$bdd->exec($sql);
	}
}

// Si le formulaire supprimer_callback a été soumis
if(isset($_POST['supprimer_callback'])){
	// Si un élément a été sélectionné création de la liste des id à supprimer
	if (count($_POST['array_callbacks']) > 0){
		$Clef=$_POST['array_callbacks'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `callbacks` WHERE `id` IN " . $supp;
		$bdd->exec($sql);
	}
}

function afficherRetourCallbacks($retour){
	$code = "<a href='#' data-toggle='popover' data-trigger='hover' data-html='true' data-placement='auto' title='Callback' ";
	if (strpos($retour, "NOK" ) !== false){
		// NOK
		$code .= "data-content= 'Callback - <b>NOK</b>'><img src='../images/Callback_NOK.png' alt='Callback NOK'  width='24' height='22'></a>";
	}elseif (strpos($retour, "OK" ) !== false){
	    // OK
        $code .= "data-content= 'Callback - <b>OK</b>'><img src='../images/Callback_OK.png' alt='Callback OK'  width='24' height='22'></a>";		
	}else{
		// N/A
		$code .= "data-content= 'Callback - <b>N/A</b>'><img src='../images/Callback_NA.png' alt='Callback N/A'  width='24' height='22'></a>";
	}
	return $code;
}

function afficherSignal($lqi, $operatorName){
	$code = "<a href='#' data-toggle='popover' data-trigger='hover' data-html='true' data-placement='auto' title='Info Réseau' ";
    switch($lqi){
		case "Excellent":
            $code .= "data-content= 'Operator : <b>{$operatorName}</b><br />Quality : <b>Excellent</b>'><img src='../images/signal_5.png' alt='Excellent'  width='46' height='22'></a>";
            break;
        case "Good":
            $code .= "data-content= 'Operator : <b>{$operatorName}</b><br />Quality : <b>Good</b>'><img src='../images/signal_4.png' alt='Good'  width='46' height='22'></a>";
            break;
		case "Average":
            $code .= "data-content= 'Operator : <b>{$operatorName}</b><br />Quality : <b>Average</b>'><img src='../images/signal_3.png' alt='Average'  width='46' height='22'></a>";
            break;
		case "Limit":
			$code .= "data-content= 'Operator : <b>{$operatorName}</b><br />Quality : <b>Limit</b>'><img src='../images/signal_2.png' alt='Limit'  width='46' height='22'></a>";
            break;
        default:
			$code = "N/A";
	}
	$code .= "</a>";
	return $code;
}

function afficherLocate($sigfox){
    
	if ($sigfox->latitude && $sigfox->longitude){
		$code = "<a href='sigfoxLocate.php?id={$sigfox->id}' title='Access location for this message'><img src='../images/Locate.png' alt='Locate'  width='24' height='22'></a>";
	}else{
		$code = "N/A";
	}	
	return $code;
}

function afficherMessages(){
	
    global $bdd;
	global $idDevice;
	
	try{
	    $sql = sprintf("SELECT * FROM `data`.`sigfox` where `idDevice`=%s ORDER BY `sigfox`.`id` DESC LIMIT 100", 
			$bdd->quote($idDevice)
		);
									
		$stmt = $bdd->query($sql);
									
		while ($sigfox =  $stmt->fetchObject()){
			echo "<tr><td><input class='array_message' type='checkbox' name='array_message[$sigfox->id]' value='$sigfox->id' ></td>\n";
			echo "<td>{$sigfox->time}</td>\n";  
			echo "<td>{$sigfox->idDevice}</td>\n";
			echo "<td>{$sigfox->seqNumber}</td>\n";
			echo "<td>{$sigfox->data}</td>\n";
			echo "<td>" . afficherSignal($sigfox->lqi, $sigfox->operatorName) . "</td>\n";
			echo "<td>" . afficherRetourCallbacks($sigfox->callbacks) . "</td>\n";
			//echo "<td><a href='sigfoxLocate.php?id={$sigfox->id}' title='Access location for this message'><img src='../images/Locate.png' alt='Locate'  width='24' height='22'></a></td>\n";
			echo "<td>" . afficherLocate($sigfox) . "</td>\n";
			echo "</tr>\n";								
		}
	}
	catch (\PDOException $ex) 
	{
		echo($ex->getMessage());
		return;
	}
}

function afficherCallbacks(){

    global $bdd;
	global $idDevice;
	
    try{
	    $sql = sprintf("SELECT * FROM `callbacks` where `idDevice`=%s",
			$bdd->quote($idDevice)
		);
		
		$stmt = $bdd->query($sql);
		
		while ($callback = $stmt->fetchObject()){
			echo "<tr><td><input class='array_callbacks' type='checkbox' name='array_callbacks[$callback->id]' value='$callback->id' ></td>";
			echo "<td>{$callback->idDevice}</td>";
			echo "<td>{$callback->type}</td>";  
			echo "<td>{$callback->url}</td>";
			echo "<td>{$callback->write_api_key}</td>";
			echo "<td>{$callback->payload}</td>";			
			echo "</tr>";
			
		}
	}
	catch (\PDOException $ex) 
	{
		echo($ex->getMessage());
		return;
	}
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sigfox</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/ruche.css" />
	<link rel="stylesheet" href="../css/jquery-confirm.min.css" />
	<link rel="stylesheet" href="../css/datatables.min.css"/>
	<link rel="stylesheet" href="../css/dataTables.css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../scripts/popper.min.js"></script>
	<script src="../scripts/bootstrap.min.js"></script> 
	<script src="../scripts/jquery-confirm.min.js"></script>
	<script src="//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
	
	
	<script >
		$(document).ready(function(){
			
			$('[data-toggle="popover"]').popover();
			
			let optionsMessages = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [ 10, 50, 100],
                pageLength: 10,
                order: [[1, 'desc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}, {type:"text"}, {type:"text"}, {type:"text"}],
				"language": {
					"url": "<?= $lang['dataTables'] ?>"
				}
                
            };
			$('#tableauMessage').DataTable(optionsMessages);

			let optionsCallbacks = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [5, 10, 15, 20, 40],
                pageLength: 10,
                order: [[1, 'desc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}, {type:"text"}],
				"language": {
					"url": "<?= $lang['dataTables'] ?>"
				}
                
            };
			$('#tableauCallbacks').DataTable(optionsCallbacks);
			
			
			function cocherTout(etat,formulaire)
			{
			  var cases = document.getElementsByClassName(formulaire);   // on recupere tous les éléments ayant la classe formulaire
			   for(var i=0; i<cases.length; i++)     // on les parcourt
				 if(cases[i].type == 'checkbox')     // si on a une checkbox...
					 {cases[i].checked = etat;}
			}
			
			$("#all_message").click(function(){	
				cocherTout(this.checked, 'array_message');
			});
			
			$("#all_callbacks").click(function(){	
				cocherTout(this.checked, 'array_callbacks');
			});
			
			$( "#btn_supp_messages" ).click(function() {
				console.log("Bouton Supprimer messages cliqué");
				
				nbCaseCochees = $('.array_message:checked').length;
				console.log(nbCaseCochees);
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' objet(s) ?',
						buttons: {
							confirm: {
								text: 'Confirmation', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
								$( "#supprimer" ).submit(); // soumission du formulaire aynat id supprimer
								}
							},
					 		cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							}
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun message !"
					});
			
				}
			});
			
			$( "#btn_supp_callBack" ).click(function() {
				console.log("Bouton Supprimer callbacks cliqué");
				
				nbCaseCochees = $('.array_callbacks:checked').length;
				console.log(nbCaseCochees);
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' callback(s) ?',
						buttons: {
							confirm: {
								text: 'Confirmation', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
								$( "#callback" ).submit(); // soumission du formulaire id callback
								}
							},
					 		cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							}
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun callback !"
					});
			
				}
			});
			
			// Bouton add callback
			$( "#add_callback" ).click(function() {
				console.log("Bouton Ajouter callback_cliqué");
				window.location = 'callback?idDevice=<?= $idDevice ?>'					
			});
			
			// Bouton modifier callback
			$( "#mod_callback" ).click(function() {
				console.log("Bouton modifier callback cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_callbacks:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun callback !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs callbacks !"
					});
				}
				if(checkbox_val.length == 1){
					console.log("callback.php?id" + checkbox_val[0]);
					window.location = 'callback?id='+checkbox_val[0];
				}
			});
			
			
			$( "#btn_val" ).click(function() {
				console.log("Bouton affiché message décodé");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_message:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs objets !"
					});
				}
				if(checkbox_val.length == 1){
					let sigfoxMessage = "../api/viewMessageSigfox.php?id=" + checkbox_val[0] + "&type=json&results=1";
					console.log (sigfoxMessage);
					$.getJSON( sigfoxMessage ,  function( data ) {
						let contenu = '';
						if (typeof data !==  'undefined'){
							let d = data.time;
							let date = Date.UTC(d.substring(0, 4), d.substring(5, 7) - 1, d.substring(8, 10), d.substring(11, 13), d.substring(14, 16), d.substring(17, 19));
							let options = {timeZone: '<?php echo $_SESSION['time_zone']?>', year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', timeZoneName: 'short'};
							let _resultDate = new Intl.DateTimeFormat('fr-FR', options).format(date);
							contenu = _resultDate;
							contenu += "<table class='table display table-striped table-sm'><tr><th>Field</th><th>Value</th></tr>";
							contenu += '<tr><td>type</td><td>'   + data.type + '<td/></tr>';
							contenu += '<tr><td>field1</td><td>' + data.field1 + '<td/></tr>';
							contenu += '<tr><td>field2</td><td>' + data.field2 + '<td/></tr>';
							contenu += '<tr><td>field3</td><td>' + data.field3 + '<td/></tr>';
							contenu += '<tr><td>field4</td><td>' + data.field4 + '<td/></tr>';
							contenu += '<tr><td>field5</td><td>' + data.field5 + '<td/></tr>';
							contenu += '<tr><td>field6</td><td>' + data.field6 + '<td/></tr>';
							contenu += '</table>';
						}else{
							contenu = "not data to show";
						}
						$.alert({	
							theme: 'bootstrap',
							title: 'Message de ' + data.idDevice,
							content: contenu
						});
						});	

				}
			});
			
		});
		
	</script>
    
 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px; max-width: 90%;">
		<div class="popin">
			<nav class="nav nav-tabs">
				<a class="nav-item nav-link active" href="#p0" data-toggle="tab">Messages</a>
				<a class="nav-item nav-link" href="#p1" data-toggle="tab">CallBacks</a>
			</nav>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="p0">
					<div class="table-responsive">
						<form method="post" id="supprimer">
							<table id="tableauMessage" class="table display table-striped table-sm">
								<thead>
								  <tr>
									<th><input type='checkbox' name='all_message' value='all_message' id='all_message' ></th>
									<th>Time</th>
									<th>Device Id</th>
									<th>Seq number</th>
									<th>Data</th>
									<th>LQI</th>
									<th>Callbacks</th>
									<th>Location</th>
								  </tr>
								</thead>
								<tbody>
									<?php afficherMessages(); ?>
								</tbody>
							</table>
							<button id="btn_val" type="button" class="btn btn-secondary">Afficher les valeurs décodées</button>
							<input id="btn_supp_messages" name="supprimer_message" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">
							<a href="https://backend.sigfox.com/auth/login" class="btn btn-info" role="button" target="_blank" >Backend sigfox</a> 
						</form>
					</div>			
				</div>
					
					
				<div class="tab-pane fade" id="p1">
					<div class="table-responsive">
						<form method="post" id="callback">
							<table id="tableauCallbacks" class="table display table-striped table-sm">
								<thead>
								  <tr>
									<th><input type='checkbox' name='all_callbacks' value='all_callbacks' id='all_callbacks' ></th>
									<th>Device Id</th>
									<th>Type</th>
									<th>Url</th>
									<th>Write API Key</th>
									<th>Payload</th>
								  </tr>
								</thead>
								<tbody>
									<?php afficherCallbacks(); ?>
								</tbody>
							</table>
							<button id="mod_callback" type="button" class="btn btn-secondary"><?= $lang['edit_settings'] ?></button>
					        <button id="add_callback" type="button" class="btn btn-secondary"><?= $lang['add'] ?></button>
							<input id="btn_supp_callBack" name="supprimer_callback" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">
							<a href="https://backend.sigfox.com/auth/login" class="btn btn-info" role="button">Backend sigfox</a> 
						</form>
					</div>	
					
				</div>
			</div>	
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>

	
	
</body>
</html>
	