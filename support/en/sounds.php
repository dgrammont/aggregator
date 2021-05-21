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
                        <h3>User</h3>
                        <h4>Overwiew</h4>
                        <p>This page allows the user to listen to different audio files coming from the hive, he can also consult more precise information on the audio files.</p>
                        <p>He can consult the spectrogram using the button in the column <mark>Spectrogram</mark></p>
                        <p>Similarly for the information of the audio file with the button in the column<mark>Informations</mark></p>

                        <h3>Administrator</h3>
                        <h4>Remove</h4>
                        <p>The administrator can select the files he wishes to delete using the button<mark>Remove</mark> and selecting with the boxes next to the files.</p>
                        <h3>Documentation</h3>
                        <h4>Informations</h4>
                        <li><p><mark>DC offset</mark> : Corresponds to a DC component which shifts the signal towards the plus or the minus</p> </li>
                        <li><p><mark>Min level</mark> : Placeholder</p></li>
                        <li><p><mark>Max level</mark> : Placeholder</p></li>
                        <li><p><mark>Pk lev dB</mark> : Placeholder</p></li>
                        <li><p><mark>RMS lev dB</mark> : Placeholder</p></li>
                        <li><p><mark>RMS Pk dB </mark> : Placeholder</p></li>
                        <li><p><mark>RMS Tr dB</mark> : Placeholder</p></li>
                        <li><p><mark>Crest factort</mark> : Placeholder</p></li>
                        <li><p><mark>Flat factor</mark> : Placeholder</p></li>
                        <li><p><mark>Pk count</mark> : Placeholder</p></li>
                        <li><p><mark>Bit-depth</mark> : Placeholder</p></li>
                        <li><p><mark>Num samples</mark> : Placeholder</p></li>
                        <li><p><mark>Length s</mark> : Placeholder</p></li>
                        <li><p><mark>Scale max</mark> : Placeholder</p></li>
                        <li><p><mark>Window s</mark> : Placeholder</p></li>

                    </div>		
                </div>		


            </div>
            <?php require_once 'piedDePage.php'; ?>
        </div>


    </body>	