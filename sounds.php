<?php
session_start();

require_once('definition.inc.php');
require_once('api/Api.php');
require_once('api/Str.php');
require_once('lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
$bdd = Api::connexionBD(BASE);

$thing_id = Api::obtenir("id", FILTER_VALIDATE_INT);
try {
    $sql = "SELECT * FROM `things` where id={$thing_id}";
    $stmt = $bdd->query($sql);
    $thing = $stmt->fetchObject();
} catch (\PDOException $ex) {
    echo($ex->getMessage());
    return;
}

// fonction pour lister les fichiers audio appartenant à un objet
function listeFichiersAudio($thing) {

    $nb_fichier = 0;

    $cdir = scandir($thing->soundFolder);

    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", "..", "index.php"))) {
            $nb_fichier++;
            $filename = './' . $thing->soundFolder . '/' . $value;
            $dateCreate = date("d m Y H:i:s", filemtime($filename));
            $type = mime_content_type($filename);

            if ($type === "audio/x-wav") {

                $Spectrogramme = substr($filename, 0, -3) . 'png';
                echo "<tr>";
                echo "<td><input type='checkbox' name='all' value='$value' ></td>\n";
                echo "<td>{$dateCreate}</td>\n";
                echo "<td><audio controls><source src=\"{$filename}\" type='audio/wav'></audio></td>\n";
                echo "<td>{$type}</td>\n";
                echo "<td><a class=\"spectro\" href=\"{$Spectrogramme}\">{$value}</a></td>\n";
                echo "</tr>\n";
            }
        }
    }
}

// Fonction pour afficher le bouton supprimer
function AfficherSupprimer($thing) {
    global $lang;
    if (isset($_SESSION["id"])) {
        if ($thing->user_id == $_SESSION["id"] || $_SESSION["id"] == 0 )
            echo "<input id=\"btn_supp\" name=\"btn_supprimer\" value=\"{$lang['delete']}\" class=\"btn btn-danger\" readonly size=\"9\">";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Sounds - Aggregator</title>
        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/bootstrap.min.css" >
        <link rel="stylesheet" href="./css/ruche.css" />
        <link rel="stylesheet" href="./css/datatables.min.css"/>
        <link rel="stylesheet" href="./css/dataTables.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="./scripts/popper.min.js"></script>
        <script src="./scripts/bootstrap.min.js"></script>
        <script src="./scripts/jquery-confirm.min.js"></script>
        <script src="//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>


        <script>
            $(document).ready(function () {
                let options = {
                    dom: 'ptlf',
                    pagingType: "simple_numbers",
                    lengthMenu: [6, 12, 18, 24],
                    pageLength: 6,
                    order: [[1, 'desc']],
                    columns: [{orderable: false}, {type: "text"}, {type: "text"}, {type: "text"}, {type: "text"}],
                    "language": {
                        "url": "./administration/<?= $lang['dataTables'] ?>"
                    }

                };
                $('#tableau').DataTable(options);

                function cocherTout(etat)
                {
                    let cases = document.getElementsByTagName('input');   // on recupere tous les INPUT
                    for (let i = 1; i < cases.length; i++)     // on les parcourt
                        if (cases[i].type === 'checkbox')     // si on a une checkbox...
                        {
                            cases[i].checked = etat;
                        }
                }


                $("#all").click(function () {
                    cocherTout(this.checked);
                });

                $(".spectro").click(afficherModal);

                function afficherModal(event) {
                    let url = $(this).attr("href");
                    console.log(url);
                    let contenu = "<div>";
                    contenu += " <img src=\"" + url + "\" class=\"img-thumbnail\" alt=\"Spectrogramme\"> ";
                    contenu += "</div>";

                    let title = url.split("/");
                    console.log(title);
                    let subtitle = title[3].split(".");
                    $("#modal-contenu").html(contenu);
                    $("#ModalLongTitle").html("Spectrogramme " + subtitle[0] + ".wav");
                    $("#ModalCenter").modal('show');
                    event.preventDefault();   // bloque l'action par défaut sur le lien cliqué
                }
            });
        </script>

    </head>

    <body>

        <?php require_once 'menu.php'; ?>

        <div class="container" style="padding-top: 65px;">

            <div class="row popin card">
                <div class="col-md-12 col-sm-12 col-xs-12">    
                    <div  class="card-header" style=""><h4>Enregistrements sonors</h4></div>
                    <table id="tableau"  class="display table table-striped">
                        <thead>
                            <tr>
                                <th><input type='checkbox' name='all' value='all' id='all' ></th>
                                <th>Date</th>
                                <th>Fichier</th>
                                <th>Type</th>
                                <th>Spectrogramme</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php listeFichiersAudio($thing); ?>
                        </tbody>

                    </table>
                    <?php AfficherSupprimer($thing); ?>
                </div>
            </div>
            <?php require_once 'piedDePage.php'; ?>

        </div>
        <!--Fenêtre Modal pour spectro-->
        <div class="modal" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1000px;">
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

                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang['close'] ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin de fenêtre Modal -->

    </body>

