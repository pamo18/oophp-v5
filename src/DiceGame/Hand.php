<?php

namespace Pamo\DiceGame;

/**
 * Hand for game Dice 100
 */
class Hand
{
    /**
     * @var object $currentHand  Array of all dice objects.
     * @var array $numOfDice    The number of dice in the game.
     */
    private $currentHand;
    private $numOfDice;
    /**
     * Constructor to initiate the object with current hand settings,
     * if available.  Get the number of dice to play and setup the current hand.
     *
     * @param int $numOfDice  The number dice in the game.
     *
     */
    public function __construct(int $numOfDice = 1)
    {
        $this->currentHand = [];
        $this->numOfDice = $numOfDice;
    }

    /**
     * Roll the dice in the current hand.
     *
     * @return void
     */
    public function setup(int $start = 1, int $sides = 6) : void
    {
        $this->currentHand = [];
        for ($i = 0; $i < $this->numOfDice; $i++) {
            $newDice = new DiceHistogram($start, $sides);
            array_push($this->currentHand, $newDice);
        }
    }

    /**
     * Roll the dice in the current hand.
     *
     * @return void
     */
    public function rollDice() : void
    {
        foreach ($this->currentHand as $dice) {
            $dice->roll();
        }
    }

    /**
     * Show the dice objects.
     *
     * @return array as the current hand dice objects.
     */
    public function showDice() : array
    {
        return $this->currentHand;
    }

    /**
     * Show the dice faces in the current hand.
     *
     * @return array as the current hand dice faces.
     */
    public function showHand() : array
    {
        $diceFaces = [];
        foreach ($this->currentHand as $dice) {
            array_push($diceFaces, $dice->face());
        }
        return $diceFaces;
    }

    /**
     * Sum the dice faces in the current hand.
     *
     * @return int as the current hand sum.
     */
    public function sumHand() : int
    {
        return array_sum($this->showHand());
    }
}
