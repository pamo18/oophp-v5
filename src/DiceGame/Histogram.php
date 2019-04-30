<?php

namespace Pamo\DiceGame;

/**
 * Generating histogram data.
 */
class Histogram
{
    /**
     * @var array $serie  The numbers stored in sequence.
     * @var int   $min    The lowest possible number.
     * @var int   $max    The highest possible number.
     */
    private $serie = [];
    private $min;
    private $max;

    /**
     * Clear the serie.
     *
     * @return void
     */
    public function clear()
    {
        $this->serie = [];
    }

    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Get the serie count.
     *
     * @return int with the serie count.
     */
    public function getThrowCount()
    {
        return count($this->serie);
    }

    /**
     * Get the serie average.
     *
     * @return float with the serie average.
     */
    public function getAverage()
    {
        return round(array_sum($this->serie) / count($this->serie), 2);
    }

    /**
     * Return a string with a textual representation of the histogram.
     *
     * @return string representing the histogram.
     */
    public function getAsText()
    {
        $stats = array_count_values($this->serie);
        if ($this->min && $this->max) {
            for ($c = $this->min; $c <= $this->max; $c++) {
                if (!isset($stats[$c])) {
                    $stats[$c] = 0;
                }
            }
        }

        ksort($stats);
        $avg = "Average: " . ($this->serie ? $this->getAverage() : " - ");
        $cnt = "Count: " . ($this->serie ? $this->getThrowCount() : " - ");
        $result = '<p class="center hist-header">' . $avg . ' / ' . $cnt . '</p>';

        foreach ($stats as $number => $count) {
            $hist = "";
            $hist .= '<p class="left">' . strval($number) . ')  ';
            for ($i = 0; $i < $count; $i++) {
                $hist .= '<i class="fas fa-circle"></i> ';
            }
            $hist .= "</p>";
            $result .= $hist;
        }
        return $result;
    }

    /**
     * Inject the object to use as base for the histogram data.
     *
     * @param HistogramInterface $object The object holding the serie.
     *
     * @return void.
     */
    public function injectData(HistogramInterface $object)
    {
        foreach ($object->getHistogramSerie() as $diceHistogram) {
            array_push($this->serie, $diceHistogram);
        }
        $this->min   = $object->getHistogramMin();
        $this->max   = $object->getHistogramMax();
    }
}
