<?php

namespace Pamo\DiceGame;

/**
 * Dice for game Dice 100
 */
class Dice
{
    /**
     * @var int $start   Starting number of the dice.
     * @var int $sides   Number of sides on a dice.
     * @var int $face    Current dice face value.
     */
    private $start;
    private $sides;
    private $face;
    /**
     * Constructor to initiate the object with current dice settings,
     * if available. Randomize the current dice face.
     *
     * @param int $start  The starting number of the dice.
     * @param int $sides  The number of sides on the dice.
     *
     */
    public function __construct(int $start = 1, int $sides = 6)
    {
        $this->start = $start;
        $this->sides = $sides;
        $this->face = 1;
    }

    /**
     * Roll the dice and set as the current face value.
     *
     * @return void
     */
    public function roll() : void
    {
        $randomNumber = rand($this->start, $this->sides);
        $this->face = $randomNumber;
    }

    /**
     * Get starting number of the dice.
     *
     * @return int as starting number.
     */
    public function start() : int
    {
        return $this->start;
    }

    /**
     * Get number of sides.
     *
     * @return int as number of sides.
     */
    public function sides() : int
    {
        return $this->sides;
    }

    /**
     * Get current dice face.
     *
     * @return int as current dice face.
     */
    public function face() : int
    {
        return $this->face;
    }
}
