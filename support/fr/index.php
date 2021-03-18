<!----------------------------------------------------------------------------------
    @fichier  support/fr/index.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Mai 2020
    @version  v1.0 - First release						
    @details  support pour la page index.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Index</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../scripts/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../css/ruche.css" />

</head>

<body>
	
	<div class="row" style="background-color:white; padding-top: 65px; ">
		<div class="col-lg-12">

		</div>
		
	</div>
	
	<?php require_once '../../menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">	
		<h1>Plateforme de supervision de données </h1>
		<p>La plateforme aggregator permet de collecter et de traiter les différentes mesures remontées par chaque transmetteur. 
		Les données collectées sont structurées dans des canaux. Chaque canal peut contenir jusqu'à 8 grandeurs physiques.
		C’est une plateforme web responsive design qui s’adapte automatiquement au terminal utilisé pour la consultation (PC, Tablette, Smartphone). 
		Elle permet de récupérer et traiter les informations des différents capteurs connectés, quel que soit le réseau de transmission utilisé.</p>
		
		<p>La plateforme permet aujourd’hui la collecte de données en provenance de divers sources et typologies de réseaux : 
		<ul>
			<li>Réseaux Internet via API Rest </li>
			<li>Réseaux mobiles via GPRS-3G-4G, </li>
			<li>Ecosystème M2M via des messages SMS </li>
			<li>Réseaux IoT LPWAN tels que SigFox</li>
	    </ul>
		
		
		<h1>Des alertes flexibles</h1>
		<p>Elle permet également de créer des scénarios d’alerte avec l’envoi de SMS ou d’email.</p>
		<p>Pour chaque champs d'un canal, elle permet de définir des alertes simplement suivant les valeurs mesurées et indiquez les correspondants à prévenir par SMS ou par mail.</p>
		</div>
		<?php require_once 'piedDePage.php'; ?>
	</div>	
</body>	