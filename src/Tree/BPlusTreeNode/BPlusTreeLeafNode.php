<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree\BPlusTreeNode;

class BPlusTreeLeafNode extends BPlusTreeNode
{
    public array $values = [];
    public ?BPlusTreeLeafNode $next = null;

    public function insert(int $key, mixed $value): BPlusTreeNode
    {
        $insertionIndex = $this->findInsertionIndex($key);

        array_splice($this->keys, $insertionIndex, 0, [$key]);
        array_splice($this->values, $insertionIndex, 0, [$value]);

        if ($this->isFull()) {
            return $this->split();
        }

        return $this;
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

    private function split(): BPlusTreeInternalNode
    {
        $middle = (int)($this->order / 2);

        $newNode = new BPlusTreeLeafNode($this->order);
        $newNode->keys = array_splice($this->keys, $middle);
        $newNode->values = array_splice($this->values, $middle);
        $newNode->next = $this->next;
        $this->next = $newNode;

        $parent = new BPlusTreeInternalNode($this->order);
        $parent->keys[] = $newNode->keys[0];
        $parent->children = [$this, $newNode];

        return $parent;
    }
}
