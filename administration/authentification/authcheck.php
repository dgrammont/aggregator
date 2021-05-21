<?php

session_start();
$_SESSION['request_uri'] = $_SERVER["REQUEST_URI"];

function ErrorPersonnalise($errno, $errstr, $errfile, $errline) {
    echo "Erreur numÃ©ro [$errno], ligne [$errline], du fichier $errfile :  $errstr";
    die();
}

set_error_handler("ErrorPersonnalise");


if (!isset($_SESSION['last_access']) || !isset($_SESSION['ipaddr']) || !isset($_SESSION['login'])) {
    header("Location: index.php");
    return;
}
// vérification du temps maxi pour une session (1 heure)

if (time() - $_SESSION['last_access'] > 3600) {
    unset($_SESSION['last_access']);
    unset($_SESSION['id']);
    unset($_SESSION['ipaddr']);
    $_SESSION['erreur'] = "Your session has timed out. Please log in again.";
    header("Location: index.php");
    return;
}

// vérification de l'adresse IP du client elle ne doit pas changer
if ($_SERVER['REMOTE_ADDR'] != $_SESSION['ipaddr']) {
    unset($_SESSION['last_access']);
    unset($_SESSION['id']);
    unset($_SESSION['ipaddr']);
    $_SESSION['erreur'] = "IP address changed";
    header("Location: index.php");
    return;
}

$_SESSION['last_access'] = time();
