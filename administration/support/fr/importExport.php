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

    <title>Support - Import Export</title>
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
			<h4>Vue d'ensemble de l'import export CSV </h4><hr>
			<p>Le format de fichier CSV (valeurs séparées par des virgules) est le format d'importation et d'exportation le plus courant pour les feuilles de calcul 
			et les bases de données. Bien que de nombreux fichiers CSV soient simples à analyser, le format doit être strictement défini par une importation réussie des données.
			</p> 
			<h4>Le Format</h4>
			<p>La première ligne du fichier CSV doit contenir le nom des colonnes séparé par une virgule.
				La colonne <b>created_at</b> est obligatoire suivie d'au moins une autre colonne.
				Les autres colonnes doivent être nommées :
				<ul>
				<li>field1</li>
				<li>field2</li>
				<li>field3</li>
				<li>field4</li>
				<li>field5</li>
				<li>field6</li>
				<li>field7</li>
				<li>field8</li>
				<li>latitude</li>
				<li>longitude</li>
				<li>elevation</li>
				<li>status</li>
				</ul>
			Tous les autres noms de colonne seront ignorés lors de l'importation et ne provoqueront pas d'erreur.</p>
			<p>Les lignes suivantes contiennent les données. Une donnée dans une colonne peut être vide.</p>
			<p>La time zone doit être UTC</p>
			<h4>Modèle de fichier  d'importation</h4>
			<div class="jumbotron">
			<pre>
created_at,field1,field2,field3,field4,field5,field6,field7,field8,latitude,longitude,elevation,status
2021-04-18 16:04:42 UTC,11.5837,23,52.30,,,,,,,,,
			</pre>
			</div>
			
			</p>
			<p>
			</p>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	