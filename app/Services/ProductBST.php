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

    public function insert($key, $data)
    {
        $this->root = $this->insertRec($this->root, $key, $data);
    }

    private function insertRec($node, $key, $data)
    {
        if ($node === null) {
            return new BSTNode($key, $data);
        }

        $compare = strcasecmp($key, $node->key);

        if ($compare < 0) {
            $node->left = $this->insertRec($node->left, $key, $data);
        } else {
            // Equal keys are inserted into the right subtree to support duplicates.
            $node->right = $this->insertRec($node->right, $key, $data);
        }

        $node->height = 1 + max($this->getHeight($node->left), $this->getHeight($node->right));
        $balance = $this->getBalance($node);

        if ($balance > 1 && strcasecmp($key, $node->left->key) < 0) {
            return $this->rotateRight($node);
        }

        if ($balance < -1 && strcasecmp($key, $node->right->key) > 0) {
            return $this->rotateLeft($node);
        }

        if ($balance > 1 && strcasecmp($key, $node->left->key) > 0) {
            $node->left = $this->rotateLeft($node->left);
            return $this->rotateRight($node);
        }

        if ($balance < -1 && strcasecmp($key, $node->right->key) < 0) {
            $node->right = $this->rotateRight($node->right);
            return $this->rotateLeft($node);
        }

        return $node;
    }

    private function getHeight($node)
    {
        return $node ? $node->height : 0;
    }

    private function getBalance($node)
    {
        if ($node === null) {
            return 0;
        }

        return $this->getHeight($node->left) - $this->getHeight($node->right);
    }

    private function rotateRight($y)
    {
        $x = $y->left;
        $t2 = $x->right;

        $x->right = $y;
        $y->left = $t2;

        $y->height = 1 + max($this->getHeight($y->left), $this->getHeight($y->right));
        $x->height = 1 + max($this->getHeight($x->left), $this->getHeight($x->right));

        return $x;
    }

    private function rotateLeft($x)
    {
        $y = $x->right;
        $t2 = $y->left;

        $y->left = $x;
        $x->right = $t2;

        $x->height = 1 + max($this->getHeight($x->left), $this->getHeight($x->right));
        $y->height = 1 + max($this->getHeight($y->left), $this->getHeight($y->right));

        return $y;
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

    public function inOrderTraversal()
    {
        $result = [];
        $this->traverseInOrder($this->root, $result);
        return $result;
    }

    private function traverseInOrder($node, array &$result)
    {
        if ($node === null) {
            return;
        }

        $this->traverseInOrder($node->left, $result);
        $result[] = $node->data;
        $this->traverseInOrder($node->right, $result);
    }
}
