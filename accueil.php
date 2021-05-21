<?php
/**
@fichier  support/timeControl.php							    		
@auteur   Léo Cognard (Touchard Washington le Mans)
@date     May 2021
@version  v1.0 - First release						
@details  support pour la page administration/timeControl.php
 */
require_once 'cookieConfirm.php';
require_once('definition.inc.php');
require_once('./api/Api.php');
require_once('./lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

$bdd = Api::connexionBD(BASE);
$url = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);

function makeMarker() {

    global $bdd;
    try {
        if (!isset($_SESSION['id'])) { // Personne n'est connect� donc objet publique
            $sql = 'SELECT * FROM `things` where status = "public";';
        } else if ($_SESSION['id'] != 0) {
            $sql = "SELECT * FROM `things` where user_id = " . $_SESSION['id'];
        } else {   // C'est root qui est connect�, tous les objets sont affich�s
            $sql = "SELECT * FROM `things`";
        }

        $reponse = $bdd->query($sql);
        $marker = "var markers = [\n";
        $infoWindowContent = "var infoWindowContent = [\n";
        $deb = true;
        while ($thing = $reponse->fetchObject()) {
            if (!$deb) {
                $marker .= ",";
                $infoWindowContent .= ",";
            }
            $marker .= "['{$thing->name}', {$thing->latitude} , {$thing->longitude} ]\n";
            $infoWindowContent .= "['<div class=\"info_content\"><h5>{$thing->name}</h5></div>']\n";
            $deb = false;
        }
        $marker .= "];\n";
        $infoWindowContent .= "];\n";
        $reponse->closeCursor();
        echo $marker;
        echo $infoWindowContent;
    } catch (\PDOException $ex) {
        echo $ex->getMessage();
        return;
    }
}

function listerChannels($id) {
    global $lang;
    global $bdd;
    global $url;

    $sql = 'SELECT count(*) as nb FROM `channels` WHERE `thing_id`=' . $bdd->quote($id);

    if ($bdd->query($sql)->fetchObject()->nb > 0) {
        $sql = 'SELECT * FROM `channels` WHERE `thing_id`=' . $bdd->quote($id);
        $reponse = $bdd->query($sql);
        echo "<li  class='folder-data'><a href='#'>{$lang['Data_visualisation']}</a>\n";
        echo "<ul id=\"channel\">\n";
        while ($channel = $reponse->fetchObject()) {
            echo "<li>\n";
            echo "<a class='channels' href='{$url}/channels/{$channel->id}/feeds.json?results=0' >{$channel->name}</a>\n";
            echo "</li>\n";
        }
        echo "</ul>\n";
        echo "</li>\n";
    }
}

function listerMatlabVisu($id) {
    global $lang;
    global $bdd;
    try {
        $sql = "SELECT count(*) as nb FROM `Matlab_Visu` WHERE `things_id`={$id}";
        if ($bdd->query($sql)->fetchObject()->nb > 0) {
            $sql = "SELECT * FROM `Matlab_Visu` WHERE `things_id`={$id}";
            $reponse2 = $bdd->query($sql);
            echo "<li  class='folder-matlab'><a href='#'>{$lang['Data_Analysis']}</a>\n";
            echo "<ul>\n";
            while ($matalVisu = $reponse2->fetchObject()) {
                echo "<li class='analysis'>\n";
                echo '<a  href="./MatlabVisualization?id=' . $matalVisu->thing_speak_id . '&name=' . urlencode($matalVisu->name) . '">' . $matalVisu->name . '</a>';
                echo '</li>';
            }
            echo "</ul>\n";
            echo "</li>\n";
        }
    } catch (\PDOException $ex) {
        die('Erreur BDD: ' . $ex->getMessage());
    }
}

function listerSons($id) {
    global $lang;
    global $bdd;
    global $url;
    try {
        $sql = "SELECT COUNT(*) as nb FROM `things` WHERE  `soundFolder` is NOT NULL AND `id` ={$id}";

        if ($bdd->query($sql)->fetchObject()->nb > 0) {
            echo "<li  class='folder-sounds'>\n";
            echo "<a href='{$url}/sounds?id={$id}'>{$lang['Sounds']}</a>\n";
            echo "</li>\n";
        }
    } catch (\PDOException $ex) {
        die('Erreur BDD: ' . $ex->getMessage());
    }
}

function listerCom($id) {
    global $lang;
    global $bdd;
    global $url;
    try {
        if (isset($_SESSION["login"])) {
            $sql = "SELECT count(*) as nb FROM `things` WHERE `blogStatus` != \"off\" AND `id`={$id}";
        } else {
            $sql = "SELECT count(*) as nb FROM `things` WHERE `blogStatus` = \"public\" AND `id`={$id}";
        }

        if ($bdd->query($sql)->fetchObject()->nb > 0) {
            echo "<li  class='com'><a href='{$url}/blogs?id={$id}'>{$lang['Logbook']}</a>\n";
            echo "</li>\n";
        }
    } catch (\PDOException $ex) {
        die('Erreur BDD: ' . $ex->getMessage());
    }
}

