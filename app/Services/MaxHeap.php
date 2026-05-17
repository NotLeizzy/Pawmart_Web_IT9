<?php

namespace App\Services;

class HeapItem
{
    public $priority; // Numeric priority score
    public $data;     // Order Model or Object

    public function __construct($priority, $data)
    {
        $this->priority = $priority;
        $this->data = $data;
    }
}

class MaxHeap
{
    private $heap = [];

    public function insert($priority, $data)
    {
        $item = new HeapItem($priority, $data);
        $this->heap[] = $item;
        $this->heapifyUp(count($this->heap) - 1);
    }

    public function extractMax()
    {
        $count = count($this->heap);
        if ($count === 0) {
            return null;
        }

        $max = $this->heap[0];
        $last = array_pop($this->heap);

        if (count($this->heap) > 0) {
            $this->heap[0] = $last;
            $this->heapifyDown(0);
        }

        return $max->data;
    }

    public function isEmpty()
    {
        return empty($this->heap);
    }

    private function heapifyUp($index)
    {
        while ($index > 0) {
            $parentIndex = floor(($index - 1) / 2);
            if ($this->heap[$index]->priority <= $this->heap[$parentIndex]->priority) {
                break;
            }

            // Swap parent and child
            $temp = $this->heap[$index];
            $this->heap[$index] = $this->heap[$parentIndex];
            $this->heap[$parentIndex] = $temp;

            $index = $parentIndex;
        }
    }

    private function heapifyDown($index)
    {
        $count = count($this->heap);
        while (true) {
            $leftChild = 2 * $index + 1;
            $rightChild = 2 * $index + 2;
            $largest = $index;

            if ($leftChild < $count && $this->heap[$leftChild]->priority > $this->heap[$largest]->priority) {
                $largest = $leftChild;
            }

            if ($rightChild < $count && $this->heap[$rightChild]->priority > $this->heap[$largest]->priority) {
                $largest = $rightChild;
            }

            if ($largest === $index) {
                break;
            }

            // Swap
            $temp = $this->heap[$index];
            $this->heap[$index] = $this->heap[$largest];
            $this->heap[$largest] = $temp;

            $index = $largest;
        }
    }
}
