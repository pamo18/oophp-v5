<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class HistogramCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectNoArguments()
    {
        $histogram = new Histogram();

        $this->assertInstanceOf("\Pamo\DiceGame\Histogram", $histogram);

        $dice = new DiceHistogram();

        $histogram->injectData($dice);
        $res = $histogram->getThrowCount();
        $exp = 0;
        $this->assertEquals($exp, $res);

        $res = $histogram->getSerie();
        $exp = [];
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectNoArgumentsRoll()
    {
        $histogram = new Histogram();

        $this->assertInstanceOf("\Pamo\DiceGame\Histogram", $histogram);

        $dice = new DiceHistogram();

        $dice->roll();
        $dice->roll();
        $histogram->injectData($dice);

        $res = $histogram->getThrowCount();
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectNoArgumentsAvgTxt()
    {
        $histogram = new Histogram();

        $this->assertInstanceOf("\Pamo\DiceGame\Histogram", $histogram);

        $dice = new DiceHistogram();

        $dice->roll();
        $histogram->injectData($dice);

        $res = $histogram->getAverage();
        $exp = 0;
        $this->assertGreaterThan($exp, $res);

        $res = $histogram->getAsText();
        $this->assertIsString($res);
    }
}
