<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree;

use KaririCode\Contract\DataStructure\Structural\BPlusTreeCollection;
use KaririCode\Contract\DataStructure\Structural\Collection;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeInternalNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeLeafNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeNode;

/**
 * BPlusTree is an implementation of a B+ Tree data structure.
 *
 * The B+ Tree is a self-balancing tree data structure that maintains sorted data
 * and allows for efficient insertion, deletion, and search operations. It is commonly
 * used in databases and file systems.
 *
 * ### Complexity Analysis:
 * - **Time Complexity**:
 *   - Insertion: O(log n)
 *   - Deletion: O(log n)
 *   - Search: O(log n)
 *   - Range Search: O(log n + k), where k is the number of elements in the range
 * - **Space Complexity**:
 *   - Space: O(n)
 *
 * The B+ Tree provides better space utilization and supports range queries efficiently.
 * It is optimized for systems that read and write large blocks of data.
 *
 * @category  Trees
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class BPlusTree implements BPlusTreeCollection
{
    private ?BPlusTreeNode $root = null;
    private BPlusTreeSearcher $searcher;
    private int $size = 0;

    public function __construct(private int $order)
    {
        if ($order < 3) {
            throw new \InvalidArgumentException('Order must be at least 3');
        }
        $this->order = $order;
        $this->searcher = new BPlusTreeSearcher();
        $this->root = null;
    }

    public function getRoot(): ?BPlusTreeNode
    {
        return $this->root;
    }

    public function add(mixed $element): void
    {
        $this->insert($element, $element);
    }

    public function insert(int $key, mixed $value): void
    {
        if (null === $this->root) {
            $this->root = new BPlusTreeLeafNode($this->order);
        }

        $newRoot = $this->root->insert($key, $value);
        if ($newRoot !== $this->root) {
            $this->root = $newRoot;
        }

        ++$this->size;
    }

    public function remove(mixed $element): bool
    {
        if (null === $this->root) {
            return false;
        }

        $result = $this->root->remove($element);
        if ($result) {
            --$this->size;
            if ($this->root instanceof BPlusTreeInternalNode && 0 === count($this->root->keys)) {
                $this->root = $this->root->children[0];
            } elseif ($this->root instanceof BPlusTreeLeafNode && 0 === count($this->root->keys)) {
                $this->root = null; // Set root to null when tree is empty
            }
        }

        return $result;
    }

    public function clear(): void
    {
        $this->root = null;
        $this->size = 0;
    }

    public function find(mixed $element): mixed
    {
        return $this->searcher->find($this, $element);
    }

    public function contains(mixed $element): bool
    {
        return null !== $this->find($element);
    }

    public function rangeSearch(mixed $start, mixed $end): array
    {
        return $this->searcher->rangeSearch($this, $start, $end);
    }

    public function get(int $key): mixed
    {
        $value = $this->find($key);
        if (null !== $value) {
            return $value;
        } else {
            throw new \OutOfRangeException('Key not found');
        }
    }

    public function set(int $key, mixed $value): void
    {
        if (null === $this->root) {
            throw new \OutOfRangeException('Key not found');
        }

        $node = $this->root;
        while ($node instanceof BPlusTreeInternalNode) {
            $index = 0;
            while ($index < count($node->keys) && $key >= $node->keys[$index]) {
                ++$index;
            }
            $node = $node->children[$index];
        }

        if (null === $node) {
            throw new \OutOfRangeException('Key not found');
        }

        /** @var BPlusTreeLeafNode $node */
        $index = array_search($key, $node->keys);
        if (false !== $index) {
            $node->values[$index] = $value;
        } else {
            throw new \OutOfRangeException('Key not found');
        }
    }

    public function size(): int
    {
        return $this->size;
    }

    public function getItems(): array
    {
        $items = [];
        $current = $this->getLeftmostLeaf();
        while (null !== $current) {
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

    public function getMinimum(): mixed
    {
        $leftmostLeaf = $this->getLeftmostLeaf();

        return null !== $leftmostLeaf ? $leftmostLeaf->values[0] : null;
    }

    public function getMaximum(): mixed
    {
        $rightmostLeaf = $this->getRightmostLeaf();

        return null !== $rightmostLeaf ? $rightmostLeaf->values[count($rightmostLeaf->values) - 1] : null;
    }

    public function balance(): void
    {
        // A B+ Tree is self-balancing, so we don't need to implement any additional balancing logic.
        // However, we can perform a check to ensure the tree is balanced.
        $this->checkBalance($this->root);
    }

    public function isBalanced(): bool
    {
        if (null === $this->root) {
            return true;
        }

        $leafDepth = null;

        return $this->checkBalance($this->root, 0, $leafDepth);
    }

    private function checkBalance(?BPlusTreeNode $node, int $currentDepth = 0, ?int &$leafDepth = null): bool
    {
        if (null === $node) {
            return true;
        }

        if ($node instanceof BPlusTreeLeafNode) {
            if (null === $leafDepth) {
                $leafDepth = $currentDepth;
            } elseif ($currentDepth !== $leafDepth) {
                return false;
            }

            return true;
        }

        /** @var BPlusTreeInternalNode $node */
        foreach ($node->children as $child) {
            if (! $this->checkBalance($child, $currentDepth + 1, $leafDepth)) {
                return false;
            }
        }

        return true;
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

        while (null !== $current) {
            foreach ($current->values as $value) {
                if (null !== $prev && $value < $prev) {
                    throw new \RuntimeException('B+ Tree is not sorted');
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

    public function visualTreeStructure(): string
    {
        if (null === $this->root) {
            return 'Empty tree';
        }

        return $this->visualizeNode($this->root);
    }

    private function visualizeNode(BPlusTreeNode $node, int $depth = 0): string
    {
        $indent = str_repeat('  ', $depth);
        $output = '';

        if ($node instanceof BPlusTreeInternalNode) {
            $output .= $indent . "Internal Node:\n";
            $output .= $indent . '  Keys: ' . implode(', ', $node->keys) . "\n";
            foreach ($node->children as $index => $child) {
                $output .= $indent . '  Child ' . ($index + 1) . ":\n";
                $output .= $this->visualizeNode($child, $depth + 2);
            }
        } elseif ($node instanceof BPlusTreeLeafNode) {
            $output .= $indent . "Leaf Node:\n";
            $output .= $indent . '  Keys: ' . implode(', ', $node->keys) . "\n";
            $output .= $indent . '  Values: ' . implode(', ', $node->values) . "\n";
            if ($node->next) {
                $output .= $indent . '  Next Leaf -> [' . implode(', ', $node->next->keys) . "]\n";
            }
        }

        return $output;
    }
}
