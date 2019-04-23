<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class HandCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectNoArguments()
    {
        $hand = new Hand();
        $hand->setup();
        $this->assertInstanceOf("\Pamo\DiceGame\Hand", $hand);

        $res = count($hand->showHand());
        $exp = 1;
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectNoArgumentsRoll()
    {
        $hand = new Hand();
        $this->assertInstanceOf("\Pamo\DiceGame\Hand", $hand);

        $hand->setup();
        $hand->rollDice();
        $res = count($hand->showHand());
        $exp = 1;
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectNoArgumentsShowHand()
    {
        $hand = new Hand();
        $this->assertInstanceOf("\Pamo\DiceGame\Hand", $hand);

        $hand->setup();
        $hand->rollDice();
        $res = $hand->showHand()[0];
        $exp = 1;
        $this->assertGreaterThanOrEqual($exp, $res);

        $hand->rollDice();
        $res = $hand->showHand()[0];
        $exp = 6;
        $this->assertLessThanOrEqual($exp, $res);
    }

    public function testCreateObjectArgumentsSumHand()
    {
        $hand = new Hand();
        $this->assertInstanceOf("\Pamo\DiceGame\Hand", $hand);

        $hand->setup(5, 5);
        $hand->rollDice();
        $res = $hand->sumHand();
        $exp = 5;
        $this->assertEquals($exp, $res);
    }
}
