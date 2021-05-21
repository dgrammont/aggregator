<?php session_start(); 

	if (isset($_SERVER["REDIRECT_STATUS"])){
		$error =$_SERVER["REDIRECT_STATUS"]; 
	}else{
		$error = "inconnue";
	}	
	switch ($error) {
		case 404:
			$message = "Peut-être que cette page a été déplacée? A-t'elle été supprimée? est-elle cachée en quarantaine? Ou en premier lieu n'a t'elle jamais existée?";
			$messageEn = "Maybe this page moved? Got deleted? Is hiding out in quarantine? Never existed in the first place?";
			break;
		case 403:
			$message = "Vous n'êtes pas autorisé à accéder à cette page.";
			break;
		case 500;
			$message = "le serveur a rencontré une condition inattendue qui l’a empêché de répondre à la requête.";
			break;
		default:
			$message = "";	
	}	






?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Aggregator - Erreur</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="/Aggregator/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
	<script src="/Aggregator/scripts/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/Aggregator/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/Aggregator/css/ruche.css" />
	
	<style>
	.msg {
		text-align: center;
		
		font-size: 1 rem;
		position:absolute;
		left: 12%;
		top: 15%;
		width: 75%;
		padding: 50px;
	}
	</style>
	
	

</head>

<body>
	
	<?php require_once './menu.php'; ?>
	<div class="container" style="padding-top: 65px;">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div style="min-height : 500px">
					
					<div class="msg popin">
					<h1>Oups un problème !</h1>
					<p>Vous venez de rencontrer une erreur HTTP <b><?= $error ?></b> pour la page <b><?= $_SERVER["REQUEST_URI"] ?></b></p>
					<p><?= $message ?></p>
					</div>
					
				</div>
				<footer>
					<br />
					<hr>
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<p>Aggregator - Support</p>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<p class= 'copyright'>Copyright &copy; Section Snir Lycée Touchard - le Mans</p>
						</div>
					</div>
				</footer>
			</div>
		</div>
	</div>	
	
	
</body>	