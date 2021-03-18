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

// Si le formulaire a été soumis
if(isset($_POST['btn_supprimer'])){
	// Si un élément a été sélectionné création de la liste des id à supprimer
	if (count($_POST['table_array']) > 0){
		$Clef=$_POST['table_array'];
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

function afficherSigfox(){
	
    global $bdd;
	
	try{
	    $sql = "SELECT * FROM `sigfox`";
									
		$stmt = $bdd->query($sql);
									
		while ($sigfox =  $stmt->fetchObject()){
				echo "<tr><td><input class='selection' type='checkbox' name='table_array[$sigfox->id]' value='$sigfox->id' ></td>";
			
				echo "<td>" . $sigfox->time . "</td>";  
				echo "<td>" . $sigfox->idDevice . "</td>";
				echo "<td>" . $sigfox->seqNumber . "</td>";
				echo "<td>" . $sigfox->data . "</td>";
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
    <title>Sigfox - Aggregator</title>
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
			
			let options = {
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
			$('#tableau').DataTable(options);		
			
			function cocherTout(etat)
			{
			  var cases = document.getElementsByTagName('input');   // on recupere tous les INPUT
			   for(var i=1; i<cases.length; i++)     // on les parcourt
				 if(cases[i].type == 'checkbox')     // si on a une checkbox...
					 {cases[i].checked = etat;}
			}
			
			
			$("#all").click(function(){	
				cocherTout(this.checked);
			});
			
			
			$( "#btn_supp" ).click(function() {
				console.log("Bouton Supprimer cliqué");
				
				nbCaseCochees = $('input:checked').length - $('#all:checked').length;
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
								$( "#supprimer" ).submit(); // soumission du formulaire
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
					content: "Vous n'avez sélectionné aucun objet !"
					});
			
				}
			});
			
			
			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				window.location = 'channel'
			
			
			});
			
			
			$( "#btn_val" ).click(function() {
				console.log("Bouton View last values cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.selection:checked').each(function(){
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
	<div class="container" style="padding-top: 65px;">
		<div class="row popin card">
			<div class="col-md-12 col-sm-12 col-xs-12">
			<div  class="card-header" style=""><h4>Sigfox Messages</h4></div>
				<div class="table-responsive">
					<form method="post" id="supprimer">
					<table id="tableau" class="table display table-striped table-sm">
						<thead>
						  <tr>
							<th><input type='checkbox' name='all' value='all' id='all' ></th>
							<th>Time</th>
							<th>Device Id</th>
							<th>Seq number</th>
							<th>Data</th>
						  </tr>
						</thead>
						<tbody>
							<?php afficherSigfox(); ?>
						</tbody>
					</table>
					

					<button id="btn_val" type="button" class="btn btn-secondary">Afficher les valeurs décodées</button>
					<input id="btn_supp" name="btn_supprimer" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">
					<a href="https://backend.sigfox.com/auth/login" class="btn btn-info" role="button">Backend sigfox</a> 
					</form>	
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>	
</body>
</html>
	