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
			<h4>Overview of scripts</h4>
			<p>When the connected object records measurements in a channel, it is raw data, which requires quite a bit of refinement
before being able to provide valuable information. This cycle is divided into six stages:
			   <ul>
			   <li>1 collection,</li>
			   <li>2 cleaning, (removal of erroneous data)</li>
			   <li>3 storage,</li>
			   <li>4 transformation, (change of units, normalization, average, min, max ...)</li>
			   <li>5 the analysis (correlation histogram)</li>
			   <li>6 diffusion (graphics)</li>
			   </ul>
			</p>
			<p>Scripts can be implemented to perform step 2 (data cleaning) or step 4 for their transformation</p>
			
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	