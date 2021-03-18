<?php
	require_once 'cookieConfirm.php';
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Aggregator</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/ruche.css" />
	
</head>

<body>
	
	<div class="row" style="background-color:white; padding-top: 35px; ">
		<div class="col-lg-12">
			
			<a href="index.php">
			<img  style="width:100%;" title="Retour accueil" src="images/bandeau_ruche.png" />
			</a>
		</div>
		
	</div>
	
	<?php require_once 'menu.php'; ?>
	
	<div class="container" >
			
	<div class="row popin" style="padding-top: 35px; ">
		<div class="col-lg-12">
			<h2>La ruche connectée</h2>
			<p>Gagnez en sérénité et en réactivité! Suivez vos ruches en direct depuis votre smartphone. Economisez votre temps et vos déplacements.</p>
			<p>D’une installation facile et rapide, le système se positionne sous n’importe quelle ruche et délivre en temps réel un suivi précis des grandeurs mesurées, 
			via des vues graphiques. Ces données sont autant d’indices qui permettent à l’apiculteur de surveiller ses ruches à distance. Plus besoin d'inspecter au petit bonheur
			la chance ses ruches disséminées dans la nature, dans des endroits souvent d’accès malaisé.</p> 
			<p> Un SMS prévient des variations de poids à la hausse, signe du début de la miellée qui ne dure que de une à trois semaines. C'est le moment crucial  pour transhumer
            un rucher complet sur les lieux de la floraison.
			En cas de besoin d’intervention, des notifications sont transmises par SMS sur smartphone. Vous pouvez configurer vous même vos déclencheurs pour les ajuster à vos préferences</p>
		</div>
	</div>
	
	<div class="row popin">
	<div class="col-md-4 col-sm-4 col-xs-12">
		<p style="text-align:center;"><img src="images/picto_masse.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Masse</strong></p>
		<p><span class="span_ent_defaut"> La balance donne des indications pour : </span></p>
		<ul>
		<li> Installer les nouvelles « hausses » sur lesquelles les abeilles vont travailler </li>
		<li> Détecter le début et la fin d'une miellée. </li>
		<li> Mesurer en hiver, le niveau de consommation des réserves.</li>
		<li> Signaler la possibilité d'un essaimage suite à une baisse brutale du poids de la ruche – entre 2 et 4 kilos en une heure – .</li>
		</ul>
		<p> En visualisant la courbe de poids en période de miellée, on peut observer le matin  au départ des butineuses,  une baisse du poids. 
		Puis au cours de la journée, à la rentrée du nectar une augmentation du poids. A la tombée de la nuit lorsque toutes les butineuses sont rentrées, le poids cesse d'augmenter  . 
		Au cours de la nuit, La diminution du poids est due au séchage du miel. Les jeunes ouvrières dont c'est le travail, 
		s'emploient en permanence à faire s'évaporer l'eau dans le nectar, fraichement apporté</p>
				
		
		<p style="text-align:center;"><img src="images/picto_eclairement.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Eclairement</strong>
		<br><span class="span_ent_defaut">La mesure de l'éclairement permet d'évaluer la durée d'activité des butineuses au cours de la la journée</span></p>
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12">
		<p style="text-align:center;"><img src="images/picto_temperature.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Température</strong></p>
		<p><span class="span_ent_defaut">La température au sein du couvain dans la ruche est de 35,6 °C. Pour mesurer cette valeur le capteur de température doit impérativement
		être placé au centre de la ruche au coeur du couvain. 
		La colonie maintient cette température constante à plus au moins 4 dixièmes de degré. 
		Sachez que les abeilles peuvent chauffer la partie de la ruche réservée au couvain, en utilisant leurs muscles. La tension musculaire sans mouvement produit de la chaleur. 
		En été, lorsque la température extérieur est caniculaire, le développement des larves peut être en danger. 
		Le rafraichissement est assurée par évaporation de l'eau dans la ruche qui est provoquée par  la ventilation des ouvrières.</span></p>
		
		<p style="text-align:center;"><img src="images/picto_humidite.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Humidité</strong>
		<br><span class="span_ent_defaut">Le taux d'humidité à l'intérieur de la ruche est relativement constant. En été comme en hiver, le taux est compris entre 45 et 60% </span></p>
		
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12">
		<p style="text-align:center;"><img src="images/picto_pression.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Pression atmosphérique</strong>
		<br><span class="span_ent_defaut">Prévision des changements météo influençant le comportement des abeilles.</span></p>
		
		<p style="text-align:center;"><img src="images/picto_autres.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Autres fonctionnalités</strong>
		<br><span class="span_ent_defaut">Un suivi de l'état de charge de la batterie est intégré.</span></p>
	</div>
	
	</div>
	
	<?php 
		require_once 'piedDePage.php';
		require_once 'cookieConsent.php';
	?>
	</div>
</body>	