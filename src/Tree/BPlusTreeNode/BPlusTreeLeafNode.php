<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree\BPlusTreeNode;

/**
 * BPlusTreeLeafNode represents a leaf node in a B+ Tree.
 * Leaf nodes contain keys, values, and pointers to the next leaf node.
 *
 * @category  Data Structures
 */
class BPlusTreeLeafNode extends BPlusTreeNode
{
    public array $values = [];
    public ?BPlusTreeLeafNode $next = null;

    public function insert(int $key, $value): BPlusTreeNode
    {
        $index = $this->findIndex($key);

        array_splice($this->keys, $index, 0, [$key]);
        array_splice($this->values, $index, 0, [$value]);

        if ($this->isFull()) {
            return $this->split();
        }

        return $this;
    }

    private function findIndex(int $key): int
    {
        $index = 0;
        while ($index < count($this->keys) && $key > $this->keys[$index]) {
            ++$index;
        }

        return $index;
    }

    private function split(): BPlusTreeInternalNode
    {
        $numKeys = count($this->keys);
        $middleIndex = intdiv($numKeys, 2);
        $newNode = new BPlusTreeLeafNode($this->order);

        $newNode->keys = array_slice($this->keys, $middleIndex);
        $newNode->values = array_slice($this->values, $middleIndex);

        $this->keys = array_slice($this->keys, 0, $middleIndex);
        $this->values = array_slice($this->values, 0, $middleIndex);

        $newNode->next = $this->next;
        $this->next = $newNode;

        $parent = new BPlusTreeInternalNode($this->order);
        $parent->keys = [$newNode->keys[0]];
        $parent->children = [$this, $newNode];

        return $parent;
    }

    public function remove(mixed $key): bool
    {
        $index = $this->findInsertionIndex($key);
        if ($index < count($this->keys) && $this->keys[$index] === $key) {
            array_splice($this->keys, $index, 1);
            array_splice($this->values, $index, 1);

            return true;
        }

        return false;
    }

    public function search(mixed $key): mixed
    {
        $index = $this->findInsertionIndex($key);
        if ($index < count($this->keys) && $this->keys[$index] === $key) {
            return $this->values[$index];
        }

        return null;
    }

    private function findInsertionIndex(mixed $key): int
    {
        $left = 0;
        $right = count($this->keys);

        while ($left < $right) {
            $mid = ($left + $right) >> 1;
            if ($this->keys[$mid] < $key) {
                $left = $mid + 1;
            } else {
                $right = $mid;
            }
        }

        return $left;
    }
}
