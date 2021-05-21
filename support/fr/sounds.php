<!----------------------------------------------------------------------------------
    @fichier  support/sounds.php							    		
    @auteur   Dylan Grammont (Touchard Washington le Mans)
    @date     May 2021
    @version  v1.0 - First release						
    @details  support pour la page administration/sounds.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Users</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../css/ruche.css" />	
</head>

<body>
	<?php require_once '../../menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">
		<div class="row" style="background-color:white; padding-top: 65px; ">
		<div class="col-lg-12">
                    <h3>Utilisateur</h3>
		<h4>Vue d'ensemble</h4>
		<p>Cette page permet à l'utilisateur d'écouter différents fichiers audios provenant de la ruche , il peut aussi consulter des informations plus précises sur les fichiers audios.</p>
		<p>Il peut consulter le spectrogramme grâce au bouton dans la colonne <mark>Spectrogramme</mark></p>
		<p>Pareillement pour les informations du fichier audio avec le bouton dans la colonne <mark>Informations</mark></p>
		
                <h3>Administrateur</h3>
		<h4>Suppression</h4>
		<p>L'administrateur peut sélectionner les fichiers qu'il souhaite supprimer grâce au bouton <mark>Supprimer</mark> et en sélectionnant avec les cases à côté des fichiers.</p>
		
                <h3>Documentation</h3>
                <h4>Informations</h4>
                <li> <p><mark>DC offset</mark> : Correspond à une composante continue qui décale le signal vers le plus ou le moins</p> </li>
                <li><p><mark>Min level</mark> : Tension minimum</p></li>
                        <li><p><mark>Max level</mark> : Tension maxmimum</p></li>
                        <li><p><mark>Pk lev dB</mark> : Valeur la plus élévé en dB</p></li>
                        <li><p><mark>RMS lev dB</mark> : Composante alternative (20log de la tension éff  alternative / 1Volt)</p></li>
                        <li><p><mark>RMS Pk dB </mark> : </p> (20log de la valeur maximal de la composante alertnative)</li>
                        <li><p><mark>RMS Tr dB</mark> : </p>? Valeurs minimales efficaces ?</p></li>
                        <li><p><mark>Crest factort</mark> : Le facteur de crête est une mesure caractéristique d'un signal. C'est le rapport entre l'amplitude du pic du signal et la valeur efficace du signal.</p></li>
                        <li><p><mark>Flat factor</mark> : ?</p></li>
                        <li><p><mark>Pk count</mark> : Nombre de crête</p></li>
                        <li><p><mark>Bit-depth</mark> : Nombre de bit</p></li>
                        <li><p><mark>Num samples</mark> : Nombre d'échantillon</p></li>
                        <li><p><mark>Length s</mark> : Temps d'enregistrement </p></li>
                        <li><p><mark>Scale max</mark> : La tension réferentielle </p></li>
                        <li><p><mark>Window s</mark> : Le temps d'échantillonage</p></li>
		</div>		
	</div>		
	
	
		</div>
		<?php require_once 'piedDePage.php'; ?>
	</div>
	
	
</body>	