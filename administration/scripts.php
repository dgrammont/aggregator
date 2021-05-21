<?php
/**
 * @fichier  administration/support/administration/index.php							    		
 * @auteur   Léo Cognard (Touchard Washington le Mans)
 * @date     Mai 2021
 * @version  v1.0 - First release						
 * @details  
 */
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

/* connexion à la base */
$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

/* Si le formulaire a été soumis */
if(isset($_POST['btn_supprimer'])){
	/* Si un élément a été sélectionné création de la liste des id à supprimer */
	if (count($_POST['table_array']) > 0){
		$Clef=$_POST['table_array'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `scripts` WHERE `id` IN " . $supp;
		$bdd->exec($sql);
	}
}



/**
 * 
 * @detail affiche le tableau des scripts 
 * 
 */
function afficherScripts(){
	
	global $bdd;
	
	try{
		$sql = "SELECT * FROM `vue_scripts`";
		if ($_SESSION['droits'] > 1)
			$sql .= " where 1";	
		else	
			$sql .= " where user_id = '" . $_SESSION['id'] . "'";
			$sql .= " order by `id` ";
			$stmt = $bdd->query($sql);
									
			while ($script =  $stmt->fetchObject()){
				echo "<tr>\n";
				echo "    <td><input class='selection' type='checkbox' name='table_array[{$script->id}]' value='{$script->id}' ></td>\n";
				echo "    <td>{$script->login}</td>\n";
				echo "    <td><a href='script?id={$script->id}' title='Access code for this script'>" . Str::reduire($script->name) . "</a></td>\n";
				echo "    <td>{$script->language}</td>\n";
				echo "    <td>{$script->last_run_at}</td>\n";
				echo "    <td>{$script->return_value}</td>\n";
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
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Scripts - Aggregator</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jquery-confirm.min.css" />
    <link rel="stylesheet" href="../css/ruche.css" />
    <link rel="stylesheet" href="../css/datatables.min.css"/>
	<link rel="stylesheet" href="../css/dataTables.css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../scripts/popper.min.js"></script>
	<script src="../scripts/bootstrap.min.js"></script>
	<script src="//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
	<script src="../scripts/jquery-confirm.min.js"></script>	
	
	
	
	<script>
	$(document).ready(function(){

		let options = {

		dom: 'ptlf',
		pagingType: "simple_numbers",
        lengthMenu: [5, 10, 15, 20, 40],
        pageLength: 10,
        order: [[1, 'desc']],
		columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"}, {type:"text"} , {type:"text"}],
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
					content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' script(s) ?',
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
				content: "<?=$lang['alertSetting'] ?>"
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
					content: "<?=$lang['alertSetting'] ?>"
				});
			}
			if(checkbox_val.length > 1){
				$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "<?=$lang['alertSettings'] ?>"
				});
			}
			if(checkbox_val.length == 1){
				console.log("react?id" + checkbox_val[0]);
					window.location = 'script?id='+checkbox_val[0];
				}
			});

			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				window.location = 'script'
			});
			
	});
	
	</script>

</head>

<body>
			
	<?php require_once '../menu.php'; ?>
	
	<div class="container" style="padding-top: 65px; max-width: 90%;" >
		<div style="min-height : 500px">
        	
			<div class="row popin card">
				
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div  class="card-header" style=""><h4>Scripts</h4></div>
					<div class="table-responsive">
						<form method="post" id="supprimer">
						<table id="tableau"  class="display table table-striped">
							<thead>
							  <tr>
								<th><input type='checkbox' name='all' value='all' id='all' ></th>
								<th><?= $lang['user'] ?></th>
								<th><?= $lang['name'] ?></th>								
								<th>Language</th>
								<th>last run at</th>
								<th>Output</th>
							  </tr>
							</thead>
							<tbody>
								<?php afficherScripts();	?>
							</tbody>
						</table>
						<input  id="btn_supp" name="btn_supprimer" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly size="9">
						<button id="btn_mod"  type="button" class="btn btn-secondary"><?= $lang['edit_settings'] ?></button>
						<button id="btn_add"  type="button" class="btn btn-secondary"><?= $lang['add'] ?></button>
						</form>	
					</div>
				</div>					
			</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	