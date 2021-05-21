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

    <title>Support - Script</title>
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
			<h4>Vue d'ensemble des scripts </h4>
			<p>Au moment où l'objet connectée enregistre des mesures dans un canal, il s’agit  de données brutes, qui nécessitent tout un travail de raffinage 
			   avant de pouvoir fournir une information valorisable. Ce cycle se découpe  en six étapes : 
			   <ul>
			   <li>1 la collecte, </li>
			   <li>2 le nettoyage, (retrait des données erronées)</li>
			   <li>3 le stockage, </li>
			   <li>4 la transformation, (changement d'unités, normalisation, moyenne, mini, maxi ...) </li>
			   <li>5 l’analyse (histogramme corrélation)</li>
			   <li>6 la diffusion (grapiques)</li>.
			   </ul>
			</p>
			<p>Les scripts peuvent être mis en oeuvre pour réaliser l'étape 2 (nettoyage des données) ou l'étape 4 pour leur transformation</p>
			
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	