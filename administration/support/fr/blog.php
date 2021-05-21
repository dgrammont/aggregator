<?php session_start(); 
/**----------------------------------------------------------------------------------
    @fichier  support/fr/blogs.php							    		
    @auteur   (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page blogs.php
------------------------------------------------------------------------------------*/


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Article</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="../../../scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../../../css/ruche.css" />

	
</head>

<body>
	<?php require_once '../../../menu.php'; ?>
	
	<div class="container" style="padding-top: 65px;">
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
	
	
				<div class="popin" style="min-height : 500px">	
					
					<h3>L'espace de rédaction</h3><hr>
					<p>Pour écrire un nouvel article, cliquez sur le cadre "Que voulez-vous dire aujourd'hui", vous arrivez alors sur la page de rédaction. 
					Cette dernière est décomposée en deux parties :
					<ul>
					    <li>les meta-données (titre, date de publication, auteur, status de visibilité, mots clés)</li>
						<li>un champ Texte, car c'est lui qui vous permettra de commencer à rédiger votre article. </li>
					</ul>
					<p>Voici toutes les possibilités que propose le champ Texte de gauche à droite : 
					<ul>
						<li>Style : permet de choisir les balises paragraphe, titre de 1 à 6, bloc de citation, code.</li>
						<li> B, I, U, S  : permet de mettre en gras, italique, souligné  votre texte. </li>
						<li>une gomme : ce petit bouton magique vous permet de supprimer la mise en forme de votre texte.</li>
						<li>x<sup>2</sup> et x<sub>2</sub> : ce sont les symboles pour mettre en exposant ou en indice.</li> 
						<li>Taille : permet de choisir la taille du texte.</li>
						<li>Choisir la couleur de texte ou la couleur d'arrière-plan du texte</li> 
						<li>Liste : deux choix avec une liste numérotée ou une liste à puce. </li>
						<li>Texte aligné, à droite, au centre, à gauche ou justifié. Augmenter ou diminuer le retrait du texte.</li>
						<li>Ajouter un tableau</li>
						<li>Insérer un lien</li>
						<li>Ajouter une image</li>
						<li>Ajouter une video via une plateforme (YouTube, Vimeo, Vine, Instagram, DailyMotion ou Youku)</li>
						<li>Accéder au code source de l'article</li>
					</ul>
					</p><br />
					<h3>Les options de publication</h3><hr>
					<p>Vous avez terminé de rédiger votre article, il est donc temps de regarder du côté des Options de publication. </p>
					<p>Dans cette partie, vous pouvez intervenir sur certains paramètres de votre article avant sa publication, voici lesquels : 
						<ul>
						<li><b>Auteur</b>, par défaut c'est le nom ou pseudo que vous avez indiqué pour ouvrir votre session;</li>
						<li><b>Titre</b>, C'est le titre principal de l'article, il est  à renseigner obligatoirement;</li>
						<li><b>Mots clés</b>,  associez votre article à une ou plusieurs catégories; les mots clés sont séparés par une virgule</li>
						<li><b>Date</b>, vous pouvez modifier la date de publication de l'article grâce à plusieurs options. Vous pouvez le publier immédiatement, 
						                 mais aussi le programmer et l'antidater.</li> 
						<li><b>Objet</b>, Permet de choisir le journal de bord d'une ruche auquel l'article appartient; <li>				 
						<li><b>Status</b>, indique l'état de l'article. Cliquez sur "privé" pour l'enregistrer dans vos brouillons ou sur "public" pour le publier.</li>
						</ul>
					</p>
					<br/>
					<p>Vous savez désormais tout sur la rédaction d'article sur aggregator</p>
					
				</div>
			</div>
		</div>	
		<?php require_once '../../../piedDePage.php'; ?>
	</div>
	
	
</body>	