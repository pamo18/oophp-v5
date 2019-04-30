<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class HistogramTraitCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectNoArguments()
    {
        $dice = new DiceHistogramMax();

        $dice->roll();

        $exp = 0;
        $res = $dice->getHistogramMax();
        $this->assertGreaterThan($exp, $res);
    }
}
