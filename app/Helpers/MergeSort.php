<?php

namespace App\Helpers;

/**
 * Class MergeSort
 *
 * Provides an implementation of merge sort for arrays.
 * This helper is useful for sorting product lists and category inventories.
 */
class MergeSort
{
    /**
     * Sort an array using merge sort.
     *
     * @param array<int|float|string> $items Unsorted array.
     * @return array<int|float|string> Sorted array.
     */
    public static function sort(array $items): array
    {
        $count = count($items);

        if ($count <= 1) {
            return $items;
        }

        $mid = intdiv($count, 2);
        $left = self::sort(array_slice($items, 0, $mid));
        $right = self::sort(array_slice($items, $mid));

        return self::merge($left, $right);
    }

    /**
     * Merge two sorted arrays.
     *
     * @param array<int|float|string> $left
     * @param array<int|float|string> $right
     * @return array<int|float|string>
     */
    protected static function merge(array $left, array $right): array
    {
        $result = [];
        $i = 0;
        $j = 0;

        while ($i < count($left) && $j < count($right)) {
            if ($left[$i] <= $right[$j]) {
                $result[] = $left[$i++];
            } else {
                $result[] = $right[$j++];
            }
        }

        while ($i < count($left)) {
            $result[] = $left[$i++];
        }

        while ($j < count($right)) {
            $result[] = $right[$j++];
        }

        return $result;
    }
}
