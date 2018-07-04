<?php

define('PROJECT_PATH', realpath('../'));
define('DEPLOY_PATH', realpath(__DIR__));

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__.'/../vendor/autoload.php';

$classes = [
    __DIR__ . '/include/DeployCreateCommand.php'
];

foreach($classes as $class) {
    require_once "$class";
}