<?php

require 'vendor/autoload.php';

$f3 = \Base::instance();

$f3->config('app/config.ini');

$f3->config('app/routes.ini');

$f3->run();