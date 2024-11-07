<?php

session_start();

require_once 'vendor/autoload.php';
use iutnc\nrv\dispatch\Dispatcher;

ob_start();

$dispatcher = new Dispatcher();
try {
    $dispatcher->run();
} catch (Exception $e) {
    echo $e->getMessage();
}

ob_end_flush();