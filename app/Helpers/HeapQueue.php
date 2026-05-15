<?php

namespace App\Helpers;

/**
 * Class HeapQueue
 *
 * A min-heap based priority queue for managing prioritized orders.
 */
class HeapQueue
{
    protected array $heap = [];

    /**
     * Insert an item with a priority.
     *
     * @param mixed $item
     * @param int $priority
     * @return void
     */
    public function insert($item, int $priority): void
    {
        $this->heap[] = ['priority' => $priority, 'item' => $item];
        $this->bubbleUp(count($this->heap) - 1);
    }

    /**
     * Remove and return the highest priority item.
     *
     * @return mixed|null
     */
    public function pop()
    {
        if (empty($this->heap)) {
            return null;
        }

        $root = $this->heap[0];
        $last = array_pop($this->heap);

        if (!empty($this->heap)) {
            $this->heap[0] = $last;
            $this->bubbleDown(0);
        }

        return $root['item'];
    }

    protected function bubbleUp(int $index): void
    {
        while ($index > 0) {
            $parent = intdiv($index - 1, 2);
            if ($this->heap[$index]['priority'] >= $this->heap[$parent]['priority']) {
                break;
            }

            [$this->heap[$parent], $this->heap[$index]] = [$this->heap[$index], $this->heap[$parent]];
            $index = $parent;
        }
    }

    protected function bubbleDown(int $index): void
    {
        $count = count($this->heap);

        while (true) {
            $left = 2 * $index + 1;
            $right = 2 * $index + 2;
            $smallest = $index;

            if ($left < $count && $this->heap[$left]['priority'] < $this->heap[$smallest]['priority']) {
                $smallest = $left;
            }

            if ($right < $count && $this->heap[$right]['priority'] < $this->heap[$smallest]['priority']) {
                $smallest = $right;
            }

            if ($smallest === $index) {
                break;
            }

            [$this->heap[$index], $this->heap[$smallest]] = [$this->heap[$smallest], $this->heap[$index]];
            $index = $smallest;
        }
    }

    /**
     * Check whether the queue is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->heap);
    }
}
