<?php
/**
 * @fichier  administration/support/administration/index.php							    		
 * @auteur   Dylan Grammont (Touchard Washington le Mans)
 * @date     Mai 2021
 * @version  v1.0 - First release						
 * @details 
 */
session_start();

require_once('definition.inc.php');
require_once('api/Api.php');
require_once('api/Str.php');
require_once('lang/lang.conf.php');

use Aggregator\Support\Api;

/* connexion à la base */
$bdd = Api::connexionBD(BASE);

/* Si le formulaire a été soumis */
/* On suprimme les fichiers demandés */
if (isset($_POST['btn_supprimer'])) {
    foreach ($_POST["delete"] as $value) {
        $soundFile = $_POST["soundFolder"] . "/".$value;
        $spectroFile = $Spectrogramme = substr($soundFile, 0, -3) . 'png';
        if(is_file($soundFile)){
            unlink($soundFile);
        }
        if(is_file($spectroFile)){
            unlink($spectroFile);
        }
    }
}

/* Création de l'objet thing */
$thing_id = Api::obtenir("id", FILTER_VALIDATE_INT);
try {
    $sql = "SELECT * FROM `things` where id={$thing_id}";
    $stmt = $bdd->query($sql);
    $thing = $stmt->fetchObject();
} catch (\PDOException $ex) {
    echo($ex->getMessage());
    return;
}

/* fonction pour lister les fichiers audio appartenant à un objet */
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
                echo "<td><input type='checkbox' name='delete[$value]' value='$value' ></td>\n";
                echo "<td>{$dateCreate}</td>\n";
                echo "<td><audio controls><source src=\"{$filename}\" type='audio/wav'></audio></td>\n";
                echo "<td><a class=\"info\" href=\"{$filename}\">{$type}</td>\n";
                echo "<td><a class=\"spectro\" href=\"{$Spectrogramme}\">{$value}</a></td>\n";
                echo "</tr>\n";
            }
        }
    }
    
}

/**
 * 
 * @global type $lang
 * @param type $thing
 * @detail Fonction pour afficher le bouton supprimer
 */
function AfficherSupprimer($thing) {
    global $lang;
    if (isset($_SESSION["id"])) {
        if ($thing->user_id == $_SESSION["id"] || $_SESSION["id"] == 0)
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
        <link rel="stylesheet" href="./css/ruche.css" />
        <link rel="stylesheet" href="./css/datatables.min.css"/>
        <link rel="stylesheet" href="./css/dataTables.css" />
        <link rel="stylesheet" href="./css/jquery-confirm.min.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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

                $(".spectro").click(afficherSpectro);
                $(".info").click(afficherInfo);

                function afficherSpectro(event) {
                    let url = $(this).attr("href");
                    console.log(url);

                    contenu = " <img src=\"" + url + "\"  alt=\"Spectrogramme\"> ";


                    let title = url.split("/");
                    console.log(title);
                    let subtitle = title[3].split(".");
                    $.dialog({
                        columnClass: 'col-md-10 col-sm-10 col-12',
                        theme: 'bootstrap',
                        title: "Spectrogramme " + subtitle[0] + ".wav",
                        content: contenu,
                    });
                    event.preventDefault();   // bloque l'action par défaut sur le lien cliqué
                }

                function afficherInfo(event) {
                    let url = $(this).attr("href");
                    let title = url.split("/");

                    // Requête ajax pour obtenir les informations sur le fichier audio
                    let urlApi = "api/sound.php";
                    let query = "file=" + url + "&type=json";
                    console.log(urlApi);
                    $.getJSON(urlApi, query, function (data) {

                        var items = [];
                        $.each(data, function (key, val) {
                            items.push("<tr><td><pre>" + val + "</pre></td></tr>");
                        });
                        contenu = "<div class=\"table-responsive\">";
                        contenu += "<table class='table display table-hover table-sm'>";
                        contenu += items.join("");
                        contenu += "</table>";
                        contenu += "</div>";

                        console.log(contenu);
                        $.dialog({
                            columnClass: 'col-md-6 col-sm-10 col-12',
                            theme: 'bootstrap',
                            title: 'Informations',
                            content: contenu
                        });

                    });
                    event.preventDefault();   // bloque l'action par défaut sur le lien cliqué
                }

                $("#btn_supp").click(function () {
                    console.log("Bouton Supprimer cliqué");

                    nbCaseCochees = $('input:checked').length - $('#all:checked').length;
                    console.log(nbCaseCochees);
                    if (nbCaseCochees > 0) {

                        $.confirm({
                            theme: 'bootstrap',
                            title: 'Confirm!',
                            content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' enregistrement(s) ?',
                            buttons: {
                                confirm: {
                                    text: 'Confirmation',
                                    btnClass: 'btn-blue',
                                    action: function () {
                                        $("#supprimer").submit(); // soumission du formulaire
                                    }
                                },
                                cancel: {
                                    text: 'Annuler', // text for button
                                    action: function () {}
                                }
                            }
                        });

                    } else {
                        $.alert({
                            theme: 'bootstrap',
                            title: 'Alert!',
                            content: "Vous n'avez sélectionné aucun enregistrement !"
                        });

                    }
                });
            });
        </script>

    </head>

    <body>

        <?php require_once 'menu.php'; ?>

        <div class="container" style="padding-top: 65px;">

            <div class="row popin card">
                <div class="col-md-12 col-sm-12 col-xs-12">    
                    <div  class="card-header" style=""><h4><?=$lang['sound_recordings']?></h4></div>
                    <form method="post" id="supprimer">
                        <input type='hidden' name='soundFolder' value = '<?= $thing->soundFolder ?>' />
                        <div class="table-responsive">
                            <table id="tableau"  class="display table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type='checkbox' name='all' value='all' id='all' ></th>
                                        <th>Date</th>
                                        <th><?=$lang['player']?></th>
                                        <th>Informations</th>
                                        <th><?= $lang['spectrogram'] ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php listeFichiersAudio($thing); ?>
                                </tbody>

                            </table>
                        </div>
                        <?php AfficherSupprimer($thing); ?>
                    </form>

                </div>
            </div>
            <?php require_once 'piedDePage.php'; ?>

        </div>


    </body>