function afficherArbre() {
    global $bdd;
    try {
        if (isset($_SESSION['droits']) && $_SESSION['droits'] == 1) {
            $sql = "SELECT * FROM `things` where user_id = " . $_SESSION['id'];
        } else if (isset($_SESSION['droits']) && $_SESSION['droits'] > 1) { // C'est un administrateur qui est connect�
            $sql = "SELECT * FROM `things`";
        } else {
            $sql = 'SELECT * FROM `things` where status = "public";';
        }
        $reponse = $bdd->query($sql);
        while ($thing = $reponse->fetchObject()) {
            echo "<li class=\"folder-root {$thing->class} \"><a href=\"#\">{$thing->name}</a><ul>\n";
            listerCom($thing->id);
            listerChannels($thing->id);
            listerMatlabVisu($thing->id);
            listerSons($thing->id);
            echo "</ul></li>\n";
        }
        $reponse->closeCursor();
    } catch (\PDOException $ex) {
        echo "erreur BDD";
        die('Erreur : ' . $ex->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?= $lang['Browse_Sites'] ?> - Aggregator</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/ruche.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="scripts/bootstrap.min.js"></script>
        <script src="scripts/file-explore.js"></script>

        <script type="text/javascript">
            "use strict";

            $(document).ready(function () {
                $(".file-tree").filetree();
                $(".channels").click(afficheModal);
                $(".btn-afficher").click(afficherVue);

                // Asynchronously Load the map API 
                let script = document.createElement('script');
                script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyBKUqx5vjYkrX15OOMAxFbOkGjDfAPL1J8&language=<?= $langue ?>&callback=initialize";
                document.body.appendChild(script);
            });

            function initialize() {

                let bounds = new google.maps.LatLngBounds();
                let mapOptions = {
                    mapTypeId: 'roadmap'
                };
                let map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

                map.setTilt(45);

                // Multiple Markers
<?php makeMarker() ?>

                // Display multiple markers on a map
                let infoWindow = new google.maps.InfoWindow();

                // Parcoure le tableau des marqueurs et place chacun sur la carte  
                for (let i = 0; i < markers.length; i++) {
                    let position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                    bounds.extend(position);
                    let marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: markers[i][0]
                    });

                    // Autorise chaque marqueur � avoir une fen�tre d'informations    
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infoWindow.setContent(infoWindowContent[i][0]);
                            infoWindow.open(map, marker);
                        }
                    })(marker, i));

                    // Centre automatiquement la carte en ajustant tous les marqueurs sur l'�cran
                    map.fitBounds(bounds);
                }

                // Si le niveau de zoom est sup�rieur � 18
                // Remplace le niveau de zoom sur la carte une fois la fonction fitBounds ex�cut�e
                let boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {

                    if (this.getZoom() > 15) {
                        this.setZoom(15);
                    }
                    google.maps.event.removeListener(boundsListener);
                });
            }

            function afficheModal(event) {

                let url = $(this).attr("href");

                $.getJSON(url, function (data, status, error) {
                    console.log(data.channel);
                    var contenu = "<div>";
                    $.each(data.channel, function (key, val) {
                        if (key.indexOf("field") != -1) {
                            contenu += '<div id = "choix" class="form-check">'
                            contenu += '<input class="form-check-input" type="checkbox" value="' + key.substring(5, 6) + '" id="' + key + '">';
                            contenu += '<label class="form-check-label" for="' + key + '">';
                            contenu += val;
                            contenu += '</label>';
                            contenu += '</div>';
                        }
                    });
                    contenu += "</div>";

                    $("#modal-contenu").html(contenu);
                    let title = data.channel.id + " : " + data.channel.name;
                    console.log(title);
                    $("#ModalLongTitle").html(title);
                    $(".btn-afficher").attr("id", data.channel.id);  // On fixe l'attribut id du button avec l'id du canal
                    $(".btn-afficher").attr("name", data.channel.name);  // On fixe l'attribut name du button avec le nom du canal
                    $("#ModalCenter").modal('show');
                });

                event.preventDefault();   // bloque l'action par d�faut sur le lien cliqu�
            }

            function afficherVue(event) {

                let url = "./thingView?channel=" + $(this).attr("id");
                let channel_name = $(this).attr("name");
                let choix = [];
                let anyBoxesChecked = false;

                $('#choix  input[type="checkbox"]').each(function () {
                    if ($(this).is(":checked")) {
                        choix.push($(this).val());
                        anyBoxesChecked = true;
                    }
                });
                if (anyBoxesChecked == false) {
                    console.log("pas de choix");
                }

                for (let i = 0; i < choix.length; i++) {
                    url += '&field' + i + '=' + choix[i];
                }
                window.location.href = url;
                $("#ModalCenter").modal('hide');
            }
        </script>
    </head>

    <body>
        <?php require_once 'menu.php'; ?>
        <div class="container" style="padding-top: 75px;">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="popin" style="margin: 0px; padding : 4px;">
                        <ul class="file-tree ">
                            <?php afficherArbre(); ?>								
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="popin" style="margin: 0px;">
                        <div  id="map-canvas" style = "height: 500px; width: 100%;" ></div>
                    </div>
                </div>
            </div>
            <?php
            require_once 'piedDePage.php';
            require_once 'cookieConsent.php';
            ?>
        </div>
        <!--Fen�tre Modal -->
        <div class="modal" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLongTitle">Message !</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-contenu">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-afficher"><?= $lang['display'] ?></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang['close'] ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin de fen�tre Modal -->
    </body>
</html>

