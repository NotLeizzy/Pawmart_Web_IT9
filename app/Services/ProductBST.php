<?php

namespace App\Services;

class BSTNode
{
    public $key;   // Product Name
    public $data;  // Product Details/Model
    public $left = null;
    public $right = null;
    public $height = 1;

    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
    }
}

class ProductBST
{
    private $root = null;

    private function height($node)
    {
        return $node === null ? 0 : $node->height;
    }

    private function getBalance($node)
    {
        return $node === null ? 0 : $this->height($node->left) - $this->height($node->right);
    }

    private function rotateRight($y)
    {
        $x = $y->left;
        $T2 = $x->right;

        // Perform rotation
        $x->right = $y;
        $y->left = $T2;

        // Update heights
        $y->height = max($this->height($y->left), $this->height($y->right)) + 1;
        $x->height = max($this->height($x->left), $this->height($x->right)) + 1;

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
        $x->height = max($this->height($x->left), $this->height($x->right)) + 1;
        $y->height = max($this->height($y->left), $this->height($y->right)) + 1;

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
            // Allow duplicate keys by inserting to right subtree
            $node->right = $this->insertRec($node->right, $key, $data);
        }

        // 2. Update height of this ancestor node
        $node->height = 1 + max($this->height($node->left), $this->height($node->right));

        // 3. Get the balance factor to check if it became unbalanced
        $balance = $this->getBalance($node);

        // If node becomes unbalanced, perform AVL rotations:

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

    /**
     * Search products by name (case-insensitive substring match)
     *
     * @param string $key
     * @return array
     */
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

        // If search term is empty, return all products in-order
        if (empty($key)) {
            $this->searchRec($node->left, $key, $results);
            $results[] = $node->data;
            $this->searchRec($node->right, $key, $results);
            return;
        }

        // Perform case-insensitive substring search
        if (stripos($node->key, $key) !== false) {
            $results[] = $node->data;
        }

        // Continue searching both subtrees for substring matches
        $this->searchRec($node->left, $key, $results);
        $this->searchRec($node->right, $key, $results);
    }

    /**
     * In-order traversal helper to get all products sorted alphabetically
     *
     * @return array
     */
    public function getInOrder()
    {
        $results = [];
        $this->inOrderRec($this->root, $results);
        return $results;
    }

    private function inOrderRec($node, &$results)
    {
        if ($node !== null) {
            $this->inOrderRec($node->left, $results);
            $results[] = $node->data;
            $this->inOrderRec($node->right, $results);
        }
    }
}
