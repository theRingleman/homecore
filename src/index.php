<?php

require 'vendor/autoload.php';

$f3 = \Base::instance();

$f3->config('app/config.ini');

$f3->config('app/routes.ini');

$dbCreds = json_decode(file_get_contents('creds.json'));

$f3->set('DB', new DB\SQL(
    'mysql:host=homestorage;port=3306;dbname=homecore',
    "{$dbCreds->database->username}",
    "{$dbCreds->database->password}"
));

$f3->run();