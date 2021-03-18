<!----------------------------------------------------------------------------------
    @fichier  administration/support/administration/sms.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/sms.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - REACT</title>
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
					<h4>Vue d'ensemble des déclencheurs (réagir aux évènements)</h4>
					<p>Réagissez lorsque les données d'un canal remplissent certaines conditions</br></p>
					<p>La figure ci-dessous décrit comment le moteur d'événements déclenche des alertes. Un événement est déclenché lorsque les données (ligne bleue) dépassent  le seuil. 
					<p>Lorsque l'option <b>exécuter l'action uniquement la première fois</b> est sélectionnée Les données doivent retomber sous le seuil avant que ne se déclenche le prochain événement </p>. 
					<img src="react_1.png" width="80%" />
					<p>Lorsque l'option <b>Exécuter une action chaque fois que la condition est remplie</b> est sélectionnée, l'événement est déclenché cycliquement à la période sélectionnée, toutes les 10, 30 ou 60 minutes.</p> 
					<img src="react_2.png" width="50%" />
					
					
					
					<p>les déclencheurs fonctionnent avec les actions HTTP, pour effectuer des actions lorsque les données d'un canal remplissent une certaine condition.
					Vous pouvez configurer un déclencheur pour notifier un évenement par SMS ou Mail. 
					Par exemple lorsque le poids d'une ruche chute soudainement, demandez à envoyer un SMS contenant une description de l'évènement.</p></br>
					<h4>Lire les déclencheurs archivés </h4>
					<p> Choisir Déclencheurs dans le menu utilisateur pour afficher le tableau des déclencheurs archivés.</p>
					<p> Pour chacun des déclencheurs, les informations suivantes sont affichées. <ul>
					<li> Le login de l'utilisateur propriétaire </li>
					<li> Le nom du déclencheur </li>
					<li> Le nom du canal à vérifier </li>
					<li> La condition logique permettant de déclencher l'action. Cette condition est composée de trois parties :
						<ul> 
						<li>Un champs du canal </li>
						<li>Un opérateur de comparaison numérique; </li>
						<li>Une valeur numérique</li>
						</ul>
					<li> Le nom de l'application Action Http (cette application exécute le service demandé via une requête http) 
					</ul>
					
					<h4>Supprimer les déclencheurs </h4>
					<p> Vous pouvez supprimer un ou plusieurs déclencheurs archivés. Cocher le ou les déclencheurs à supprimer puis cliquer sur le bouton supprimer. Une fenêtre de confirmation s'ouvre.
					Valider l'action</p></br>
					<h4>Modifier un déclencheur </h4>
					<p> Cocher le déclencheur à modifier puis cliquer sur le bouton modifier. Un formulaire s'ouvre.</p></br>
					
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
	
</body>	