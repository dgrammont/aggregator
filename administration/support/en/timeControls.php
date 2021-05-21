<!----------------------------------------------------------------------------------
@fichier  support/timeControls.php							    		
@auteur   Philippe SIMIER (Touchard Washington le Mans)
@date     Avril 2020
@version  v1.0 - First release						
@details  support pour la page timeControls.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Support - Time Control</title>
        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="../../../css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="../../../scripts/bootstrap.min.js"></script> -->
        <link rel="stylesheet" href="../../../css/bootstrap.min.css" >
        <link rel="stylesheet" href="../../../css/ruche.css" />	

    </head>

    <body>

        <?php require_once '../../../menu.php'; ?>

        <div class="container" style="padding-top: 75px;" >
            <div style="min-height : 500px">
                <div class="row" style="background-color:white; padding-top: 10px; ">
                    <div class="col-lg-12">
                        <h2>Time control</h2>
                        <p>The TimeControl app works with other aggregator apps: php Analysis, ThingHTTP, shell script, to perform an action at a specific time or on a regular schedule.<br />
                            You can use TimeControl with:
                        <ul>
                            <li>ThingHTTP to communicate with devices, websites, or web services.</li>

                            <li>php Analysis to act on your data.</li>
                        </ul>

                        For example, you can make a ThingHTTP request that calls someone via SMS.</p>





                    </div>
                </div>
            </div>			

            <?php require_once '../piedDePage.php'; ?>
        </div>
    </body>	