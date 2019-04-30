<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceHistogramCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectNoArguments()
    {
        $diceHistogram = new DiceHistogram();

        $this->assertInstanceOf("\Pamo\DiceGame\DiceHistogram", $diceHistogram);

        $diceHistogram->roll();

        $exp = 6;
        $res = $diceHistogram->getHistogramMax();
        $this->assertEquals($exp, $res);

        $exp = 0;
        $res = $diceHistogram->roll();
        $this->assertGreaterThan($exp, $res);
    }
}
