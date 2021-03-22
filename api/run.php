<?php

require_once(__DIR__ . "/../definition.inc.php");
require_once(__DIR__ . "/Api.php");
require_once(__DIR__ . "/Channel.class.php");

use Aggregator\Support\Api;

if ($argc === 2) {

    $bdd = Api::connexionBD(BASE, "+00:00");
    $id = $argv[1];
    try {
        // construction de la requête SQL pour obtenir le langage et le code du script
        $sql = "SELECT * FROM `scripts` WHERE id = {$id}";
        $stmt = $bdd->query($sql);
        if ($script = $stmt->fetchObject()) {
            $code = $script->code;
            $language = $script->language;
        } else {
            echo "erreur script inconnu\n";
            return;
        }
    } catch (\PDOException $ex) {
        echo($ex->getMessage() . "\n");
        return;
    }

    // Execution du code
    if ($language === "php") {

        try {
            $result = eval($code);
            result(0);
        } catch (ParseError $e) {
            echo "line " . $e->getLine() . " : " . $e->getMessage();
            result(1);
        }
    }

    if ($language === "shell") {

        exec($code, $output, $exitcode);
        result($exitcode);
    }

    if ($language === "python") {

        $filename = __DIR__ . '/../temp/code.py';

        if (!$handle = fopen($filename, 'w+')) {
            echo "Impossible d'ouvrir le fichier ({$filename})";
            exit;
        }

        if (fwrite($handle, $code) === FALSE) {
            echo "Impossible d'écrire dans le fichier ({$filename})";
            exit;
        }

        fclose($handle);

        exec("python3 " . __DIR__ . "/../temp/code.py 2>&1", $output, $exitcode);

        unlink($filename);
        result($exitcode);
    }
} else {

    echo "usage : run 1 \n";
}

function result($output) {
    global $bdd;
    global $id;

    try {
        $sql = "UPDATE `scripts` SET `last_run_at` = now(), `return_value` = {$output} WHERE `scripts`.`id` = {$id};";
        $bdd->exec($sql);
    } catch (ParseError $e) {
        echo "line " . $e->getLine() . " : " . $e->getMessage();
    }
}
