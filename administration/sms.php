<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

//  Ceci est une fct du modele SMS
$bdd = Api::connexionBD(BASESMS,$_SESSION['time_zone']);
$title = "SMS - Aggregator";

// Si le formulaire supprimer sent à été soumis
if(isset($_POST['btn_supp_sent'])){
	
	// Si un élément a été sélectionné
	if (count($_POST['array_sent']) > 0){
		$Clef=$_POST['array_sent'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `sentitems` WHERE `ID` IN " . $supp;
		$bdd->exec($sql);
	}
}

// Si le formulaire supprimer receive à été soumis
if(isset($_POST['btn_supp_receive'])){
	
	// Si un élément a été sélectionné
	if (count($_POST['array_receive']) > 0){
		$Clef=$_POST['array_receive'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `inbox` WHERE `ID` IN " . $supp;
		
		$bdd->exec($sql);
	}
}

// Si le formulaire clear receive à été soumis
if(isset($_POST['btn_clear_receive'])){
	
	$sql = "TRUNCATE TABLE `inbox`";	
	$bdd->exec($sql);
	
}

// Fonction pour afficher le tableau des SMS envoyés
function afficherSentitems(){
		
	global $bdd;
	if ($_SESSION['droits'] > 1){		
		$sql = "SELECT `SendingDateTime`,`DestinationNumber`,`TextDecoded`,`CreatorId`,`ID` FROM `sentitems` order by `SendingDateTime` desc ";
	}else{
		$sql = "SELECT `SendingDateTime`,`DestinationNumber`,`TextDecoded`,`CreatorId`,`ID` FROM `sentitems` where `CreatorId` = '".$_SESSION['login']."' order by `SendingDateTime` desc ";
	}
	
	$stmt = $bdd->query($sql);	
																	
	while ($message =  $stmt->fetchObject()){
		echo "<tr>\n    <td><input type='checkbox' class='array_sent' name='array_sent[$message->ID]' value='$message->ID' ></td>\n";
		echo "    <td>" . $message->SendingDateTime . "</td>\n";
		echo "    <td>" . $message->DestinationNumber . "</td>\n";
		echo "    <td>" . Str::reduire($message->TextDecoded) . "</td>\n";
		echo "    <td>" . $message->CreatorId . "</td>\n</tr>\n";
	}
}

// Fonction pour afficher le tableau des SMS recus
function afficherInbox(){

	global $bdd;
	$sql = "SELECT `UpdatedInDB`,`ReceivingDateTime`,`SenderNumber`,`TextDecoded`,`ID` FROM `inbox` order by `ReceivingDateTime` desc ";
											
	$stmt = $bdd->query($sql);
											
	while ($message =  $stmt->fetchObject()){
		echo "<tr>\n    <td><input type='checkbox' class='array_receive' name='array_receive[$message->ID]' value='$message->ID' ></td>\n";
		echo "    <td>" . $message->UpdatedInDB . "</td>\n";
		echo "    <td>" . $message->ReceivingDateTime . "</td>\n";
		echo "    <td>" . $message->SenderNumber . "</td>\n";
		echo "    <td>" . Str::reduire($message->TextDecoded) . "</td>\n";
		echo "</tr>\n";
	}	
}

// Fonction pour afficher les propriétés du modem GSM et du réseau GSM
// 
function afficherModem(){
	
	// Code des opérateurs Français
	$operateur = array(
    "20801" => "Orange France, GSM et UMTS (principal)",
    "20802" => "Orange France, itinérance zones blanches",
	"20803" => "MobiquiThings (full MVNO)",
	"20804" => "Sisteer (MVNE, full MVNO)",
	"20805" => "Globalstar Europe (Satellite)",
	"20806" => "Globalstar Europe",
	"20807" => "Globalstar Europe",
	"20808" => "Completel (Full MVNO)",
	"20809" => "Neuf Cégétel / SFR",
	"20810" => "SFR, GSM/UMTS (principal)",
	"20811" => "SFR, UMTS (Femtocell)",
	"20812" => "Truphone France",
	"20813" => "SFR, GSM/UMTS (zones blanches)",
	"20814" => "RFF GSM-R (réseau privé)",
	"20815" => "Free Mobile UMTS (principal)",
	"20816" => "Free Mobile (Femtocells)",
	"20817" => "Legos",
	"20818" => "Usage ARCEP",
	"20820" => "Bouygues Télécom, GSM/UMTS (principal)",
	"20821" => "Bouygues Télécom (expérimental)",
	"20822" => "Transatel (MVNE)",
	"20823" => "Omea Telecom et Virgin Mobile (Full MVNO)",
	"20824" => "Omea Telecom et Virgin Mobile (Full MVNO)",
	"20825" => "Lycamobile et GT-Mobile (Full MVNO)",
	"20826" => "NRJ Mobile (MVNO et Full MVNO)",
	"20827" => "Coriolis telecom SAS",
	"20828" => "Astrium SAS (satellite)",
	"20829" => "International mobile Communication (Full MVNO)",
	"20830" => "Symacom (Full MVNO)",
	"20831" => "Mundio Mobile (MVNE)",
	"20835" => "Free mobile",
	"20836" => "Free mobile",
	"20888" => "Bouygues Télécom, GSM/UMTS (zones blanches)",
	"20889" => "Omea Telecom (Test)",
	"20890" => "Association Images et réseaux (Test)",
	"20891" => "Orange France (test)",
	"20892" => "Association Plate-forme Telecom (Test)"
	);
	
	$MCC = array(
	"204" => "Pays-Bas",
	"206" => "Belgique",
	"208" => "France",
	"214" => "Espagne",
	"222" => "Italie",
	"228" => "Suisse",
	"232" => "Autriche",
	"262" => "Allemagne",
	"288" => "Danemark",
	"290" => "Danemark"	
	);
	
	global $bdd;
	$sql = "SELECT * FROM `phones` WHERE 1";
	$stmt = $bdd->query($sql);
	$modem = $stmt->fetchObject();
	if ($modem){
		echo "<h5>Modem GSM</h5>";
		echo "Date de démarrage : <span style='font-weight: bold;' >" . $modem->InsertIntoDB . "</span><br />";
		echo "IMEI : <span style='font-weight: bold;' >" . $modem->IMEI . "</span><br />";
		echo "MCC (Mobile Country Code) : " . substr ($modem->IMSI, 0, 3) . " - <span style='font-weight: bold;' >" . $MCC[substr ($modem->IMSI, 0, 3)] . "</span><br />";
		echo "MNC (Mobile Network Code) : " . substr ($modem->IMSI, 3, 2) . " - <span style='font-weight: bold;' >" . $operateur[substr ($modem->IMSI, 0, 5)] . "</span><br />";
		echo "MSIN (Mobile Subscriber Identification Number) : <span style='font-weight: bold;' >" . substr ($modem->IMSI, 5) . "</span><br />";
		echo "<br />";
		echo "<h5>Force du signal : ";
		// Dans les réseaux GSM, l'ASU correspond au RSSI (received signal strength indicator).
		$ASU = $modem->Signal/3;
		$rssi = 2*$ASU -113; 
		if ( $modem->Signal > 0 && $modem->Signal <= 18) 
		   echo "<span  style='font-size: x-large; font-weight: bold; color: red;'> ASU {$ASU} : rssi {$rssi} dBm : Poor</span>";
		if ( $modem->Signal > 18 && $modem->Signal <= 42) 
		   echo "<span  style='font-size: x-large; font-weight: bold; color: orange;'> ASU {$ASU} : rssi {$rssi} dBm : Fair</span>";		
		if ( $modem->Signal > 42 && $modem->Signal <= 60) 
		   echo "<span  style='font-size: x-large; font-weight: bold; color: yellow;'> ASU {$ASU} : rssi {$rssi} dBm : Good</span>";
		if ( $modem->Signal > 60) 
		   echo "<span  style='font-size: x-large; font-weight: bold; color: green;'> asu {$ASU} : rssi {$rssi} dBm : Excellent</span>";  
	    echo "</h5><br />";
	} else {
		echo "<h5>Modem GSM absent !!</h5>";
	}	
}

?>


<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?></title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/ruche.css" />
	<link rel="stylesheet" href="../css/jquery-confirm.min.css" />
	<link rel="stylesheet" href="../css/datatables.min.css"/>
	<link rel="stylesheet" href="../css/dataTables.css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../scripts/bootstrap.min.js"></script> 
	<script src="../scripts/jquery-confirm.min.js"></script>
	<script src="//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
	
	
	<script >
		$(document).ready(function(){
			
		    let options0 = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [5, 10, 15, 20, 40],
                pageLength: 10,
                order: [[1, 'desc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}],
				"language": {
					"url": "<?= $lang['dataTables'] ?>"
				}
                
            };
			$('#tableau0').DataTable(options0);
			
			let options1 = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [5, 25, 50, 100],
                pageLength: 10,
                order: [[1, 'desc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"}, {type:"text"} , {type:"text"}],
				"language": {
					"url": "<?= $lang['dataTables'] ?>"
				}
                
            };
			$('#tableau1').DataTable(options1);
			
			function cocherTout(etat,formulaire)
			{
			  var cases = document.getElementsByClassName(formulaire);   // on recupere tous les éléments ayant la classe formulaire
			   for(var i=0; i<cases.length; i++)     // on les parcourt
				 if(cases[i].type == 'checkbox')     // si on a une checkbox...
					 {cases[i].checked = etat;}
			}
			
			
			$("#all_sent").click(function(){	
				cocherTout(this.checked, 'array_sent');
			});
			
			$("#all_receive").click(function(){	
				cocherTout(this.checked, 'array_receive');
			});
			
			
			$( "#btn_supp_sent" ).click(function() {
				
				nbCaseCochees = $('.array_sent:checked').length;
				if(nbCaseCochees == 1) 
					message = "1 message envoyé sera supprimé.";
				else
					message = nbCaseCochees + " messages envoyés seront supprimés.";
				
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirmation !',
						content: message,
						buttons: {
							cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							},
							confirm: {
								text: 'Confirmer', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
								$( "#supp_sent" ).submit(); // soumission du formulaire
								
								}
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
			
			$( "#btn_lire_sent" ).click(function() {
				console.log("Bouton lire_sent cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox class array_sent checkées"
				$('.array_sent:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}else{
				
					var url = "../api/lireSMS.php?key=azerty&id=" + checkbox_val[0] + "&folder=sent"
					$.get(url, function(data, status){
						if (status == "success")
							console.log(data);
							$.dialog({
								title: data.number,
								content: data.text + "<br/><br/>" + data.date,
							});
					});
					
				}
			});
			
			// Action sur les boutons ecrire
			$( ".btn_ecrire" ).click(function() {
				console.log("Bouton ecrire cliqué");
				$.confirm({
					title: 'Write a SMS',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label class="font-weight-bold">Number : </label><br />' +
					'<input type="text" id="number" name="number" size="12" placeholder="Number"  /><br />' +
					'</div>' +
					'<div class="form-group">' +
					'<label class="font-weight-bold">Message : </label>' +
					'<textarea  rows="5" id="message" name="message" maxlength="160" class="form-control" ></textarea>' +
					'</div>' +
					'<input type="hidden" id="key" name="key"  value="' + <?php echo "'".$_SESSION['User_API_Key']. "'"; ?> + '"/>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Sending',
							btnClass: 'btn-blue',
							action: function () {
								var message = this.$content.find('#message').val();
								var number = this.$content.find('#number').val();
								var form_data = this.$content.find('.formName').serialize();
								
								if(!message || isNaN(number)){
									$.alert('provide a valid number and message');
									return false;
								}
								console.log(' form_data : ' + form_data);
								
								$.getJSON( '../api/sendSMS.php' , form_data, function( response,status, error ) {
									console.log("status : " + status);
									console.log("reponse : " +response);
									console.log("error : " +error);
									if (response.status == "202 Accepted"){
										console.log("message Accepted");
										$.dialog({
											title: "Info",
											content: "Message Accepted"
										});
										setTimeout( function(){window.location = 'sms'}, 10000);
									}	
									else{
										$.dialog({
											title: "Erreur",
											content: response.message + " <em>" + response.detail + "</em>"
										});
									}
									
								}).fail(function(response,status, error) {
									console.log("status : " + status);
									console.log("reponse : " + response.detail);
									console.log("error : " + error);
									$.dialog({
										title: "Erreur",
										content: error
									});
								});	
							}
						},
						cancel: function () {
							//close
						},
					},
					onContentReady: function () {
						// bind to events
						var jc = this;
						this.$content.find('form').on('submit', function (e) {
							// if the user submits the form by pressing enter in the field.
							e.preventDefault();
							jc.$$formSubmit.trigger('click'); // reference the button and click it
						});
					}
				});
			});
			
			
			// Action sur le bouton supprimer SMS receive
			$( "#btn_supp_receive" ).click(function() {
				console.log("Bouton Supp_receive cliqué");
				
				nbCaseCochees = $('.array_receive:checked').length;
				
				if(nbCaseCochees == 1) 
					message = "1 message reçu sera supprimé.";
				else
					message = nbCaseCochees + " messages reçus seront supprimés.";
				
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirmation!',
						content: message,
						buttons: {
							cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							},
							confirm: {
								text: 'Confirmer', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
									$( "#supp_receive" ).submit(); // soumission du formulaire
								}
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
			
			// Action sur le bouton lire SMS reçu
			$( "#btn_lire_receive" ).click(function() {
				console.log("Bouton lire_receive cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_receive:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun SMS reçu !"
					});
				}else{
				
					var url = "../api/lireSMS.php?key=azerty&id=" + checkbox_val[0] + "&folder=inbox"
					$.get(url, function(data, status){
						if (status == "success")
							console.log(data);
							$.dialog({
								title: data.number,
								content: data.text + "<br/><br/>" + data.date,
							});
					});
				}		
				
			});
			
			// Action sur le bouton supprimer SMS clear receive
			$( "#btn_clear_receive" ).click(function() {
				console.log("Bouton clear_receive cliqué");
				
			});
		});	
	
		
		
	</script>
    
 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px; max-width: 90%;">
		<div class="popin">
			<nav class="nav nav-tabs">
				<a class="nav-item nav-link active" href="#p0" data-toggle="tab">SMS <?= $lang['sent'] ?></a>
				<a class="nav-item nav-link" href="#p1" data-toggle="tab">SMS <?= $lang['received'] ?></a>
				<a class="nav-item nav-link" href="#p2" data-toggle="tab">GSM Modem</a>
			</nav>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="p0">
					<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">	
						<div class="table-responsive">
							<form method="post" id="supp_sent">
							<table id="tableau0" class="display table table-striped" >
								<thead>
								  <tr>
									<th><input type='checkbox' name='all_sent' value='all_sent' id='all_sent' ></th>
									<th><?= $lang['date_of_issue'] ?></th>
									<th><?= $lang['to'] ?></th>
									<th>Message</th>
									<th><?= $lang['author'] ?></th>
									
								  </tr>
								</thead>
								<tbody>									
									<?php afficherSentitems(); ?>
								</tbody>
							</table>
							
							<button id="btn_lire_sent" type="button" class="btn btn-info"><?= $lang['read'] ?></button>
							<button  type="button" class="btn btn-info btn_ecrire"><?= $lang['write'] ?></button>
							<input id="btn_supp_sent" name="btn_supp_sent" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">
							
							</form>	
						</div>			
					</div>
					</div>
				</div>
				<div class="tab-pane fade" id="p1">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="table-responsive">
							<form method="post" id="supp_receive">
								<table id="tableau1" class="display table table-striped">
									<thead>
									  <tr>
										<th><input type='checkbox' name='all_receive' value='all_receive' id='all_receive' ></th>
										<th>Date update BD</th>
										<th><?= $lang['date_of_receipt'] ?></th>
										<th><?= $lang['from']?></th>
										<th>Message</th>														
									  </tr>
									</thead>
									<tbody>										
										<?php  afficherInbox() ?>
									</tbody>
								</table>
								
								<button id="btn_lire_receive" type="button" class="btn btn-info"><?= $lang['read'] ?></button>
								<button  type="button" class="btn btn-info btn_ecrire"><?= $lang['write'] ?></button>
								<input id="btn_supp_receive" name="btn_supp_receive" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">	
							</form>
							<form method="post" id="clear_receive">	
								<input type="submit" id="btn_clear_receive" name="btn_clear_receive" value="<?= $lang['clear'] ?>" class="btn btn-danger" readonly size="9">
							</form>							
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="p2">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<br /><br />
							<?php afficherModem() ?>
						</div>
					</div>	
				</div>
			</div>	
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>

	
	
</body>
</html>
	