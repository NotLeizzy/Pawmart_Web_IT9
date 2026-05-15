<?php

namespace App\Helpers;

/**
 * Class BinarySearch
 *
 * Provides a static method for performing binary search on sorted arrays.
 * This helper can be used for fast lookups in ordered product lists.
 */
class BinarySearch
{
    /**
     * Search a sorted array for a given value.
     *
     * @param array<int|float|string> $items Sorted array of comparable values.
     * @param int|float|string $target Value to search for.
     * @return int|null Index of the target if found, otherwise null.
     */
    public static function search(array $items, $target): ?int
    {
        $low = 0;
        $high = count($items) - 1;

        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            if ($items[$mid] === $target) {
                return $mid;
            }

            if ($items[$mid] < $target) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        return null;
    }
}
