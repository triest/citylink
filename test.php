<?php

require_once "CarrieController.php";
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 24.07.2019
 * Time: 18:09
 */


$controller = new CarrieController();

if (!$controller->readCarrier()) {
    exit();
}

echo $controller->calc("DHL", 1);

echo "\n";
echo $controller->calc("Почта России", 1000);