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
        // Polynomial rolling hash function for string keys, with modulo capacity.
        // This maps the key into one of the fixed buckets.
        $hash = 0;
        $strKey = (string)$key;
        $len = strlen($strKey);

        for ($i = 0; $i < $len; $i++) {
            $hash = ($hash * 31 + ord($strKey[$i])) % $this->capacity;
        }

        return $hash;
    }

    public function insert($key, $value)
    {
        $index = $this->getHash($key);
        $head = $this->buckets[$index];

        // Check for an existing key in the bucket chain. If the key exists, update the value.
        $current = $head;
        while ($current !== null) {
            if ($current->key == $key) {
                $current->value = $value;
                return true;
            }
            $current = $current->next;
        }

        // If the bucket already contains entries, chain the new node at the head.
        // This is separate chaining collision resolution: multiple keys with the same bucket index are stored in a linked list.
        $newNode = new HashNode($key, $value);
        $newNode->next = $this->buckets[$index];
        $this->buckets[$index] = $newNode;
        $this->count++;

        return true;
    }

    public function search($key)
    {
        $index = $this->getHash($key);
        $current = $this->buckets[$index];

        // Walk the linked list in the bucket until we find the matching key or run out of nodes.
        while ($current !== null) {
            if ($current->key == $key) {
                return $current->value;
            }
            $current = $current->next;
        }

        return null; // Not found
    }

    public function delete($key)
    {
        $index = $this->getHash($key);
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
