<?php 
/**
 * @fichier  timeControls.php							    		
 * @auteur   Léo Cognard (Touchard Washington le Mans)
 * @date     May 2021
 * @version  v1.0 - First release						
 * @details  Affiche la liste des tâches planifiées déjà crées.
 */
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../lang/lang.conf.php');
require_once('../api/CronManager.class.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\CronManager;

// connexion à la base
$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
// Création d'un cron_manager
$cron_manager = new CronManager();

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
			$cron_manager->remove_cronjob($selectValue);
		}
		$supp .= ")";
		
		
		$sql = "DELETE FROM `timeControls` WHERE `id` IN " . $supp;
		$bdd->exec($sql);
	}
}


/**
 * @details Requête vers la BDD pour récupérer les tâches planifiées
 * @param * (demande tous les élements du champ vue_timeControls)
 * @return tous les élements du champ vue_timeControls et les affiche dans un tableau 
 */
function afficherTimeControls(){
	
	global $bdd;
	
	try{
		$sql = "SELECT * FROM `vue_timeControls`";
		if ($_SESSION['droits'] > 1)
			$sql .= " where 1";	
		else	
			$sql .= " where login = '" . $_SESSION['login'] . "'";
			$sql .= " order by `name` ";
			$stmt = $bdd->query($sql);
									
			while ($timeControl =  $stmt->fetchObject()){
				echo "<tr>\n";
				echo "    <td><input class='selection' type='checkbox' name='table_array[{$timeControl->id}]' value='{$timeControl->id}' ></td>\n";
				echo "    <td>{$timeControl->login}</td>";
				echo "    <td>" . Str::reduire($timeControl->name) . "</td>\n";
				echo "    <td>{$timeControl->minute} {$timeControl->hour} {$timeControl->dayMonth} {$timeControl->month} {$timeControl->dayWeek}</td>";
				
				$sql = "SELECT * FROM `{$timeControl->actionable_type}` where id = {$timeControl->actionable_id}";
				$stmt2 = $bdd->query($sql);
				$action = $stmt2->fetchObject();
				echo "    <td><a href=\"script?id={$action->id}\">{$timeControl->actionable_type} / {$action->name}</a></td>\n";
				
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

    <title><?= $lang['timeControls'] ?> - Aggregator</title>
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
		columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}],
		"language": {
			"url": "<?= $lang['dataTables'] ?>"
			}
        };
			
		$('#tableau').DataTable(options);
		/**
                * 
                 * @param {type} etat
                 * @returns {undefined}  
                 * @details permet de cocher toutes les cases affichées du tableau 
                 *                */
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
					content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' timeControl(s) ?',
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
				content: "Vous n'avez sélectionné aucun timeControl !"
				});
			}
		});	
                /**
                 * @details Permet d'accéder a la page de modification de l'élement coché
                 */
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
					content: "Vous n'avez sélectionné aucun timeControl !"
				});
			}
			if(checkbox_val.length > 1){
				$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs timeControl !"
				});
			}
			if(checkbox_val.length == 1){
				console.log("react?id" + checkbox_val[0]);
					window.location = 'timeControl?id='+checkbox_val[0];
				}
			});

			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				window.location = 'timeControl'
			});
			
	});
	
	</script>

</head>

<body>
			
	<?php require_once '../menu.php'; ?>
	
	
	<div style="min-height : 500px">
       	<div class="container" style="padding-top: 65px;">
			<div class="row popin card">
				
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div  class="card-header" style=""><h4><?= $lang['timeControls'] ?></h4></div>
					<div class="table-responsive">
						<form method="post" id="supprimer">
						<table id="tableau"  class="display table table-striped">
							<thead>
							  <tr>
								<th><input type='checkbox' name='all' value='all' id='all' ></th>
								<th><?= $lang['user'] ?></th>
								<th><?= $lang['name'] ?></th>
								<th>Cron schedule expression</th>
								<th><?= $lang['action'] ?></th>
							  </tr>
							</thead>
							<tbody>
								<?php afficherTimeControls();	?>
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
		
	</div>
	
	
</body>	