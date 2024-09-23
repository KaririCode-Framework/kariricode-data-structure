<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree\BPlusTreeNode;

/**
 * BPlusTreeInternalNode represents an internal node in a B+ Tree.
 * Internal nodes contain keys and children pointers.
 *
 * @category  Trees
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class BPlusTreeInternalNode extends BPlusTreeNode
{
    public array $keys = [];
    public array $children = [];

    public function insert(int $key, $value): BPlusTreeNode
    {
        $index = $this->findIndex($key);
        $child = $this->children[$index];
        $newChild = $child->insert($key, $value);

        if ($newChild !== $child) {
            // Insert the new key and child into the current node
            array_splice($this->keys, $index, 0, [$newChild->keys[0]]);
            array_splice($this->children, $index + 1, 0, [$newChild]);
        }

        if ($this->isFull()) {
            return $this->split();
        }

        return $this;
    }

    private function findIndex(int $key): int
    {
        $index = 0;
        while ($index < count($this->keys) && $key >= $this->keys[$index]) {
            ++$index;
        }

        return $index;
    }

    private function split(): BPlusTreeInternalNode
    {
        $order = $this->order;
        $middleKeyIndex = (int) (($order - 1) / 2);
        $middleKey = $this->keys[$middleKeyIndex];

        // Create left and right nodes
        $leftNode = new BPlusTreeInternalNode($order);
        $rightNode = new BPlusTreeInternalNode($order);

        // Left node keys and children
        $leftNode->keys = array_slice($this->keys, 0, $middleKeyIndex);
        $leftNode->children = array_slice($this->children, 0, $middleKeyIndex + 1);

        // Right node keys and children
        $rightNode->keys = array_slice($this->keys, $middleKeyIndex + 1);
        $rightNode->children = array_slice($this->children, $middleKeyIndex + 1);

        // Create a new parent node and promote the middle key
        $parent = new BPlusTreeInternalNode($order);
        $parent->keys = [$middleKey];
        $parent->children = [$leftNode, $rightNode];

        return $parent;
    }

    public function search(mixed $key): mixed
    {
        $index = $this->findInsertionIndex($key);

        return $this->children[$index]->search($key);
    }

    public function remove(mixed $key): bool
    {
        $index = $this->findInsertionIndex($key);

        return $this->children[$index]->remove($key);
    }

    private function findInsertionIndex(mixed $key): int
    {
        $left = 0;
        $right = count($this->keys);

        while ($left < $right) {
            $mid = ($left + $right) >> 1;
            if ($this->keys[$mid] <= $key) {
                $left = $mid + 1;
            } else {
                $right = $mid;
            }
        }

        return $left;
    }
}
