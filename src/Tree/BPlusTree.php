<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree;

use KaririCode\Contract\DataStructure\Structural\BPlusTreeCollection;
use KaririCode\Contract\DataStructure\Structural\Collection;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeInternalNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeLeafNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeNode;

class BPlusTree implements BPlusTreeCollection
{
    private int $order;
    private ?BPlusTreeNode $root;
    private int $size = 0;

    public function __construct(int $order)
    {
        if ($order < 3) {
            throw new \InvalidArgumentException("Order must be at least 3");
        }
        $this->order = $order;
        $this->root = null;
    }

    public function add(mixed $element): void
    {
        $this->insert($element, $element);
    }

    public function insert(int $index, mixed $value): void
    {
        if ($this->root === null) {
            $this->root = new BPlusTreeLeafNode($this->order);
        }

        $this->root = $this->root->insert($index, $value);

        if ($this->root->isFull()) {
            $newRoot = new BPlusTreeInternalNode($this->order);
            $newRoot->children[] = $this->root;
            $this->root = $newRoot->split();
        }

        $this->size++;
    }

    public function remove(mixed $element): bool
    {
        if ($this->root === null) {
            return false;
        }

        $result = $this->root->remove($element);
        if ($result) {
            $this->size--;
            if ($this->root instanceof BPlusTreeInternalNode && count($this->root->keys) === 0) {
                $this->root = $this->root->children[0];
            }
        }

        return $result;
    }

    public function clear(): void
    {
        $this->root = null;
        $this->size = 0;
    }

    public function contains(mixed $element): bool
    {
        return $this->find($element) !== null;
    }

    public function find(mixed $element): mixed
    {
        if ($this->root === null) {
            return null;
        }
        if (is_int($element)) {
            return $this->root->search($element);
        } else {
            return $this->searchByValue($element);
        }
    }

    private function searchByValue(mixed $value): ?int
    {
        $current = $this->getLeftmostLeaf();
        while ($current !== null) {
            foreach ($current->values as $index => $nodeValue) {
                if ($nodeValue === $value) {
                    return $current->keys[$index];
                }
            }
            $current = $current->next;
        }
        return null;
    }

    public function get(int $index): mixed
    {
        if ($index < 0 || $index >= $this->size) {
            throw new \OutOfRangeException("Index out of range");
        }

        $current = $this->root;
        while ($current instanceof BPlusTreeInternalNode) {
            $current = $current->children[0];
        }

        /** @var BPlusTreeLeafNode $current */
        for ($i = 0; $i < $index; $i++) {
            $current = $current->next;
            if ($current === null) {
                throw new \OutOfRangeException("Index out of range");
            }
        }

        return $current->values[0];
    }

    public function set(int $index, mixed $element): void
    {
        if ($index < 0 || $index >= $this->size) {
            throw new \OutOfRangeException("Index out of range");
        }

        $current = $this->getLeftmostLeaf();
        $currentIndex = 0;

        while ($current !== null) {
            for ($i = 0; $i < count($current->keys); $i++) {
                if ($current->keys[$i] == $index) {
                    $current->values[$i] = $element;
                    return;
                }
                $currentIndex++;
            }
            $current = $current->next;
        }

        throw new \OutOfRangeException("Index not found");
    }

    public function size(): int
    {
        return $this->size;
    }

    public function getItems(): array
    {
        $items = [];
        $current = $this->getLeftmostLeaf();
        while ($current !== null) {
            $items = array_merge($items, $current->values);
            $current = $current->next;
        }
        return $items;
    }

    public function addAll(Collection $collection): void
    {
        foreach ($collection->getItems() as $item) {
            $this->add($item);
        }
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    // In BPlusTree.php

    public function rangeSearch(mixed $start, mixed $end): array
    {
        $result = [];
        $current = $this->root;

        // Find the leaf node where the range starts
        while ($current instanceof BPlusTreeInternalNode) {
            $i = 0;
            while ($i < count($current->keys) && $start > $current->keys[$i]) {
                $i++;
            }
            $current = $current->children[$i];
        }

        // Collect all values in the range
        /** @var BPlusTreeLeafNode $current */
        while ($current !== null) {
            foreach ($current->values as $key => $value) {
                if ($current->keys[$key] >= $start && $current->keys[$key] <= $end) {
                    $result[] = $value;
                }
                if ($current->keys[$key] > $end) {
                    return $result;
                }
            }
            $current = $current->next;
        }

        return $result;
    }

    public function getMinimum(): mixed
    {
        $leftmostLeaf = $this->getLeftmostLeaf();
        return $leftmostLeaf !== null ? $leftmostLeaf->values[0] : null;
    }

    public function getMaximum(): mixed
    {
        $rightmostLeaf = $this->getRightmostLeaf();
        return $rightmostLeaf !== null ? $rightmostLeaf->values[count($rightmostLeaf->values) - 1] : null;
    }

    public function balance(): void
    {
        // A B+ Tree is self-balancing, so we don't need to implement any additional balancing logic.
        // However, we can perform a check to ensure the tree is balanced.
        $this->checkBalance($this->root);
    }

    private function checkBalance(?BPlusTreeNode $node): int
    {
        if ($node === null) {
            return 0;
        }

        if ($node instanceof BPlusTreeLeafNode) {
            return 1;
        }


        /** @var BPlusTreeInternalNode $node */
        $height = $this->checkBalance($node->children[0]);

        for ($i = 1; $i < count($node->children); $i++) {
            $childHeight = $this->checkBalance($node->children[$i]);
            if ($childHeight !== $height) {
                throw new \RuntimeException("B+ Tree is not balanced");
            }
        }

        return $height + 1;
    }

    public function sort(): void
    {
        // B+ Tree is always sorted, so this method doesn't need to do anything.
        // However, we can perform a check to ensure the tree is sorted.
        $this->checkSorted();
    }

    private function checkSorted(): void
    {
        $current = $this->getLeftmostLeaf();
        $prev = null;

        while ($current !== null) {
            foreach ($current->values as $value) {
                if ($prev !== null && $value < $prev) {
                    throw new \RuntimeException("B+ Tree is not sorted");
                }
                $prev = $value;
            }
            $current = $current->next;
        }
    }

    private function getLeftmostLeaf(): ?BPlusTreeLeafNode
    {
        $current = $this->root;
        while ($current instanceof BPlusTreeInternalNode) {
            $current = $current->children[0];
        }
        return $current;
    }

    private function getRightmostLeaf(): ?BPlusTreeLeafNode
    {
        $current = $this->root;
        while ($current instanceof BPlusTreeInternalNode) {
            $current = $current->children[count($current->children) - 1];
        }
        return $current;
    }
}
