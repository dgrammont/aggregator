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

//------------si des données  sont soumises on les enregistre dans la table data.blogs ---------
if (!empty($_POST['envoyer'])) {
    if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) { // si le token est valide
        try {
            if (isset($_POST['action']) && ($_POST['action'] == 'insert')) {
                $sql = sprintf("INSERT INTO `data`.`blogs` (`thing_id`, `title`, `subTitle`, `visitDate`, `comment`, `status`) VALUES ( %s, %s, %s, %s, %s, %s);"
                        , $bdd->quote($_POST['thing_id'])
                        , $bdd->quote($_POST['title'])
                        , $bdd->quote($_POST['subTitle'])
                        , $bdd->quote($_POST['visitDate'])
                        , $bdd->quote($_POST['comment'])
                        , $bdd->quote($_POST['status'])
                );
                $bdd->exec($sql);
            }
            if (isset($_POST['action']) && ($_POST['action'] == 'update')) {
                $sql = sprintf("UPDATE `blogs` SET `thing_id` = %s, `title`=%s, `subTitle`=%s, `visitDate`=%s, `comment`=%s, `status`=%s WHERE `blogs`.`id` = %s;"
                        , $bdd->quote($_POST['thing_id'])
                        , $bdd->quote($_POST['title'])
                        , $bdd->quote($_POST['subTitle'])
                        , $bdd->quote($_POST['visitDate'])
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

        header("Location: blogs.php?id={$_POST['thing_id']}");
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
            $blog->thing_id = 0;
            $blog->title = "";
            $blog->subTitle = "";
            $blog->visitDate = "";
            $blog->comment = "";
            $blog->status = "";
        }

        // Création du selectThing

        if ($_SESSION['droits'] > 1)
            $sql = "SELECT id,name FROM `things` ORDER BY id;";
        else
            $sql = "SELECT id,name FROM `things` where user_id = {$_SESSION['id']} ORDER BY id;";

        $stmt = $bdd->query($sql);

        $selectThing = array();
        while ($thing = $stmt->fetchObject()) {
            $selectThing[$thing->id] = $thing->name;
        }
    } catch (\PDOException $ex) {
        echo($ex->getMessage());
        return;
    }

    function afficherFormBlog($blog, $selectThing) {

        global $lang;
        // Création du tokenCSRF
        $tokenCSRF = STR::genererChaineAleatoire(32);
        $_SESSION['tokenCSRF'] = $tokenCSRF;

        echo Form::hidden('action', $blog->action);
        echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"]);

        $options = array('class' => 'form-control', 'readonly' => null);
        echo Form::input('int', 'id', $blog->id, $options, 'Id');
       

        $options = array('class' => 'form-control');
        echo Form::input('text', 'title', $blog->title, $options, $lang['title']);
        echo Form::input('text', 'subTitle', $blog->subTitle, $options, "sous-titre");
        echo Form::textarea('comment', $blog->comment, $options);
        echo Form::input('datetime-local', 'visitDate', $blog->visitDate, $options, "Date");
        echo Form::select("thing_id", $selectThing, $lang['thing'], $blog->thing_id);
        echo Form::select("status", $lang['sel_status'], $lang['status'], $blog->status);
    }

}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?= $lang['blog'] ?> - Aggregator</title>
        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="../css/bootstrap.min.css" >
        <link rel="stylesheet" href="../css/ruche.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="../scripts/bootstrap.min.js"></script>

    </head>
    <body>

<?php require_once '../menu.php'; ?>

        <div class="container-fluid" style="padding-top: 65px;">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-12">
                    <div class="popin">
                        <form class="form-horizontal" method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
<?php afficherFormBlog($blog,$selectThing );	 ?>

                            <div class="form-group">
                                </br>
                                <button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
                                <a  class="btn btn-info" role="button" href="blogs?id=<?= $blog->thing_id ?>"><?= $lang['Cancel'] ?></a>
                            </div>	
                        </form>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 col-12">
                    <div class="popin">

                    </div>
                </div>
            </div>	

<?php require_once '../piedDePage.php'; ?>
        </div>

    </body>