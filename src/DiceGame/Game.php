<?php

namespace Pamo\DiceGame;

/**
 * Game for game Dice 100
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Game
{
    /**
     * @var array $players        All the players.
     * @var int $numOfDice        Number of dice in the game.
     * @var string $playersTurn   Who's turn is it.
     * @var int $winningThrow     The first rounds deciding highest throw value.
     * @var object $round         The current round object.
     * @var object $hand          The current hand object.
     */
    private $players;
    private $numOfDice;
    private $playersTurn;
    private $winningThrow;
    private $round;
    private $hand;

    /**
     * Constructor to initiate the object with current hand settings,
     * if available.  Get rolled dice and add to the hand.
     *
     * @param array $playerNames  All the players in the game.
     * @param int $numOfDice      The number of dice to play with.
     *
     */
    public function __construct(array $playerNames, int $numOfDice = 1)
    {
        $this->playerNames = [];
        $this->numOfDice = $numOfDice;
        $this->playersTurn = "";
        $this->winningThrow = 0;
        foreach ($playerNames as $player) {
            $this->players[$player] = 0;
        }
        $this->hand = new Hand($numOfDice);
        $this->hand->setup();
        $this->round = new Round($this->hand);
    }

    /**
     * Who starts the game.
     *
     * @return void
     */
    public function decideWhoStarts() : void
    {
        $startHand = new Hand();
        $startHand->setup();
        foreach (array_keys($this->players) as $player) {
            $startHand->rollDice();
            $diceFace = $startHand->showHand()[0];
            if ($this->winningThrow == 0 || $diceFace > $this->winningThrow) {
                $this->winningThrow = $diceFace;
                $this->playersTurn = $player;
            }
        }
    }

    /**
     * Get the winning throw value for deciding who starts, then resets it.
     *
     * @return int as winning throw value.
     */
    public function showWinningThrow() : int
    {
        $throw = $this->winningThrow;
        $this->winningThrow = 0;
        return $throw;
    }

    /**
     * Who's turn is it
     *
     * @return string as player's turn
     */
    public function showPlayersTurn() : string
    {
        return $this->playersTurn;
    }

    /**
     * Get the current player's score
     *
     * @return int as player's score.
     */
    public function showPlayerScore(string $player = "") : int
    {
        if ($player == "") {
            return $this->players[$this->playersTurn];
        } else {
            return $this->players[$player];
        }
    }

    /**
     * Get the current round score, histogram, throws and dice faces.
     *
     * @return array as round results.
     */
    public function showRound() : array
    {
        $currentRound = [
            "score" => $this->round->getRoundScore(),
            "histogram" => $this->round->showHistogram(),
            "throws" => $this->round->throws(),
            "faces" => $this->hand->showHand()
        ];

        return $currentRound;
    }

    /**
     * Play a round.
     *
     * @return void
     */
    public function playRound(string $player = "", int $difficulty = 3) : void
    {
        if ($player == "") {
            $this->round->play();
        } else if ($player == "Computer") {
            $this->computer($difficulty);
        }
    }

    /**
     * Computer plays a round
     *
     * @return null
     */
    private function computer(int $difficulty = 3)
    {
        $rand = rand(1, $difficulty);
        $this->round->play();
        $result = $this->checkScore();
        $histogram = $this->round->getHistogram();
        if ($result == "one" || $result == "win") {
            return;
        } else if ($histogram->getThrowCount() >= 4 && $histogram->getAverage() >= 3) {
            return;
        } else if ($rand == $difficulty) {
            return;
        } else {
            $this->computer($difficulty);
        }
    }

    /**
     * Change current hand.
     *
     * @return void
     */
    public function setupHand(int $start = 1, int $sides = 6) : void
    {
        $this->hand->setup($start, $sides);
    }

    /**
     * Check the results of the last throw and see if the
     * total score is 100 or higher.  Also checks if it is a new game.
     *
     * @return string as current status.
     */
    public function checkScore(int $score = -1) : string
    {
        if ($score == -1) {
            $currentRoundScore = $this->round->getRoundScore();
        } else {
            $currentRoundScore = $score;
        }

        if ($this->playersTurn == "") {
            $result = "new";
        } else if ($this->round->throws() == 0) {
            $result = "start";
        } else if ($this->round->throwGotOne()) {
            $result = "one";
        } else if ($this->players[$this->playersTurn] + $currentRoundScore >= 100) {
            $result = "win";
        } else {
            $result = "play";
        }
        return $result;
    }

    /**
     * End the round, reset round score and change player.
     *
     * @return void
     */
    public function endRound() : void
    {
        if ($this->round->throwGotOne() == false) {
            $this->players[$this->playersTurn] += $this->round->getRoundScore();
        }
        $keys = array_keys($this->players);
        $currentPlayer = array_search($this->playersTurn, $keys);
        if ($currentPlayer + 1 == count($keys)) {
            $nextPlayer = $keys[0];
        } else {
            $nextPlayer = $keys[$currentPlayer + 1];
        }
        $this->playersTurn = $nextPlayer;
        $this->setupHand();
        $this->round = new Round($this->hand);
    }
}
