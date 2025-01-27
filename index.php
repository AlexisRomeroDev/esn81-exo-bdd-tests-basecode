<?php

require './params.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use App\EmailController;

$pdo = new \PDO('mysql:host=localhost;dbname=ESN81', 'alex', 'alex');
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$controller = new EmailController($pdo);

$response = $controller->displayForm();

$response->send();
