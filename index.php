<?php

// DEV MODE pour afficher des erreurs plus precises
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//


session_start();

require_once 'vendor/autoload.php';
use iutnc\nrv\dispatch\Dispatcher ;
use iutnc\nrv\repository\NrvRepository ;

// NrvRepository::setConfig('src/configdb.ini'); // chemin a modif ??

$d = new Dispatcher() ;
$d->run();

?>