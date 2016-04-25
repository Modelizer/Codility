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
     * Slices of Numbers to evaluate smallest or shortest slice
     *
     * @var array
     */
    protected $slices = [];

    /**
     * No solution
     *
     * @type constant
     */
    const NO_SOLUTION = 'NO SOLUTION';

    /**
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
        return $this->totalEvenNumbers > 2 and $this->totalOddNumbers > 1;
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
     * @param array $oddNumbers
     * @param array $evenNumbers
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
        if (! $this->slice($this->oddNumbers)) {
            return false;
        }

        if (! $slices = $this->getSmallestSlices()) {
            return $this->slices[0];
        }

        if (count($slices) > 1) {
            return $this->getShortestSlice($slices);
        }

        return $slices[0];
    }

    /**
     * Get smallest even number
     *
     * @return string
     */
    protected function getSmallestEvenNumbers()
    {
        if (! $this->hasEvenNumbers()) {
            return static::NO_SOLUTION;
        }

        $evenNumber = current(array_keys($this->evenNumbers));

        return $this->stringify([$evenNumber, $evenNumber]);
    }

    /**
     * Slice numbers into two pocket of array
     *
     * @param array $numbers
     * @return array
     */
    protected function slice(array $numbers)
    {
        foreach ($numbers as $numberIndex => $number) {
            foreach ($numbers as $subSliceIndex => $subSliceNumber) {
                if ($number == $subSliceNumber) {
                    continue;
                }

                $this->slices[] = [
                    $subSliceIndex => $number,
                    $numberIndex   => $subSliceNumber
                ];
            }
        }

        return $this->slices;
    }

    /**
     * Get Smallest Slices
     *
     * @return array
     */
    protected function getSmallestSlices()
    {
        $smallestSliceSum = null;
        $smallestSlices   = [];

        foreach ($this->slices as $slice) {
            if ($smallestSliceSum > array_count_values($slice) or ! $smallestSliceSum) {
                $smallestSlices[] = $slice;
                $smallestSliceSum = array_count_values($slice);
            }
        }

        return $smallestSlices;
    }

    /**
     * Get slice by shortest key
     *
     * @param $slices
     * @return array
     */
    protected function getShortestSlice($slices)
    {
        // @todo Find shortest key and return that slice
        return $slices[0];
    }

    /**
     * Stringify array
     *
     * @param array $array
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
    var_dump($data);exit;
}

print_r(solution([4, 3, 5, 7, 2, 10]));