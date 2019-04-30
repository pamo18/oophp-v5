<?php

namespace Pamo\DiceGame;

class DiceHistogramMax extends Dice implements HistogramInterface
{
    use HistogramTrait;

    /**
     *
     * @return int the value of the rolled dice.
     */
    public function roll() : int
    {
        $this->serie[] = parent::roll();
        return $this->face();
    }
}
