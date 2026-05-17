<?php

namespace App\Services;

class BSTNode
{
    public $key;   // Product Name
    public $data;  // Product Details/Model
    public $left;
    public $right;

    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
}

class ProductBST
{
    private $root = null;

    public function insert($key, $data)
    {
        $this->root = $this->insertRec($this->root, $key, $data);
    }

    private function insertRec($node, $key, $data)
    {
        if ($node === null) {
            return new BSTNode($key, $data);
        }

        // Compare keys (case-insensitive string comparison)
        $compare = strcasecmp($key, $node->key);

        if ($compare < 0) {
            $node->left = $this->insertRec($node->left, $key, $data);
        } else {
            // Equal keys will go to the right subtree to allow duplicates
            $node->right = $this->insertRec($node->right, $key, $data);
        }

        return $node;
    }

    public function search($key)
    {
        return $this->searchRec($this->root, $key);
    }

    private function searchRec($node, $key)
    {
        if ($node === null) {
            return null;
        }

        $compare = strcasecmp($key, $node->key);

        if ($compare === 0) {
            return $node->data;
        }

        if ($compare < 0) {
            return $this->searchRec($node->left, $key);
        }

        return $this->searchRec($node->right, $key);
    }
}
