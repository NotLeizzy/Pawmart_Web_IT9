<?php

namespace App\Services;

class HashNode
{
    public $key;
    public $value;
    public $next;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
        $this->next = null;
    }
}

class CustomHashTable
{
    private $buckets;
    private $capacity;
    private $count = 0;

    public function __construct($capacity = 101)
    {
        $this->capacity = $capacity;
        $this->buckets = array_fill(0, $capacity, null);
    }

    private function getHash($key)
    {
        // Polynomial rolling hash for string keys, or standard modulo for integers
        $hash = 0;
        $strKey = (string)$key;
        $len = strlen($strKey);
        
        for ($i = 0; $i < $len; $i++) {
            $hash = ($hash * 31 + ord($strKey[$i])) % $this->capacity;
        }
        
        return $hash;
    }

    public function insert(string|int $key, $value)
    {
        $index = $this->getHash($key);
        $head = $this->buckets[$index];

        // COLLISION RESOLUTION: Chaining (Linked List)
        // If a collision occurs (multiple keys map to the same index), we traverse the linked list
        // at the index to check if the key already exists, updating its value if found.
        $current = $head;
        while ($current !== null) {
            if ($current->key == $key) {
                $current->value = $value;
                return true;
            }
            $current = $current->next;
        }

        // COLLISION RESOLUTION: If key doesn't exist, append it at the head of the chain.
        $newNode = new HashNode($key, $value);
        $newNode->next = $this->buckets[$index];
        $this->buckets[$index] = $newNode;
        $this->count++;
        return true;
    }

    public function search(string|int $key)
    {
        $index = $this->getHash($key);
        
        // COLLISION RESOLUTION: Traverse the linked list chain at this index to look up the key.
        $current = $this->buckets[$index];

        while ($current !== null) {
            if ($current->key == $key) {
                return $current->value;
            }
            $current = $current->next;
        }

        return null; // Not found
    }

    public function delete(string|int $key)
    {
        $index = $this->getHash($key);
        
        // COLLISION RESOLUTION: Traverse the linked list chain to find and remove the key.
        $current = $this->buckets[$index];
        $prev = null;

        while ($current !== null) {
            if ($current->key == $key) {
                if ($prev === null) {
                    $this->buckets[$index] = $current->next;
                } else {
                    $prev->next = $current->next;
                }
                $this->count--;
                return true;
            }
            $prev = $current;
            $current = $current->next;
        }

        return false; // Not found
    }

    public function getCount()
    {
        return $this->count;
    }
}
