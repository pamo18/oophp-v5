<?php

namespace Pamo\DiceGame;

/**
 * Round for game Dice 100
 */
class Round
{
    /**
     * @var object $currentHand  The current hand object.
     * @var int $roundScore      The score for the current round.
     * @var bool $gotOne         Was a one thrown.
     * @var bool $start          Is it the start of the round.
     */
    private $currentHand;
    private $roundScore;
    private $gotOne;
    private $start;
    /**
     * Constructor to initiate the object with current hand settings,
     * if available.  Get rolled dice and add to the hand.
     *
     * @param object $currentHand  The current hand object.
     *
     */
    public function __construct(object $currentHand)
    {
        $this->currentHand = $currentHand;
        $this->roundScore = 0;
        $this->gotOne = false;
        $this->start = true;
    }

    /**
     * Setup and roll the current hand.
     *
     * @return void
     */
    public function play() : void
    {
        if ($this->start) {
            $this->start = false;
        }
        $this->currentHand->rollDice();
        $roundHand = $this->currentHand->showHand();
        if (in_array(1, $roundHand)) {
            $this->gotOne = true;
            $this->roundScore = 0;
        } else {
            $this->roundScore += $this->currentHand->sumHand();
        }
    }

    /**
     * Show the current round score.
     *
     * @return int as round score.
     */
    public function getRoundScore() : int
    {
        return $this->roundScore;
    }

    /**
     * Was a one thrown.
     *
     * @return bool as result.
     */
    public function throwGotOne() : bool
    {
        return $this->gotOne;
    }

    /**
     * Is it the start of the round.
     *
     * @return bool as result.
     */
    public function start() : bool
    {
        return $this->start;
    }
}
