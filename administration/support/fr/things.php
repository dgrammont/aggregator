<!----------------------------------------------------------------------------------
    @fichier  administration/support/administration/index.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/things.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Objets</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../../scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../../css/ruche.css" />
</head>

<body>
	
	<?php require_once '../../../menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">	
		<div class="row" style="background-color:white; padding-top: 65px; ">
			<div class="col-lg-12">
			<h4>Vue d'ensemble des objets </h4>
			<p>Internet se transforme progressivement en un réseau étendu, appelé « Internet des objets », reliant tous les objets devenus connectables.
			Agregator est un des maillons de l’internet des objets. C’est une plate-forme de collecte et de stockage de données.
			<img src="channels.gif"></p>
			<p>les objets connectées émettent des données sur un ou plusieurs canaux à destination de l'aggrégator.</p>
			<h4>Les Objets</h4>
				<p>Les objets que vous avez créés sont affichés sous forme de tableau. chaque ligne donne le nom, l'étiquette, le propriétaire, le status privé ou public, l'adresse IP.</p>
				<p>Après avoir coché un objet, vous pouvez pour cet objet:
				<ul><li>Modifier le paramétrage</li>
					<li>Le supprimer définitivement</li>
					<li>Créer un nouvel objet</li>
				</ul>	
				
			    </p> 
			
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	