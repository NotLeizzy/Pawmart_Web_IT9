<?php

namespace App\Services;

class DsaBenchmarkService
{
    public static function runEvaluation()
    {
        $testSizes = [100, 500, 1000];
        $results = [];

        foreach ($testSizes as $size) {
            // Generate mock data for the current size
            $mockData = [];
            for ($i = 0; $i < $size; $i++) {
                $mockData[] = [
                    'id' => $i + 1,
                    'name' => "Product_" . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'price' => rand(10, 10000) / 100.0,
                    'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                    'total_amount' => rand(50, 5000),
                ];
            }

            // 1. Measure BST Search (AVL Tree)
            $bst = new ProductBST();
            foreach ($mockData as $item) {
                $bst->insert($item['name'], $item);
            }
            $searchTarget = "Product_" . str_pad(floor($size / 2), 5, '0', STR_PAD_LEFT);
            $bstStart = hrtime(true);
            $runs = 100;
            for ($r = 0; $r < $runs; $r++) {
                $bst->search($searchTarget);
            }
            $bstEnd = hrtime(true);
            $bstTime = (($bstEnd - $bstStart) / 1_000_000.0) / $runs; // average ms per search

            // 2. Measure Priority Queue (MaxHeap) Insert & Extract
            $heapStart = hrtime(true);
            $heap = new MaxHeap();
            foreach ($mockData as $item) {
                $priority = rand(1, 1000);
                $heap->insert($priority, $item);
            }
            while (!$heap->isEmpty()) {
                $heap->extractMax();
            }
            $heapEnd = hrtime(true);
            $heapTime = ($heapEnd - $heapStart) / 1_000_000.0; // total ms for N items

            // 3. Measure Merge Sort
            $sortStart = hrtime(true);
            MergeSorter::sort($mockData, 'price');
            $sortEnd = hrtime(true);
            $sortTime = ($sortEnd - $sortStart) / 1_000_000.0;

            // 4. Measure Hash Table Lookup
            $hashTable = new CustomHashTable($size * 2 + 1);
            foreach ($mockData as $item) {
                $hashTable->insert($item['id'], $item);
            }
            $lookupTarget = floor($size / 2);
            $hashStart = hrtime(true);
            for ($r = 0; $r < $runs; $r++) {
                $hashTable->search($lookupTarget);
            }
            $hashEnd = hrtime(true);
            $hashTime = (($hashEnd - $hashStart) / 1_000_000.0) / $runs;

            $results[$size] = [
                'bst_time' => $bstTime,
                'hash_time' => $hashTime,
                'heap_time' => $heapTime,
                'sort_time' => $sortTime,
            ];
        }

        return $results;
    }
}
