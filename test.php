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

echo $controller->calc("DHL", 100);


echo $controller->calc("Почта России", 100);