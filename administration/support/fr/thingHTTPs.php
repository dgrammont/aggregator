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

    <title>Support - ThingHTTPs</title>
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
			<h4> Vue d'ensemble </h4>
			<p>Simplifiez la communication avec les services WEB et les API.</p>
			<p>Vous spécifiez les actions dans ThingHTTP, que vous déclenchez à l'aide d'autres applications comme React ou  TimeControle</p>
			
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	