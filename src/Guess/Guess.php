<?php

namespace Pamo\Guess;

/**
 * Guess my number, a class supporting the game through GET, POST and SESSION.
 */
class Guess
{
    /**
     * @var int $number   The current secret number.
     * @var int $tries    Number of tries a guess has been made.
     */
    private $number;
    private $tries;

    /**
     * Constructor to initiate the object with current game settings,
     * if available. Randomize the current number if no value is sent in.
     *
     * @param int $number The current secret number, default -1 to initiate
     *                    the number from start.
     * @param int $tries  Number of tries a guess has been made,
     *                    default 6.
     */
    public function __construct(int $number = -1, int $tries = 6)
    {
        if ($number === -1) {
            $this->random();
        } else {
            $this->number = $number;
        }
        $this->tries = $tries;
    }

    /**
     * Randomize the secret number between 1 and 100 to initiate a new game.
     *
     * @return void
     */
    public function random() : void
    {
        $randomNumber = rand(1, 100);
        $this->number =  $randomNumber;
    }

    /**
     * Get number of tries left.
     *
     * @return int as number of tries made.
     */
    public function tries() : int
    {
        return $this->tries;
    }

    /**
     * Get the secret number.
     *
     * @return int as the secret number.
     */
    public function number() : int
    {
        return $this->number;
    }

    /**
     * Make a guess, decrease remaining guesses and return a string stating
     * if the guess was correct, too low or to high or if no guesses remains.
     *
     * @param int $number   The guessed number to test
     *
     * @throws GuessException when guessed number is out of bounds.
     *
     * @return string to show the status of the guess made.
     */
    public function makeGuess(int $number) : string
    {
        if ((!is_int($number) || $number < 1 || $number > 100)) {
            throw new GuessException("Out of bounds! Only numbers between 1 and 100");
        }
        $tooHigh = "Your guess of {$number} is
                    <span class='wrong'>too high!</span>";
        $tooLow = "Your guess of {$number} is
                    <span class='wrong'>too low!</span>";
        $correct = "{$number} is <span class='correct'>correct!</span>
                    <br>You guessed the number, well done!";
        if ($this->tries() > 0) {
            $this->tries -= 1;
            switch ($number) {
                case $number > $this->number:
                    $result = $tooHigh;
                    break;
                case $number < $this->number:
                    $result = $tooLow;
                    break;
                case $number === $this->number:
                    $result = $correct;
                    $this->tries = 0;
                    break;
            }
        } else {
            $result = "Sorry, no more tries left!";
        }
        return $result;
    }
}
