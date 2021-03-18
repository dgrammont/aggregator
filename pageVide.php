<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>the connected beehive</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="./scripts/popper.min.js"></script>
	<script src="./scripts/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.min.css" >
    <link rel="stylesheet" href="./css/ruche.css" />
	
	
	
	<script>
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover();
		});
	</script>

</head>

<body>
	
	<div class="row" style="background-color:white; padding-top: 35px; ">
		<div class="col-lg-12">
			
			
		</div>
		
	</div>
	
	<?php require_once 'menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">
       

		<br />
		<a href="#" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="auto" title="Info Réseau" data-content="Operator : <b>Sigfox-France</b><br />Quality : <b>Good</b>">Toggle popover</a>		
	
		</div>
		<?php require_once 'piedDePage.php'; ?>
	</div>
	
	
</body>	