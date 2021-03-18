<?php session_start(); ?>


<!DOCTYPE html>
<html lang="fr">
<!----------------------------------------------------------------------------------
    @fichier  administration/support/fr/comSigfox.php						    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Juin 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/comSigfox.php
------------------------------------------------------------------------------------>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Sigfox</title>
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
					<h4>Vue d'ensemble - Communication via Sigfox</h4>
					<p><img src="Structure_réseau.png" alt="structure du réseau LPWAN" width="100%" >
					<p>L'objet connecté  lit ses capteurs et envoie les valeurs  (dans un  message ) aux stations de base Sigfox qui les retransmettent au Cloud Sigfox. 
					 Chaque message est émis 3 fois sur 3 fréquences différentes et capté par plusieurs antennes. 
					 À ce stade, un seul message est conservé et	archivé dans le cloud Sigfox, il est donc nécessaire de transférer le  message vers la plate-forme  Cloud Computing en utilisant un Callback
					 (c’est une fonction de rappel qui décode et transfert le message reçu). </p></br>
					
					<h4>Les rappels Sigfox </h4>
					<p>Les rappels Sigfox utilisent des requêtes HTTP pour transmettre des données  vers n'importe quel service cloud. 
					   Cette aide expose comment configurer les rappels Sigfox pour transmettre les données décodées du cloud sigfox vers l’agrégateur de la section snir.
					   
					</p>
					<p><a href="Message_Sigfox.pdf"> Aide pour la création des rappels </a></p>
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	