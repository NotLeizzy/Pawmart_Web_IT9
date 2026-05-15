<?php

namespace App\Helpers;

/**
 * Class BST
 *
 * A simple binary search tree implementation for product organization.
 */
class BST
{
    public ?BSTNode $root = null;

    /**
     * Insert a key and value into the tree.
     *
     * @param int|string $key
     * @param mixed $value
     * @return void
     */
    public function insert($key, $value): void
    {
        $node = new BSTNode($key, $value);

        if ($this->root === null) {
            $this->root = $node;
            return;
        }

        $current = $this->root;

        while (true) {
            if ($key < $current->key) {
                if ($current->left === null) {
                    $current->left = $node;
                    break;
                }
                $current = $current->left;
            } elseif ($key > $current->key) {
                if ($current->right === null) {
                    $current->right = $node;
                    break;
                }
                $current = $current->right;
            } else {
                $current->value = $value;
                break;
            }
        }
    }

    /**
     * Search the tree for a key.
     *
     * @param int|string $key
     * @return mixed|null
     */
    public function search($key)
    {
        $current = $this->root;

        while ($current !== null) {
            if ($key === $current->key) {
                return $current->value;
            }

            $current = $key < $current->key ? $current->left : $current->right;
        }

        return null;
    }

    /**
     * In-order traversal of the tree.
     *
     * @return array<int, mixed>
     */
    public function inOrder(): array
    {
        return $this->traverseInOrder($this->root);
    }

    protected function traverseInOrder(?BSTNode $node): array
    {
        if ($node === null) {
            return [];
        }

        return array_merge(
            $this->traverseInOrder($node->left),
            [[$node->key => $node->value]],
            $this->traverseInOrder($node->right)
        );
    }
}

class BSTNode
{
    public int|string $key;
    public $value;
    public ?BSTNode $left = null;
    public ?BSTNode $right = null;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
