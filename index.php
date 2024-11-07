<?php

// DEV MODE pour afficher des erreurs plus precises
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once 'vendor/autoload.php';
use iutnc\nrv\dispatch\Dispatcher ;
use iutnc\nrv\repository\NrvRepository ;

iutnc\nrv\repository\NrvRepository::getInstance();
$repo = NrvRepository::getInstance();

echo "hdh";