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

		try{
			$sql = "DELETE FROM `things` WHERE `id` IN " . $supp;
			$bdd->exec($sql);
		}	
		catch (\PDOException $ex){
		    echo "erreur BDD";
		    die('Erreur : ' . $ex->getMessage());
	    }
	}
}

function afficherThings($bdd){
	try{
		$sql = "SELECT * FROM `login_things`";
		if ($_SESSION['droits'] > 1)
			$sql .= " where 1";	
		else	
			$sql .= " where login = '" . $_SESSION['login'] . "'";
			$sql .= " order by `name` ";
			$stmt = $bdd->query($sql);
									
			while ($thing =  $stmt->fetchObject()){
				echo "<tr>\n";
				echo "    <td><input class='selection' type='checkbox' name='table_array[{$thing->id}]' value='{$thing->id}' ></td>\n";
				echo "    <td>" . Str::reduire($thing->name) . "</td>\n";
				echo "    <td><a href='./channels?id={$thing->id}' title='Access channels for this thing'>{$thing->tag}</a></td>\n";
				echo "    <td>{$thing->status}</td>\n";
				echo "    <td>{$thing->login}</td>\n";
				echo "    <td>{$thing->local_ip_address}</td>\n";
				echo "    <td><a href='./comSigfox?id={$thing->idDevice}' title='Access Sigfox messages for this thing'>{$thing->idDevice}</a></td>\n";
				echo "</tr>\n";
			}	
	}
	catch (\PDOException $ex){
		echo "erreur BDD";
		die('Erreur : ' . $ex->getMessage());
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $lang['things'] ?>  - Aggregator</title>
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
	
	<script>
		$(document).ready(function(){

		    let options = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [5, 10, 15, 20, 40],
                pageLength: 10,
                order: [[1, 'asc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}, {type:"text"}, {type:"text"}],
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
			
			$( "#btn_mod" ).click(function() {
				console.log("Bouton modifier cliqué");
				
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
					console.log("thing.php?id" + checkbox_val[0]);
					window.location = 'thing?id='+checkbox_val[0];
				}
			});
			
			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				window.location = 'thing'
			});
			
		});	
		
	</script>
    
 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px;">
		<div class="row popin card">
			
			<div class="col-md-12 col-sm-12 col-xs-12">
			<div  class="card-header" style=""><h4><?= $lang['things'] ?></h4></div>
				<div class="table-responsive">
					<form method="post" id="supprimer">
					<table id="tableau"  class="display table table-striped">
						<thead>
						  <tr>
							<th><input type='checkbox' name='all' value='all' id='all' ></th>
							<th><?= $lang['name'] ?></th>
							<th><?= $lang['channels'] ?></th>
							<th><?= $lang['access'] ?></th>
							<th><?= $lang['author'] ?></th>
							<th><?= $lang['Ip_address'] ?></th>
							<th>Sigfox id</th>
						  </tr>
						</thead>
						<tbody>
							<?php afficherThings($bdd);	?>
						</tbody>
					</table>
					<input id="btn_supp" name="btn_supprimer" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">
					<button id="btn_mod" type="button" class="btn btn-secondary"><?= $lang['edit_settings'] ?></button>
					<button id="btn_add" type="button" class="btn btn-secondary"><?= $lang['add'] ?></button>
					</form>	
				</div>
			</div>	
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>
</html>
	