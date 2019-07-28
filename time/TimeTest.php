<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 26.07.2019
 * Time: 0:08
 */

require_once "2.php";

use PHPUnit\Framework\TestCase;


class TimeTest extends TestCase
{

    static $list
        = array(
            '09:00-11:00',
            '11:00-13:00',
            '15:00-16:00',
            '17:00-20:00',
            '20:30-21:30',
            '21:30-22:30',
        );

// correct test

    public function list()
    {
        return $list
            = array(
            '09:00-11:00',
            '11:00-13:00',
            '15:00-16:00',
            '17:00-20:00',
            '20:30-21:30',
            '21:30-22:30',
        );
    }

    public function test2Validate()
    {
        //incorrect time first interval hour
        $interval = "22:10-23:10";
        $this->assertTrue(validate($interval));
    }

    public function testIncorrecMinute3Validate()
    {
        //incorrect time first interval hour
        $interval = "01:02-23:50";
        $this->assertTrue(validate($interval));
    }

//incorrect test

    public function testIncorrecHourValidate()
    {
        //incorrect time first interval hour
        $interval = "25:10-23:10";
        $this->assertFalse(validate($interval));

    }

    public function testIncorrecMinuteValidate()
    {
        //incorrect time first interval hour
        $interval = "23:70-23:10";
        $this->assertFalse(validate($interval));
    }

    public function testIncorrecMinute2Validate()
    {
        //incorrect time first interval hour
        $interval = "22:10-23:60";
        $this->assertFalse(validate($interval));
    }

    public function testValidate()
    {
        //incorrect time first interval hour
        $interval = "25:10-23:10";
        $this->assertFalse(validate($interval));
    }

    public function testValidate3()
    {
        //incorrect time first interval hour
        $interval = "22:10 23:10";
        $this->assertFalse(validate($interval));
    }

    public function testValidate4()
    {
        //incorrect time first interval hour
        $interval = "22:10-21:10";
        $this->assertFalse(validate($interval));
    }

    public function testValidate5()
    {
        //incorrect time first interval hour
        $interval = "22:10-21:09";
        $this->assertFalse(validate($interval));
    }

    public function testValidate6()
    {
        //incorrect time first interval hour
        $interval = "22:10 23:09";
        $this->assertFalse(validate($interval));
    }

    public function testValidate7()
    {
        //incorrect time first interval hour
        $interval = "2:10-23:09";
        $this->assertFalse(validate($interval));
    }


    //тест наложения
    public function testNalog1()
    {
        $list = array(
            '09:00-11:00',
            '11:00-13:00',
            '15:00-16:00',
            '17:00-20:00',
            '20:30-21:30',
            '21:30-22:30',
        );


        $count = count($list);
        $list = nalog($list, "11:10-11:20");
        $count2 = count($list);
        $this->assertEquals($count, $count2);


        $list = nalog($list, "23:10-23:20");
        $count = $count + 1;
        $count2 = count($list);
        $this->assertEquals($count, $count2);
    }

    //тест наложения
    public function testNalog3()
    {
        $list = array(
            '09:00-11:00',
            '11:00-13:00',
            '15:00-16:00',
            '17:00-20:00',
            '20:30-21:30',
            '21:30-22:30',
        );


        $count = count($list);
        nalog($list, "10:00-17:00");
        $count2 = count($list);
        $this->assertEquals($count, $count2);

    }

    //тест наложения
    public function testNalog2()
    {
        $list = array(
            '09:00-11:00',
            '11:00-13:00',
            '15:00-16:00',
            '17:00-20:00',
            '20:30-21:30',
            '21:30-22:30',
        );
        $list = nalog($list, "11:1011:20");

        $this->assertEquals(false, $list);

    }
}