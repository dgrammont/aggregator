<?php
include "authentification/authcheck.php";

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../api/Form.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\Form;

$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

//------------si des donn�es  sont soumises on les enregistre dans la table data.blogs ---------
if (!empty($_POST['envoyer'])) {
    if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) { // si le token est valide
        try {
            if (isset($_POST['action']) && ($_POST['action'] == 'insert')) {
                $sql = sprintf("INSERT INTO `data`.`blogs` (`thing_id`, `title`, `keyWord`, `visitDate`, `comment`, `status`) VALUES ( %s, %s, %s, %s, %s, %s);"
                        , $bdd->quote($_POST['thing_id'])
                        , $bdd->quote($_POST['title'])
                        , $bdd->quote($_POST['keyWord'])
                        , $bdd->quote($_POST['date'] . ' ' . $_POST['heure'])
                        , $bdd->quote($_POST['comment'])
                        , $bdd->quote($_POST['status'])
                );
                $bdd->exec($sql);
            }
            if (isset($_POST['action']) && ($_POST['action'] == 'update')) {
                $sql = sprintf("UPDATE `blogs` SET `thing_id` = %s, `title`=%s, `keyWord`=%s, `visitDate`=%s, `comment`=%s, `status`=%s WHERE `blogs`.`id` = %s;"
                        , $bdd->quote($_POST['thing_id'])
                        , $bdd->quote($_POST['title'])
                        , $bdd->quote($_POST['keyWord'])
                        , $bdd->quote($_POST['date'] . ' ' . $_POST['heure'])
                        , $bdd->quote($_POST['comment'])
                        , $bdd->quote($_POST['status'])
                        , $_POST['id']
                );

                $bdd->exec($sql);
            }
        } catch (\PDOException $ex) {
            echo($ex->getMessage());
            return;
        }

        // destruction du tokenCSRF
        unset($_SESSION['tokenCSRF']);

        header("Location: ../blogs.php?id={$_POST['thing_id']}");
        return;
    }
}
// -------------- sinon lecture de la table data.blogs  -----------------------------
else {
    try {
        if (isset($_GET['id'])) {

            $sql = sprintf("SELECT * FROM `blogs` WHERE `id`=%s", $bdd->quote($_GET['id']));
            $stmt = $bdd->query($sql);
            if ($blog = $stmt->fetchObject()) {
                $blog->action = "update";
            }
        } else {
            $blog = new stdClass();
            $blog->action = "insert";
            $blog->id = 0;
            if (isset($_GET['thingId'])) {
                $blog->thing_id = $_GET['thingId'];
            } else {
                $blog->thing_id = 0;
            }
            $blog->title = "";
            $blog->keyWord = "";
            $blog->visitDate = date("Y-m-d H:i:s");
            $blog->comment = "";
            $blog->status = "";
        }

        $dateTime = explode(" ", $blog->visitDate);
        $blog->date = $dateTime[0];
        $blog->heure = substr($dateTime[1], 0, 5);

        // Création du selectThing

        if ($_SESSION['droits'] > 1) {
            $sql = "SELECT id,name FROM `things` ORDER BY id;";
        } else {
            $sql = "SELECT id,name FROM `things` where user_id = {$_SESSION['id']} ORDER BY id;";
        }
        $stmt = $bdd->query($sql);

        $selectThing = array();
        while ($thing = $stmt->fetchObject()) {
            $selectThing[$thing->id] = $thing->name;
        }
    } catch (\PDOException $ex) {
        echo($ex->getMessage());
        return;
    }

    function afficherMetaBlog($blog, $selectThing) {

        global $lang;
        // Creation du tokenCSRF
        $tokenCSRF = STR::genererChaineAleatoire(32);
        $_SESSION['tokenCSRF'] = $tokenCSRF;

        echo Form::hidden('action', $blog->action);
        echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"]);
        echo Form::hidden("id", $blog->id);

        $optionsRO = array('class' => 'form-control', 'readonly' => null);
        echo Form::input('text', 'author', $_SESSION["login"], $optionsRO, $lang['author']);


        $optionsRE = array('class' => 'form-control', 'required' => 'required');
        echo Form::input('text', 'title', $blog->title, $optionsRE, $lang['title']);

        $options = array('class' => 'form-control');
        echo Form::input('text', 'keyWord', $blog->keyWord, $options, $lang['keyWord']);

        echo Form::input('date', 'date', $blog->date, $options, "Date");
        echo Form::input('time', 'heure', $blog->heure, $options, $lang['time']);
        echo Form::select("thing_id", $selectThing, $lang['thing'], $blog->thing_id);
        echo Form::select("status", $lang['sel_status'], $lang['status'], $blog->status);
    }

    function afficherFormBlog($blog) {

        global $lang;

        echo '<textarea class="form-control" name="comment" id="comment" >';
        echo $blog->comment;
        echo '</textarea>';
    }

}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?= $lang['blog'] ?> - Aggregator</title>


        <link rel="stylesheet" href="../css/ruche.css" />

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

        <link rel="stylesheet" href="../css/jquery-confirm.min.css" />
        <script src="../scripts/jquery-confirm.min.js"></script>	

        <script>
            $(document).ready(function () {
                $('#comment').summernote({
                    toolbar: [
                        ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                        ['font', ['superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview']],
                    ],
                    height: 450,
                    maxHeight: 650,
                    focus: true,
                    placeholder: 'write here...',
                    codemirror: {
                        theme: 'monokai'
                    }
                });

                $("#ecrire").submit(function (e) {
                    console.log("click", this);
                    if ($('#comment').summernote('isEmpty')) {
                        $.alert({
                            title: 'Alert!',
                            content: "L'article est vide"
                        });
                        e.preventDefault();

                    }
                });
            });
        </script>

    </head>


    <body>

        <?php require_once '../menu.php'; ?>

        <div class="container-fluid" style="padding-top: 65px;">
            <form class="form-horizontal" method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>" name="ecrire" id="ecrire">
                <div class="row">

                    <div class="col-md-4 col-sm-12 col-12">
                        <div class="popin">

                            <?php afficherMetaBlog($blog, $selectThing); ?>

                            <div class="form-group">
                                </br>
                                <button id="envoyer" type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
                                <a  class="btn btn-info" role="button" href="../blogs?id=<?= $blog->thing_id ?>"><?= $lang['Cancel'] ?></a>
                            </div>	

                        </div>
                    </div>

                    <div class="col-md-8 col-sm-12 col-12">
                        <div class="popin">
                            <?php afficherFormBlog($blog); ?>
                        </div>
                    </div>

                </div>	
            </form>
            <?php require_once '../piedDePage.php'; ?>
        </div>

    </body>
