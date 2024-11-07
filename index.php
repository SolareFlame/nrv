<?php

// DEV MODE pour afficher des erreurs plus precises
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//


session_start();

require_once 'vendor/autoload.php';
use iutnc\nrv\dispatch\Dispatcher;

ob_start();
session_start();
iutnc\nrv\repository\NrvRepository::getInstance();
$repo = NrvRepository::getInstance();

$dispatcher = new Dispatcher();

try {
    $dispatcher->run();
} catch (Exception $e) {
    http_response_code(500);
    echo "Erreur 500 : " . $e->getMessage();
}

ob_end_flush();