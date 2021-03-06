<?php

namespace Pamo\DiceGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class GameCreateObjectTest extends TestCase
{
    /**
    * No arguements
    */
    public function testCreateObjectFirstArgument()
    {
        $game = new Game(["Paul", "Daniel", "Elliot", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $player = $game->showPlayersTurn();
        $res = array_search($player, ["Paul", "Daniel", "Elliot", "Hanna"]);
        $exp = 3;
        $this->assertLessThanOrEqual($exp, $res);
    }

    public function testCreateObjectSecondArgument()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $game->playRound();
        $roundResults = $game->showRound();

        $res = count($roundResults["faces"]);
        $exp = 1;
        $this->assertLessThanOrEqual($exp, $res);
    }

    public function testCreateObjectFirstArgumentRoundScore()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $roundResults = $game->showRound();
        $res = $roundResults["score"];
        $exp = 0;
        $this->assertEquals($exp, $res);

        $game->decideWhoStarts();
        $game->playRound();

        $roundResults = $game->showRound();
        $res = $roundResults["score"];
        $exp = 6;
        $this->assertLessThanOrEqual($exp, $res);

        $exp = 0;
        $this->assertGreaterThanOrEqual($exp, $res);
    }

    public function testCreateObjectFirstArgumentWinThrow()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();

        $res = $game->showWinningThrow();
        $exp = 6;
        $this->assertLessThanOrEqual($exp, $res);

        $exp = 1;
        $this->assertGreaterThanOrEqual($exp, $res);
    }

    public function testCreateObjectFirstArgumentPlayerScore()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $game->playRound();

        $res = $game->showPlayerScore("Paul");
        $exp = 0;
        $this->assertEquals($exp, $res);

        $res = $game->showPlayerScore();
        $exp = 0;
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectFirstArgumentEndRound()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $player = $game->showPlayersTurn();
        $game->playRound();
        $roundResults = $game->showRound();

        $exp = $roundResults["score"];
        $game->endRound();
        $res = $game->showPlayerScore($player);

        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectFirstArgumentEndRoundNext()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $exp = $game->showPlayersTurn();

        $game->endRound();
        $game->endRound();
        $game->endRound();
        $game->endRound();

        $res = $game->showPlayersTurn();

        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectFirstArgumentCheckScore()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $exp = "new";
        $res = $game->checkScore();

        $this->assertEquals($exp, $res);

        $game->decideWhoStarts();
        $game->setupHand(2, 6);

        $exp = "start";
        $res = $game->checkScore();
        $this->assertEquals($exp, $res);

        $game->playRound();

        $exp = "win";
        $res = $game->checkScore(100);
        $this->assertEquals($exp, $res);

        $exp = "play";
        $res = $game->checkScore(2);
        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectBothArgumentsComputer()
    {
        $game = new Game(["Computer", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $game->setupHand(2, 6);

        $game->playRound("Computer");
        $roundResults = $game->showRound();
        $res = $roundResults["throws"];
        $exp = 1;
        $this->assertGreaterThanOrEqual($exp, $res);

        $game = new Game(["Computer", "Hanna"]);
        $game->decideWhoStarts();
        $game->setupHand(3, 6);
        $game->playRound("Computer", 1);
        $roundResults = $game->showRound();
        $res = $roundResults["throws"];
        $exp = 1;
        $this->assertEquals($exp, $res);

        $game = new Game(["Computer", "Hanna"]);
        $game->decideWhoStarts();
        $game->setupHand(5, 6);
        $game->playRound("Computer", 1000);
        $roundResults = $game->showRound();
        $res = $roundResults["throws"];
        $exp = 4;
        $this->assertEquals($exp, $res);

        $game = new Game(["Computer", "Hanna"]);
        $game->decideWhoStarts();
        $game->setupHand(1, 1);
        $game->playRound("Computer");
        $exp = "one";
        $res = $game->checkScore();

        $this->assertEquals($exp, $res);
    }

    public function testCreateObjectFirstArgumentGotOne()
    {
        $game = new Game(["Paul", "Hanna"]);
        $this->assertInstanceOf("\Pamo\DiceGame\Game", $game);

        $game->decideWhoStarts();
        $game->setupHand(1, 1);
        $game->playRound();

        $exp = "one";
        $res = $game->checkScore();

        $this->assertEquals($exp, $res);
    }
}
