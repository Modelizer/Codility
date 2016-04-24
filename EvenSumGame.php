<?php

/**
 * Even Sum Game
 *
 * @author Mohammed Mudasir <md.hyphen@gmail.com>
 */
class EvenSumGame
{
    /**
     * Total Numbers which carry both even and odd.
     *
     * @type array
     */
    protected $numbers = [];

    /**
     * Even Numbers form $numbers variable
     *
     * @type array
     */
    protected $evenNumbers = [];

    /**
     * Odd Numbers form $numbers variable
     *
     * @type array
     */
    protected $oddNumbers = [];

    /**
     * Total Even Numbers
     *
     * @type int
     */
    protected $totalEvenNumbers = 0;

    /**
     * Total Odd Numbers
     *
     * @type int
     */
    protected $totalOddNumbers = 0;
    /**
     * Total Even pockets(keys) which is extracted from $number variable.
     *
     * @type array
     */
    protected $evenNumberPockets = [];

    /**
     * Total Odd pockets(keys) which is extracted from $number variable.
     *
     * @type array
     */
    protected $oddNumberPockets = [];

    /**
     * No solution or 100% winning chance is not possible.
     *
     * @type constant
     */
    const NO_SOLUTION = 'NO SOLUTION';

    /**
     * Construct
     *
     * @param array $numbers
     */
    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * Next best possible move
     *
     * @return string Pockets of $numbers array
     */
    public function nextMove()
    {
        $this->evaluateNumbers();

        if (! $this->hasValidSolution()) {
            return static::NO_SOLUTION;
        }

        if ($pockets = $this->getOddNumberPockets()) {
            return $pockets;
        }

        return $this->getSmallestEvenNumbers();
    }

    /**
     *
     *
     * @return bool
     */
    protected function hasValidSolution()
    {
        return $this->oddTurnsRemaining() + $this->evenTurnsRemaining() === 1;
    }

    /**
     * Run Evalution on numbers
     *
     * @return bool
     */
    protected function evaluateNumbers()
    {
        list($oddNumbers, $evenNumbers) = $this->splitOddEven();

        $oddPockets  = array_keys($oddNumbers);
        $evenPockets = array_keys($evenNumbers);

        asort($oddPockets);
        asort($evenPockets);

        // key not require in pocket case
        $this->evenNumberPockets = array_values($evenPockets);
        $this->oddNumberPockets  = array_values($oddPockets);

        $this->evenNumbers = $evenNumbers;
        $this->oddNumbers  = $oddNumbers;

        $this->totalEvenNumbers = count($evenNumbers);
        $this->totalOddNumbers  = count($oddNumbers);

        return true;
    }

    /**
     * Split odd and even
     *
     * @return array
     */
    private function splitOddEven($oddNumbers = [], $evenNumbers = [])
    {
        foreach ($this->numbers as $key => $value) {
            if ($value % 2 === 0) {
                $evenNumbers[$key] = $value;
            } else {
                $oddNumbers[$key] = $value;
            }
        }

        // Sorting minimum to maximum value
        asort($oddNumbers); asort($evenNumbers);

        return [$oddNumbers, $evenNumbers];
    }

    /**
     * Get odd number pockets
     *
     * @return string|bool
     */
    protected function getOddNumberPockets()
    {
        if (! $this->hasOddNumbers()) {
            return false;
        }

        $pockets = $this->evaluateSmallestOddSumIsEven();

        return $this->stringify($pockets);
    }

    protected function evaluateSmallestOddSumIsEven()
    {
        if ($this->totalOddNumbers > 2) {
            return $this->getSmallestShortestOddSlice();
        }

        return [
            $this->oddNumberPockets[0],
            end($this->oddNumberPockets)
        ];
    }

    protected function getSmallestShortestOddSlice()
    {
        $smallestSlices = null;
        $smallestSlice  = [];

        foreach (array_chunk($this->oddNumbers, 2, true) as $slice) {

            if ((! $slice) or count($slice) == 1) {
                continue;
            }

            if (! $smallestSlices) {
                $smallestSlices[] = $slice;
            }

            if (array_count_values($slice) < array_count_values(end($smallestSlices))) {
                $smallestSlices[] = $slice;
            }
        }

        if (count($smallestSlices) > 1) {
            foreach ($smallestSlices as $index => $currentSmallestSlice) {
                if (array_count_values($smallestSlice) > array_count_values($currentSmallestSlice)
                    or array_count_values($smallestSlice) === 0) {
                    $smallestSlice = $currentSmallestSlice;
                }
            }
        } else {
            $smallestSlice = $smallestSlices[0];
        }

        $smallestSlice = array_keys($smallestSlice);
        asort($smallestSlice);

        return $smallestSlice;
    }

    /**
     * Get smallest even number
     *
     * @return string
     */
    protected function getSmallestEvenNumbers()
    {
        $evenNumbers = $this->evenNumbers;

        if (! $this->hasEvenNumbers()) {
            return static::NO_SOLUTION;
        }

        $evenNumber = current((array_keys($this->evenNumbers)));

        return $this->stringify([$evenNumber, $evenNumber]);
    }

    /**
     * Stringify array
     *
     * @return array $array
     */
    private function stringify(array $array)
    {
        asort($array);

        return implode(',', $array);
    }

    private function oddTurnsRemaining()
    {
        return ($this->totalOddNumbers / 2) % 2;
    }

    private function evenTurnsRemaining()
    {
        return $this->totalEvenNumbers % 2;
    }

    private function hasOddNumbers()
    {
        return $this->totalOddNumbers > 1;
    }

    private function hasEvenNumbers()
    {
        return $this->totalEvenNumbers > 0;
    }
}


function solution(array $numbers)
{
    $game = new EvenSumGame($numbers);

    return $game->nextMove();
}

function dd($data)
{
    var_dump($data);
}