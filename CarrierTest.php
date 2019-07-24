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


    public function testEveryCalculate()
    {
        $carrier = new Сarrier();
        $carrier->setName("test");
        $carrier->setRateType("const_every_weight");
        $carrier->setPriceMinWeight(100);
        $rez = $carrier->calculateCost(9);
        $this->assertEquals(900, $rez);
    }

    public function testMinCalculate()
    {
        $carrier = new Сarrier();
        $carrier->setName("test");
        $carrier->setRateType("price_increase_after_the_limit");
        $carrier->setPriceMinWeight(1);
        $carrier->setPriceMaxWeight(1000);
        $carrier->setMinWeight(8);
        $rez = $carrier->calculateCost(9);
        $this->assertEquals(1000, $rez);
    }

    //error cases
    public function testUnknowType()
    {
        $carrier = new Сarrier();
        $carrier->setName("test");
        $carrier->setRateType("test type");
        $rez = $carrier->calculateCost(9);
        $this->assertEquals('unknown type', $rez);
    }

    public function testIncorrectWeight()
    {
        $carrier = new Сarrier();
        $carrier->setName("test");
        $carrier->setRateType("test type");
        $rez = $carrier->calculateCost("rrr");
        $this->assertEquals('incorrect weight', $rez);
    }



}