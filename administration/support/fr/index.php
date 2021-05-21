<!----------------------------------------------------------------------------------
    @fichier  administration/support/administration/index.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/index.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Index</title>
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
			<h4> Authentification </h4>
			<p>Pour s'authentifier vous devez saisir votre login et votre mot de passe.<br></p>
			<h4>Votre navigateur doit supporter les cookies</h4>
			<p>Ce site utilise trois cookies.
			Un cookie (essentiel) est utilisé pour la session de travail. Il est habituellement appelé PHPSESSID. Vous devez l'autoriser dans votre navigateur afin de pouvoir naviguer sur le site sans avoir à vous reconnecter à tout moment. 
			Ce cookie est supprimé du navigateur et du serveur lorsque vous vous déconnectez ou si vous quittez le navigateur.<br></p>

			<p>les autres cookies ne sont pas essentiels, mais rendent la connexion à l'Aggregator plus facile en mémorisant votre connexion dans le navigateur ainsi que la langue uilisée. 
			Vous n'aurez donc pas à vous reconnecter lors de la prochaine visite. Il porte habituellement le nom auth. Il est tout à fait sûr de refuser ce cookie. 
			Vous devrez simplement ressaisir votre nom d'utilisateur et votre mot de passe lors de chaque connexion.
			</p>
			<h4>Persistance de la connexion</h4>
			<p>Une session de navigateur persistante vous permet  de rester connecté après la fermeture et la réouverture de la fenêtre du navigateur.
			Si vous cochez cette option, le serveur fourni un cookie valable uniquement pour l'ordinateur utilisé et pour une durée de 60 jours.
			</p>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	