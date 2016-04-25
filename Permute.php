<?php

/**
 * Permutation combination without duplicacy.
 *
 * Note: Development mode, Code Not completed yet.
 * @author Mohammed Mudasir <md.hyphen@gmail.com>
 */
class Permute
{
    protected $numbers;

    protected $totalPockets;

    protected $slices = [];

    public function __construct(array $numbers, $totalPockets = 2)
    {
        $this->numbers = $numbers;

        $this->totalPockets = $totalPockets;
    }

    public function make()
    {
        foreach ($this->numbers as $numberIndex => $number) {
            foreach ($this->numbers as $subSliceNumberIndex => $subSliceNumber) {
                if ($number == $subSliceNumber) {
                    continue;
                }

                $this->slices[$number][] = [
                    $numberIndex => $number,
                    $subSliceNumberIndex => $subSliceNumber
                ];
            }
        }

        return $this->slices;
    }
}

// Example
var_dump((new Permute([3, 5, 7, 9]))->make());





