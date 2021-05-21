<?php
// page d'authentification pour la partie sécurisée du site
// cette page affiche un formulaire avec deux champs (login et passe)
// Une checkbox se souvenir de moi
// et un bouton pour soumettre 

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../lang/lang.conf.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

session_start();

unset($_SESSION['id']);
unset($_SESSION['droits']);



$bdd = Api::connexionBD(BASE);

$erreur = "";
if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}

$login = "";
$loginRO = "";
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $loginRO = "readonly";
    unset($_SESSION['login']);
}

if (isset($_COOKIE['auth'])) {
    $kmsi = "checked";
} else {
    $kmsi = "";
}


// Si le formulaire a été soumis
if (isset($_POST['envoyer'])) {

    $tokenCSRF = Api::obtenir("tokenCSRF");
    $md5 = Api::obtenir("md5");
    $login = Api::obtenir("login");

    if ($tokenCSRF !== $_SESSION['tokenCSRF']) {
        Api::envoyerErreur(403, "Authorization Required", "Erreur interne token invalide !!");
    }

    try {
        $sql = sprintf("SELECT * FROM `users` WHERE `login`=%s AND `allow` = 1; ", $bdd->quote($login));
        $stmt = $bdd->query($sql);
        $utilisateur = $stmt->fetchObject();
    } catch (\PDOException $ex) {
        Api::envoyerErreur('503', 'Service Unavailable', $ex->getMessage());
    }
    // vérification des identifiants login et encrypted_password par rapport à  ceux enregistrés dans la table users



    if ($utilisateur && $login == $utilisateur->login && $md5 == $utilisateur->encrypted_password) {
        // A partir de cette ligne l'utilisateur est authentifié donc nouvelle session
        // écriture des variables de session pour cet utilisateur

        $_SESSION['last_access'] = time();
        $_SESSION['ipaddr'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['login'] = $utilisateur->login;
        $_SESSION['id'] = $utilisateur->id;
        $_SESSION['User_API_Key'] = $utilisateur->User_API_Key;
        $_SESSION['time_zone'] = $utilisateur->time_zone;
        $_SESSION['droits'] = $utilisateur->droits;
        $_SESSION['language'] = strtolower($utilisateur->language);
        $_SESSION['cookieConsent'] = $utilisateur->cookieConsent;

        // si l'index remember est présent création d'un cookie auth crypté
        if (isset($_POST['remember'])) {
            // le contenu du cookie
            $auth = $utilisateur->id . '-' . sha1($utilisateur->login . $utilisateur->User_API_Key . $_SERVER['REMOTE_ADDR']);
            $retour = setcookie("auth", $auth, time() + 3600 * 24 * NBDAY, PATH, $_SERVER["HTTP_HOST"], false, true);
        } else {
            $retour = setcookie("auth", $auth, time() - 42000, PATH, $_SERVER["HTTP_HOST"], false, true);
        }

        // mise à  jours de la date et heure de son passage dans le champ last_sign_in_at de la table users
        try {
            $sql0 = "UPDATE `users` SET `last_sign_in_at` = `current_sign_in_at`  WHERE `users`.`id` = {$utilisateur->id} ";
            $stmt0 = $bdd->query($sql0);

            $sql1 = "UPDATE `users` SET `current_sign_in_at` = CURRENT_TIMESTAMP  WHERE `users`.`id` = {$utilisateur->id} ";
            $stmt1 = $bdd->query($sql1);

            // Incrémentation du compteur de session
            $sql2 = "UPDATE `users` SET `sign_in_count` = `sign_in_count`+1 WHERE `users`.`id` = {$utilisateur->id} ";
            $stmt2 = $bdd->query($sql2);
        } catch (\PDOException $ex) {
            Api::envoyerErreur('503', 'Service Unavailable', $ex->getMessage());
        }

        // sélection de la page de retour

        if (isset($_SESSION['request_uri'])) {
            header("Location: " . $_SESSION['request_uri']);
            exit;
        } else {
            header("Location: ../accueil.php");
            exit;
        }
    } else {
        try {
            // Erreur d'identification enregistrement des informations dans la table `failed_logins`
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $sql3 = sprintf("INSERT INTO `data`.`failed_logins` (`login`, `password`, `ip_address`, `created_at`) VALUES (%s, %s, %s, CURRENT_TIMESTAMP);"
                    , $bdd->quote($login)
                    , $bdd->quote($md5)
                    , $bdd->quote($_SERVER['REMOTE_ADDR']));
            $stmt3 = $bdd->query($sql3);

            // Comptage du nombre d'erreurs lors de la dernière heure
            $sql4 = sprintf("SELECT count(*) as nb FROM `failed_logins` where `login` = %s AND `created_at` > DATE_SUB(NOW(), INTERVAL 3600 SECOND)"
                    , $bdd->quote($login));
            $stmt4 = $bdd->query($sql4);
            $res = $stmt->fetchObject();

            // Si plus de trois erreurs 
            if ($res->nb > 3) {
                $erreur = "Attention! plus de trois erreurs !!!";
                $sql5 = sprintf("UPDATE `data`.`users` SET `allow` = 0 WHERE `users`.`login` = %s"
                        , $bdd->quote($login));
                $stmt = $bdd->query($sql5);
            } else {
                $erreur = $lang['incorrect'];
            }
        } catch (\PDOException $ex) {
            Api::envoyerErreur('503', 'Service Unavailable', $ex->getMessage());
        }
    }
}

$tokenCSRF = Str::genererChaineAleatoire(25);
$_SESSION['tokenCSRF'] = $tokenCSRF;
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Aggregator : <?= $lang['Sign_in'] ?></title>
        <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/ruche.css" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="../scripts/bootstrap.min.js"></script> 


        <script  src="./authentification/login.js"></script>
    </head>
    <body>

        <?php require_once '../menu.php'; ?>

        <div class="container" style="padding-top: 65px;">


            <div class="row">

                <div  class="col-md-6 col-sm-6 col-xs-12">
                    <div class="popin">
                        <h2><?= $lang['Sign_in'] ?></h2>



                        <form method="POST"   name="form2" id="form2">

                            <input type='hidden' name='md5' />

                            <input type='hidden' name='tokenCSRF' value = "<?php echo $tokenCSRF; ?>" />

                            <div class="form-group">
                                <label for="login" class="font-weight-bold"><?= $lang['User login'] ?> :</label>
                                <input type="text"  name="login" class="form-control"  required="required" value="<?= $login ?>" <?= $loginRO ?> >
                            </div>

                            <div class="form-group">
                                <label for="password" class="font-weight-bold"><?= $lang['Password'] ?> :</label>
                                <input type="password" name="passe" class="form-control" required="required">
                            </div>

                            <p style="color: #ff0000;"><?= $erreur ?></p>

                                <div class = "form-group form-check">

                                <input type = "checkbox" name = "remember" class = "form-check-input" <?= $kmsi ?> >
                                <label for="remember" class="font-weight-bold"><?= $lang['keep_me'] ?></label>
                                </div>
                                <br />

                                <input   id="Valider" class="btn btn-primary" value="<?= $lang['Validate'] ?>" name="envoyer"   readonly size="9">		
                        </form>
                    </div>
                </div>

            </div>
        <?php require_once '../piedDePage.php'; ?>
        </div>
    </body>
</html>


