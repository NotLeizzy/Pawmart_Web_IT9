<?php

namespace App\Services;

class BSTNode
{
    public $key;   // Product Name
    public $data;  // Product Details/Model
    public $left;
    public $right;
    public $height;

    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
        $this->left = null;
        $this->right = null;
        $this->height = 1;
    }
}

class ProductBST
{
    private $root = null;

    private function getHeight($node)
    {
        return $node === null ? 0 : $node->height;
    }

    private function getBalance($node)
    {
        return $node === null ? 0 : $this->getHeight($node->left) - $this->getHeight($node->right);
    }

    private function rotateRight($y)
    {
        $x = $y->left;
        $T2 = $x->right;

        // Perform rotation
        $x->right = $y;
        $y->left = $T2;

        // Update heights
        $y->height = max($this->getHeight($y->left), $this->getHeight($y->right)) + 1;
        $x->height = max($this->getHeight($x->left), $this->getHeight($x->right)) + 1;

        return $x;
    }

    private function rotateLeft($x)
    {
        $y = $x->right;
        $T2 = $y->left;

        // Perform rotation
        $y->left = $x;
        $x->right = $T2;

        // Update heights
        $x->height = max($this->getHeight($x->left), $this->getHeight($x->right)) + 1;
        $y->height = max($this->getHeight($y->left), $this->getHeight($y->right)) + 1;

        return $y;
    }

    public function insert($key, $data)
    {
        $this->root = $this->insertRec($this->root, $key, $data);
    }

    private function insertRec($node, $key, $data)
    {
        // 1. Perform normal BST insertion
        if ($node === null) {
            return new BSTNode($key, $data);
        }

        $compare = strcasecmp($key, $node->key);

        if ($compare < 0) {
            $node->left = $this->insertRec($node->left, $key, $data);
        } else {
            // Equal keys go to right subtree to allow duplicate product names
            $node->right = $this->insertRec($node->right, $key, $data);
        }

        // 2. Update height of this ancestor node
        $node->height = 1 + max($this->getHeight($node->left), $this->getHeight($node->right));

        // 3. Get the balance factor of this node
        $balance = $this->getBalance($node);

        // If this node becomes unbalanced, then check the 4 cases

        // Left Left Case
        if ($balance > 1 && strcasecmp($key, $node->left->key) < 0) {
            return $this->rotateRight($node);
        }

        // Right Right Case
        if ($balance < -1 && strcasecmp($key, $node->right->key) >= 0) {
            return $this->rotateLeft($node);
        }

        // Left Right Case
        if ($balance > 1 && strcasecmp($key, $node->left->key) >= 0) {
            $node->left = $this->rotateLeft($node->left);
            return $this->rotateRight($node);
        }

        // Right Left Case
        if ($balance < -1 && strcasecmp($key, $node->right->key) < 0) {
            $node->right = $this->rotateRight($node->right);
            return $this->rotateLeft($node);
        }

        return $node;
    }

    public function search($key)
    {
        $results = [];
        $this->searchRec($this->root, $key, $results);
        return $results;
    }

    private function searchRec($node, $key, &$results)
    {
        if ($node === null) {
            return;
        }

        // Substring / partial match case-insensitively
        if (stripos($node->key, $key) !== false) {
            $results[] = $node->data;
        }

        // Check both subtrees for partial matches
        $this->searchRec($node->left, $key, $results);
        $this->searchRec($node->right, $key, $results);
    }

    // Exact search in O(log N) for clean Big-O lookup time benchmarks
    public function searchExact($key)
    {
        return $this->searchExactRec($this->root, $key);
    }

    private function searchExactRec($node, $key)
    {
        if ($node === null) {
            return null;
        }

        $compare = strcasecmp($key, $node->key);

        if ($compare === 0) {
            return $node->data;
        }

        if ($compare < 0) {
            return $this->searchExactRec($node->left, $key);
        }

        return $this->searchExactRec($node->right, $key);
    }
}
