<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree\BPlusTreeNode;

class BPlusTreeInternalNode extends BPlusTreeNode
{
    public array $keys = [];
    public array $children = [];

    public function insert(int $key, mixed $value): BPlusTreeNode
    {
        $insertionIndex = $this->findInsertionIndex($key);
        $this->children[$insertionIndex] = $this->children[$insertionIndex]->insert($key, $value);

        if ($this->children[$insertionIndex] instanceof BPlusTreeInternalNode) {
            $this->keys = array_merge(
                array_slice($this->keys, 0, $insertionIndex),
                $this->children[$insertionIndex]->keys,
                array_slice($this->keys, $insertionIndex)
            );
            $this->children = array_merge(
                array_slice($this->children, 0, $insertionIndex),
                $this->children[$insertionIndex]->children,
                array_slice($this->children, $insertionIndex + 1)
            );
        }

        if ($this->isFull()) {
            return $this->split();
        }

        return $this;
    }

    public function remove(mixed $key): bool
    {
        $index = $this->findInsertionIndex($key);
        return $this->children[$index]->remove($key);
    }


    public function search(mixed $key): mixed
    {
        $index = $this->findInsertionIndex($key);
        return $this->children[$index]->search($key);
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

    public function split(): BPlusTreeInternalNode
    {
        $middle = (int)($this->order / 2);

        $newNode = new BPlusTreeInternalNode($this->order);
        $newNode->keys = array_splice($this->keys, $middle + 1);
        $newNode->children = array_splice($this->children, $middle + 1);

        $parent = new BPlusTreeInternalNode($this->order);
        $parent->keys[] = $this->keys[$middle];
        $parent->children = [$this, $newNode];

        array_pop($this->keys);

        return $parent;
    }
}
