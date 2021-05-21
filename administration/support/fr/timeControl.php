<?php session_start(); 
/**
 * @fichier  support/timeControl.php							    		
 * @auteur   Léo Cognard (Touchard Washington le Mans)
 * @date     May 2021
 * @version  v1.0 - First release						
 * @details  support pour la page administration/timeControl.php 
 */
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Support - Users</title>
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
                        <h3>Utilisateur</h3>
                        <h4>Vue d'ensemble</h4>
                        <p>Cette page permet à l'utilisateur de créer une tâche planifiée en rentrant les paramètres nécessaires.</p>
                        <h3>Administrateur</h3>
                        <h4>Suppression</h4>
                        <p>L'administrateur peut sélectionner les fichiers qu'il souhaite supprimer grâce au bouton <mark>Supprimer</mark> et en sélectionnant avec les cases à côté des fichiers.</p>
                        <h3>Documentation</h3>
                        <h4>Informations</h4>
                        <li><p><mark>Minute</mark> : de 00 à 59 ou * pour toutes les minutes </p></li>
                        <li><p><mark>Heure</mark> :  de 0 à 23 ou * pour toutes les heures</p> </li>
                        <li><p><mark>Jour du mois</mark> : de 1 à 31 ou * pour tous les jours du mois </p></li>
                        <li><p><mark>Mois</mark> : de 1 à 12 ou * pour tous les mois </p></li>
                        <li><p><mark>Jour de la semaine</mark> : de 0 à 6 (Dimanche = 0), ou * pour tous les jours</p></li>
                        <li><p><mark>Type d'action</mark> : Sélectionnez Requête HTTP ou script à exécuter.</p></li>
                        <li><p><mark>Option</mark> : Sélectionnez le script à effectuer.</p></li>
                        <h4>Possibilité</h4>
                        <li><p>1-5 : le - permet de choisir un intervalle de valeurs qui sera , ici l'exemple définit les valeurs 1 et 5 seulement.</p></li>
                        <li><p>*/5 : le / permet de répéter l'opération toutes les n minutes, ici l'exemple définit la tâche toutes les 5 minutes</p></li>
                        <li><p>1,5 : la , permet de choisir des valeurs précises, ici l'exemple définit les valeurs 1 et 5 seulement.</p></li>

                    </div>		
                </div>		


            </div>
            <?php require_once '../piedDePage.php'; ?>
        </div>


    </body>	