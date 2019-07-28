<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 24.07.2019
 * Time: 22:36
 */
require_once "CarrieController.php";

use PHPUnit\Framework\TestCase;


class CarrertcontrollerTest extends TestCase
{
    public function testErrorOpenFile()
    {
        $carrierController = new CarrieController();
        $carrierController->setFilename("test");
        $this->assertNotNull($carrierController->readConnectProperty());
    }

    public function testConnetorValidate()
    {
        $carrierController = new CarrieController();
        $carrierController->setHost("");
        $this->assertEquals("empty host",
            $carrierController->connectorValidate());
        $carrierController->setHost("127.0.0.1");


        $carrierController->setDatabase("");
        $this->assertEquals("empty database",
            $carrierController->connectorValidate());

        $carrierController->setLogin("");
        $this->assertEquals("empty login",
            $carrierController->connectorValidate());
    }

    public function testreadCarrier()
    {
        $carrierController = new CarrieController();
        $this->assertEquals(true, $carrierController->readCarrier());
    }


    public function testCalculate()
    {
        $controller = new CarrieController();
        $controller->readCarrier();
        $this->assertEquals(100, $controller->calc("Почта России", 9));

        $this->assertEquals(1000, $controller->calc("Почта России", 11));

        $this->assertEquals(900, $controller->calc("DHL", 9));

        $this->assertEquals(404, $controller->calc("нет такого", 9));

        $this->assertEquals("incorrect weight", $controller->calc("DHL", "j"));
    }


}