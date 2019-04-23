<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectNoArguments()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Pamo\DiceGame\Dice", $dice);

        $res = $dice->sides();
        $exp = 6;
        $this->assertEquals($exp, $res);

        $res = $dice->face();
        $exp = 1;
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectBothArguments()
    {
        $dice = new Dice(2, 6);
        $this->assertInstanceOf("\Pamo\DiceGame\Dice", $dice);

        $res = $dice->start();
        $exp = 2;
        $this->assertEquals($exp, $res);

        $res = $dice->sides();
        $exp = 6;
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectNoArgumentsRoll()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Pamo\DiceGame\Dice", $dice);

        $dice->roll();
        $res = $dice->face();
        $exp = 1;
        $this->assertGreaterThanOrEqual($exp, $res);

        $dice->roll();
        $res = $dice->face();
        $exp = 6;
        $this->assertLessThanOrEqual($exp, $res);
    }
}
