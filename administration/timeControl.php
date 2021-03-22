<?php
include "authentification/authcheck.php";

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../api/Form.php');
require_once('../lang/lang.conf.php');
require_once('../api/CronManager.class.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\Form;
use Aggregator\Support\CronManager;

// connexion à la base

$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
// Création d'un cron_manager
$cron_manager = new CronManager();

//------------si des données  sont soumises on les enregistre dans la table data.timeControls ---------
if (!empty($_POST['envoyer'])) {
    if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) {
        try {
            if (isset($_POST['action']) && ($_POST['action'] == 'insert')) {
                $sql = sprintf("INSERT INTO `data`.`timeControls` (`user_id`, `name`, `month`, `dayWeek`, `dayMonth`, `hour`, `minute`, `actionable_id`, `actionable_type`) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s);"
                        , $_POST['user_id']
                        , $bdd->quote($_POST['name'])
                        , $bdd->quote($_POST['month'])
                        , $bdd->quote($_POST['dayWeek'])
                        , $bdd->quote($_POST['dayMonth'])
                        , $bdd->quote($_POST['hour'])
                        , $bdd->quote($_POST['minute'])
                        , $bdd->quote($_POST['actionable_id'])
                        , $bdd->quote($_POST['actionable_type'])
                );
                $bdd->exec($sql);
                $id = $bdd->lastInsertId();
                // Création du crontab
                $ligne = "{$_POST['minute']} {$_POST['hour']} {$_POST['dayMonth']} {$_POST['month']} {$_POST['dayWeek']} ";
                $ligne .= "/usr/bin/php " . __DIR__ . "/../api/run.php {$_POST['actionable_id']} > /dev/null 2>&1";

                $result = $cron_manager->add_cronjob($ligne, $id);
            }

            if (isset($_POST['action']) && ($_POST['action'] == 'update')) {
                $sql = sprintf("UPDATE `data`.`timeControls` SET `user_id` = %s, `name` = %s, `month` = %s, `dayWeek` = %s, `dayMonth` = %s, `hour` = %s, `minute` = %s, `actionable_id` = %s, `actionable_type` = %s   WHERE `timeControls`.`id` = %s;"
                        , $_POST['user_id']
                        , $bdd->quote($_POST['name'])
                        , $bdd->quote($_POST['month'])
                        , $bdd->quote($_POST['dayWeek'])
                        , $bdd->quote($_POST['dayMonth'])
                        , $bdd->quote($_POST['hour'])
                        , $bdd->quote($_POST['minute'])
                        , $bdd->quote($_POST['actionable_id'])
                        , $bdd->quote($_POST['actionable_type'])
                        , $_POST['id']
                );
                $bdd->exec($sql);
                // Modification du crontab
                $ligne = "{$_POST['minute']} {$_POST['hour']} {$_POST['dayMonth']} {$_POST['month']} {$_POST['dayWeek']} ";
                $ligne .= "/usr/bin/php " . __DIR__ . "/../api/run.php {$_POST['actionable_id']} > /dev/null 2>&1";
                $result = $cron_manager->remove_cronjob($_POST['id']);
                $result = $cron_manager->add_cronjob($ligne, $_POST['id']);
            }
        } catch (\PDOException $ex) {
            echo($ex->getMessage());
            return;
        }

        // destruction du tokenCSRF
        unset($_SESSION['tokenCSRF']);

        header("Location: timeControls.php");
        return;
    }
}

