<!----------------------------------------------------------------------------------
    @fichier  support/fr/accueil.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page accueil.php
------------------------------------------------------------------------------------>
<?php session_start(); 

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Accueil</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../css/ruche.css" />

	
</head>

<body>
	
	<div class="row" style="background-color:white; padding-top: 35px; ">
		<div class="col-lg-12">	
			
		</div>		
	</div>
	
	<?php require_once '../../menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">	
			<h2> Page support Parcourir les données enregistrées </h2>
		</div>
		<?php require_once 'piedDePage.php'; ?>
	</div>
	
	
</body>	