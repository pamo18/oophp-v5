<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class RoundCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectNoArguments()
    {
        $hand = new Hand();
        $hand->setup();
        $round = new Round($hand);
        $this->assertInstanceOf("\Pamo\DiceGame\Round", $round);

        $res = $round->getRoundScore();
        $exp = 0;
        $this->assertEquals($exp, $res);

        $round->play();
        $res = $round->getRoundScore();
        $exp = 6;
        $this->assertLessThanOrEqual($exp, $res);

        $exp = 0;
        $this->assertGreaterThanOrEqual($exp, $res);
    }

    public function testCreateObjectBothArguments()
    {
        $hand = new Hand(3);
        $hand->setup(5, 5);
        $round = new Round($hand);
        $round->play();
        $this->assertInstanceOf("\Pamo\DiceGame\Round", $round);

        $res = $round->getRoundScore();
        $exp = 15;
        $this->assertEquals($exp, $res);

        $round->play();
        $exp = 18;
        $this->assertLessThanOrEqual($exp, $res);

        $exp = 0;
        $this->assertGreaterThanOrEqual($exp, $res);
    }

    public function testCreateObjectNoArgumentsPlay()
    {
        $hand = new Hand();
        $hand->setup();
        $round = new Round($hand);
        $this->assertInstanceOf("\Pamo\DiceGame\Round", $round);

        $round->play();
        $res = $round->getRoundScore();
        $exp = 0;
        $this->assertGreaterThanOrEqual($exp, $res);

        $exp = 6;
        $this->assertLessThanOrEqual($exp, $res);
    }

    public function testCreateObjectNoArgumentsPlayOne()
    {
        $hand = new Hand();
        $hand->setup(1, 1);
        $round = new Round($hand);
        $this->assertInstanceOf("\Pamo\DiceGame\Round", $round);

        $round->play();
        $res = $round->getRoundScore();
        $exp = 0;
        $this->assertEquals($exp, $res);
    }
}