// -------------- sinon lecture de la table data.timeControls  -----------------------------
else {
    if (isset($_GET['id'])) {
        try {
            $sql = sprintf("SELECT * FROM `timeControls` WHERE `id`=%s", $bdd->quote($_GET['id']));
            $stmt = $bdd->query($sql);
            if ($timeControl = $stmt->fetchObject()) {
                $timeControl->action = 'update';
            }
        } catch (\PDOException $ex) {
            echo($ex->getMessage());
            return;
        }
    } else {
        // Création d'un nouvel objet timeControl par défault
        $timeControl = new stdClass();
        $timeControl->action = 'insert';
        $timeControl->id = 0;
        $timeControl->user_id = $_SESSION['id'];
        $timeControl->name = "";
        $timeControl->month = "*";
        $timeControl->dayWeek = "*";
        $timeControl->dayMonth = "*";
        $timeControl->hour = "*";
        $timeControl->minute = "*";
        $timeControl->actionable_id = "0";
        $timeControl->actionable_type = "scripts";
    }


    try {
        // Création du $selectUser
        $sql = "SELECT id,login FROM users ORDER BY id;";
        $stmt = $bdd->query($sql);

        $selectUser = array();
        while ($user = $stmt->fetchObject()) {
            $selectUser[$user->id] = $user->login;
        }

        // Création du $select_actionable_id
        $select_actionable_id = array();
        if ($_SESSION['droits'] > 1)
            $sql = "SELECT id,name FROM {$timeControl->actionable_type} ORDER BY id;";
        else
            $sql = "SELECT id,name FROM {$timeControl->actionable_type} where user_id = {$_SESSION['id']} ORDER BY id;";
        $stmt = $bdd->query($sql);

        while ($thingHttp = $stmt->fetchObject()) {
            $select_actionable_id[$thingHttp->id] = $thingHttp->name;
        }
    } catch (\PDOException $ex) {
        echo($ex->getMessage());
        return;
    }
}

function afficherFormTimeControl($timeControl, $selectUser) {

    global $lang;
    global $select_actionable_id;
    // Création du tokenCSRF
    $tokenCSRF = STR::genererChaineAleatoire(32);
    $_SESSION['tokenCSRF'] = $tokenCSRF;

    echo Form::hidden('action', $timeControl->action);
    echo Form::hidden('id', $timeControl->id);
    echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"]);

    if ($_SESSION['droits'] > 1) //  un selecteur pour les administrateur
        echo Form::select("user_id", $selectUser, $lang['user'], $timeControl->user_id);
    else
        echo Form::hidden("user_id", $timeControl->user_id);
    $options = array('class' => 'form-control');
    echo Form::input('text', 'name', $timeControl->name, $options, $lang['name']);
    echo Form::input('text', 'minute', $timeControl->minute, $options, 'minute');
    echo Form::input('text', 'hour', $timeControl->hour, $options, $lang['hour']);
    echo Form::input('text', 'dayMonth', $timeControl->dayMonth, $options, $lang['dayMonth']);
    echo Form::input('text', 'month', $timeControl->month, $options, $lang['month']);
    echo Form::input('text', 'dayWeek', $timeControl->dayWeek, $options, $lang['dayWeek']);
    echo Form::select("actionable_type", $lang['sel_actionable_type'], $lang['actionable_type'], $timeControl->actionable_type);
    echo Form::select("actionable_id", $select_actionable_id, "perform", $timeControl->actionable_id);
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?= $lang['timeControls'] ?> - Aggregator</title>
        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="../css/bootstrap.min.css" >
        <link rel="stylesheet" href="../css/ruche.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="../scripts/bootstrap.min.js"></script>
        <script>

            $(function () {
                $("form").submit(function () {
                    let valeur = $("[name='minute']").val();
                    console.log(valeur);
                    return false;
                });

            });

        </script>

    </head>
    <body>

        <?php require_once '../menu.php'; ?>

        <div class="container-fluid" style="padding-top: 65px;">

            <div class="row">
                <div class="col-md-5 col-sm-12 col-12">
                    <div class="popin">
                        <form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >

                            <?php afficherFormTimeControl($timeControl, $selectUser); ?>

                            <div class="form-group">
                                </br>
                                <button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > <?= $lang['Apply'] ?></button>
                                <a  class="btn btn-info" role="button" href="timeControls"><?= $lang['Cancel'] ?></a>
                            </div>	
                        </form>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12 col-12">

                    <div class="popin">
                        <?= $lang['time_control_aide'] ?>				
                    </div>
                </div>
            </div>

        </div>

        <?php require_once '../piedDePage.php'; ?>
    </div>

</body>


