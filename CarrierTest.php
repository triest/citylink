<?php

use PHPUnit\Framework\TestCase;

require_once "Сarrier.php";

class CarrierTest extends TestCase
{
    public function testName()
    {
        $carrier = new Сarrier();
        $carrier->setName("Почта");
        $this->assertEquals(
            "Почта",
            $carrier->getName()
        );
    }

    public function testType()
    {
        $carrier = new Сarrier();
        $carrier->setType("Почта");
        $this->assertEquals(
            "Почта",
            $carrier->getType()
        );
    }

    public function testWeight()
    {
        $carrier = new Сarrier();
        $carrier->setMinWeight(100);
        $this->assertEquals(
            100,
            $carrier->getMinWeight()
        );
    }

    public function testPriceMaxWeight()
    {
        $carrier = new Сarrier();
        $carrier->setPriceMaxWeight(100);
        $this->assertEquals(
            100,
            $carrier->getPriceMaxWeight()
        );
    }

    public function testGetRez()
    {
        $carrier = new Сarrier();
        $carrier->setRez(100);
        $this->assertEquals(
            100,
            $carrier->getRez()
        );
    }

}