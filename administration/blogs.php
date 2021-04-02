<?php
include "authentification/authcheck.php";

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);


// Si le formulaire a été soumis
if (isset($_POST['btn_supprimer'])) {
    // Si un élément a été sélectionné création de la liste des id à supprimer
    if (count($_POST['table_array']) > 0) {
        $Clef = $_POST['table_array'];
        $supp = "(";
        foreach ($Clef as $selectValue) {
            if ($supp != "(") {
                $supp .= ",";
            }
            $supp .= $selectValue;
        }
        $supp .= ")";


        $sql = "DELETE FROM `blogs` WHERE `id` IN " . $supp;
        $bdd->exec($sql);
    }
}
?>
<!DOCTYPE html>

<html>
    <head>
        <title><?= $lang['blog'] ?> - Aggregator</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/ruche.css" />
        <link rel="stylesheet" href="../css/jquery-confirm.min.css" />
        <link rel="stylesheet" href="../css/datatables.min.css"/>
        <link rel="stylesheet" href="../css/dataTables.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="../scripts/bootstrap.min.js"></script>
        <script src="../scripts/jquery-confirm.min.js"></script>
        <script src="//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

        <script >
            $(document).ready(function () {

                let options = {

                    dom: 'ptlf',
                    pagingType: "simple_numbers",
                    lengthMenu: [5, 10, 15, 20, 40],
                    pageLength: 10,
                    order: [[1, 'desc']],
                    columns: [{orderable: false}, {type: "text"}, {type: "text"}, {type: "text"}],
                    "language": {
                        "url": "<?= $lang['dataTables'] ?>"
                    }
                };


                $('#tableau').DataTable(options);





                function cocherTout(etat)
                {
                    var cases = document.getElementsByTagName('input');   // on recupere tous les INPUT
                    for (var i = 1; i < cases.length; i++)     // on les parcourt
                        if (cases[i].type == 'checkbox')     // si on a une checkbox...
                        {
                            cases[i].checked = etat;
                        }
                }


                $("#all").click(function () {
                    cocherTout(this.checked);
                });


                $("#btn_supp").click(function () {
                    console.log("Bouton Supprimer cliqué");

                    nbCaseCochees = $('input:checked').length - $('#all:checked').length;
                    console.log(nbCaseCochees);
                    if (nbCaseCochees > 0) {

                        $.confirm({
                            theme: 'bootstrap',
                            title: 'Confirm!',
                            content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' commentaire(s)?',
                            buttons: {
                                confirm: {
                                    text: 'Confirmation', // text for button
                                    btnClass: 'btn-blue', // class for the button
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
                            title: 'Alerte !',
                            content: "Vous n'avez sélectionné aucun commentaire !"
                        });

                    }
                });

                $("#btn_mod").click(function () {
                    console.log("Bouton modifier cliqué");

                    // Ce tableau va stocker les valeurs des checkbox cochées
                    var checkbox_val = [];

                    // Parcours de toutes les checkbox checkées"
                    $('.selection:checked').each(function () {
                        checkbox_val.push($(this).val());
                    });
                    if (checkbox_val.length == 0) {
                        $.alert({
                            theme: 'bootstrap',
                            title: 'Alerte !',
                            content: "Vous n'avez sélectionné aucun commentaire !"
                        });
                    }
                    if (checkbox_val.length > 1) {
                        $.alert({
                            theme: 'bootstrap',
                            title: 'Alerte !',
                            content: "Vous avez sélectionné plusieurs commentaires !"
                        });
                    }
                    if (checkbox_val.length == 1) {
                    console.log("blog?id" + checkbox_val[0]);
                       window.location = 'blog?id=' + checkbox_val[0];
                    }
                });

                $("#btn_add").click(function () {
                    console.log("Bouton Ajouter cliqué");
                    window.location = 'blog'
                });
            });

        </script>

    </head>

    <body>
        <?php require_once '../menu.php'; ?>
        <div class="container" style="padding-top: 65px; max-width: 90%;">
            <div class="row popin card">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div  class="card-header" style=""><h4><?= $lang['blog'] ?></h4></div>
                    <div class="table-responsive">
                        <form method="post" id="supprimer">
                            <table id="tableau" class="table display table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th><input type='checkbox' name='all' value='all' id='all' ></th>
                                        <th><?= $lang['user'] ?></th>
                                        <th><?= $lang['title'] ?></th>
                                        <th><?= $lang['release_date'] ?></th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $sql = "SELECT * FROM `vue_blogs` where things_id = {$_GET["id"]}";
                                        $sql .= " order by `comment_id` ";
                                        $stmt = $bdd->query($sql);

                                        while ($blog = $stmt->fetchObject()) {

                                            echo "<tr><td><input class='selection' type='checkbox' name='table_array[$blog->comment_id]' value='$blog->comment_id' ></td>";
                                            echo "<td>" . $blog->login . "</td>";
                                            echo "<td>" . $blog->title . "</td>";
                                            echo "<td>" . $blog->visitDate . "</td>";

                                            echo "</tr>";
                                        }
                                    } catch (\PDOException $ex) {
                                        echo($ex->getMessage());
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <button id="btn_add" type="button" class="btn btn-secondary"><?= $lang['add'] ?></button>
                            <button id="btn_mod" type="button" class="btn btn-secondary"><?= $lang['edit_settings'] ?></button>
                            <input  id="btn_supp" name="btn_supprimer" value="<?= $lang['delete'] ?>" class="btn btn-danger" readonly>
                        </form>
                    </div>
                </div>
            </div>
            <?php require_once '../piedDePage.php'; ?>
        </div>
    </body>
</html>