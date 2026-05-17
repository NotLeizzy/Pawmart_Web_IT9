<?php

namespace App\Services;

class MergeSorter
{
    public static function sort(array $arr, $keyProperty)
    {
        if (count($arr) <= 1) {
            return $arr;
        }

        $mid = floor(count($arr) / 2);
        $left = array_slice($arr, 0, $mid);
        $right = array_slice($arr, $mid);

        $left = self::sort($left, $keyProperty);
        $right = self::sort($right, $keyProperty);

        return self::merge($left, $right, $keyProperty);
    }

    private static function merge(array $left, array $right, $keyProperty)
    {
        $result = [];
        $i = 0;
        $j = 0;

        while ($i < count($left) && $j < count($right)) {
            $valLeft = self::getValue($left[$i], $keyProperty);
            $valRight = self::getValue($right[$j], $keyProperty);

            // Compare
            if ($valLeft <= $valRight) {
                $result[] = $left[$i];
                $i++;
            } else {
                $result[] = $right[$j];
                $j++;
            }
        }

        while ($i < count($left)) {
            $result[] = $left[$i];
            $i++;
        }

        while ($j < count($right)) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }

    private static function getValue($item, $keyProperty)
    {
        if (is_array($item)) {
            return isset($item[$keyProperty]) ? $item[$keyProperty] : null;
        }
        if (is_object($item)) {
            return isset($item->$keyProperty) ? $item->$keyProperty : (method_exists($item, 'getAttribute') ? $item->getAttribute($keyProperty) : null);
        }
        return $item;
    }
}
