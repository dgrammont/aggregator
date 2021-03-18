<!----------------------------------------------------------------------------------
    @fichier  administration/support/administration/index.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/channels.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Canaux</title>
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
			<h4>Vue d'ensemble des canaux (ou chaînes)</h4>
			<p>Aggregator est une plate-forme IoT qui utilise des canaux pour stocker les données reçues des objets connectés.
			<img src="channels.gif">
			</p>
			<p>Les canaux sont des supports pour les flux de données. les objets connectées émettent des données sur un ou plusieurs canaux à destination de l'aggrégator.
			   Un canal est l'endroit où sont envoyées les données à stocker. Chaque canal comprend 8 champs pour des données numériques, 3 champs d'emplacement et 1 champ d'état.</p> 
			<p>Une fois que vous avez défini un canal, vous pouvez publier des données sur ce canal, vérifier certaines conditions sur ces données. 
			   Vous pouvez aussi demander à votre application de récupérer les données enregistrées pour les afficher sous forme de graphiques.</p>
			<p>Vous pouvez rendre vos canaux publiques pour partager des données.</p> 
			<h4>Les Canaux</h4>
				<p>Les canaux que vous avez créés sont affichés sous forme de tableau. chaque ligne donne l'identifiant, le nom, l'étiquette, la clé api, le nombre de valeurs, 
				la date et l'heure de la dernière valeur enregistrée.</p>
				<p>Après avoir coché un canal, vous pouvez pour ce canal:
				<ul><li>Modifier le paramétrage</li>
					<li>Générer une nouvelle clé API</li>
					<li>Afficher les dernières valeurs enregistrées</li>
					<li>Télécharger les valeurs enregistrées au format CSV</li>
					<li>Effacer le flux, c'est à dire l'ensemble des données enregistrées</li>
					<li>Le supprimer définitivement</li>
				</ul>	
				
			</p>   
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	