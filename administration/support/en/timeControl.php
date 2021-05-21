<?php
session_start();
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
                        <h3>User</h3>
                        <h4>Overview</h4>
                        <p>This page allows the user to create a scheduled task by entering the necessary parameters.</p>
                        <h4>Deletion</h4>
                        <p>The user can select the files he wishes to delete using the button<mark>Remove</mark> and selecting with the boxes next to the files.</p>
                        <h3>Documentation</h3>
                        <h4>Informations</h4>
                        <li><p><mark>Minute</mark> : from 00 to 59 or * for every minute</p></li>
                        <li><p><mark>Hour</mark> : from 0 to 23 or * for all hours</p> </li>
                        <li><p><mark>Day of the month</mark> : from 1 to 31 or * for every day of the month</p></li>
                        <li><p><mark>Month</mark> : from 1 to 12 or * for every month</p></li>
                        <li><p><mark>Day of the week</mark> : from 0 to 6 (Sunday = 0), or * for every day</p></li>
                        <li><p><mark>Type of action</mark> : Select HTTP request or script to execute.</p></li>
                        <li><p><mark>Option</mark> : Select the script to perform.</p></li>
                        <h4>Possibility</h4>
                        <li><p>1-5: the - allows to choose an interval of values ​​which will be, here the example defines the values ​​1 and 5 only.</p></li>
                        <li><p>* / 5: the / allows you to repeat the operation every n minutes, here the example defines the task every 5 minutes</p></li>
                        <li><p>1,5: the, allows you to choose precise values, here the example defines values ​​1 and 5 only.</p></li>

                    </div>		
                </div>		


            </div>
<?php require_once '../piedDePage.php'; ?>
        </div>


    </body>	