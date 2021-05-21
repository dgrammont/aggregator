<?php session_start();
/**
 *  @fichier  support/timeControls.php							    		
    @auteur   Léo Cognard (Touchard Washington le Mans)
    @date     Mai 2021
    @version  v1.0 - First release						
    @details  support pour la page timeControls.php
 */
?>
    

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Time Control</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../../scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../../css/ruche.css" />	
	
</head>

<body>
	
	<?php require_once '../../../menu.php'; ?>
	
	<div class="container" style="padding-top: 75px;" >
		<div style="min-height : 500px">
			<div class="row" style="background-color:white; padding-top: 10px; ">
				<div class="col-lg-12">
					<h2>Tâches planifiées</h2>				
					<p>L'application TimeControl fonctionne avec d'autres applications de l'agrégateur: Script, ThingHTTP, 
					pour effectuer une action à une heure précise ou selon un calendrier régulier.<br />
					Vous pouvez utiliser TimeControl avec:
							<ul>
								<li><b>ThingHTTP</b> pour communiquer avec des objets connectées, des sites Web ou des services Web.</li>
								<li><b>Script</b> pour agir sur les données stockées dans un canal afin d'effectuer un nettoyage, une transformation.</li>
							</ul>

					Par exemple, vous pouvez exécuter un script chaque jour à minuit afin de calculer la moyenne journalière d'une grandeur</p>

	
					
					
					
				</div>
			</div>
		</div>			
	
	<?php require_once '../piedDePage.php'; ?>
	</div>
</body>	