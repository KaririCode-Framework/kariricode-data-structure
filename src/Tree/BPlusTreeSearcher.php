<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree;

use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeInternalNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeLeafNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeNode;

/**
 * BPlusTreeSearcher is a helper class for performing search operations in a B+ Tree.
 *
 * This class provides methods to find individual elements and perform range searches
 * within a B+ Tree. It operates on nodes of the tree, traversing and searching for
 * the specified keys or values.
 *
 * The BPlusTreeSearcher is optimized for efficient searching in large datasets, making
 * it suitable for use cases such as database indexing and file systems.
 *
 * @category  Trees
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class BPlusTreeSearcher
{
    public function find(BPlusTree $tree, mixed $element): mixed
    {
        $root = $tree->getRoot();
        if (null === $root) {
            return null;
        }

        return is_int($element) ?
            $this->search($root, $element) :
            $this->searchByValue($root, $element);
    }

    private function search(BPlusTreeNode $node, int $key): mixed
    {
        if ($node instanceof BPlusTreeLeafNode) {
            $index = array_search($key, $node->keys, true);

            return false !== $index ? $node->values[$index] : null;
        }

        /** @var BPlusTreeInternalNode $node */
        $index = 0;
        while ($index < count($node->keys) && $key >= $node->keys[$index]) {
            ++$index;
        }

        return $this->search($node->children[$index], $key);
    }

    private function searchByValue(BPlusTreeNode $root, mixed $value): ?int
    {
        $leafNode = $this->findFirstLeafNode($root);
        while (null !== $leafNode) {
            $result = $this->searchInLeafNode($leafNode, $value);
            if (null !== $result) {
                return $result;
            }
            $leafNode = $leafNode->next;
        }

        return null;
    }

    private function findFirstLeafNode(BPlusTreeNode $node): ?BPlusTreeLeafNode
    {
        $current = $node;
        while ($current instanceof BPlusTreeInternalNode) {
            $current = $current->children[0];
        }

        return $current;
    }

    private function searchInLeafNode(BPlusTreeLeafNode $node, mixed $value): ?int
    {
        $values = $node->values;
        $keys = $node->keys;
        foreach ($values as $index => $nodeValue) {
            if ($this->compareValues($nodeValue, $value)) {
                return $keys[$index];
            }
        }

        return null;
    }

    private function compareValues(mixed $a, mixed $b): bool
    {
        if (is_object($a) && is_object($b)) {
            return $a == $b; // Use loose comparison for objects
        }

        return $a === $b; // Use strict comparison for other types
    }

    public function rangeSearch(BPlusTree $tree, mixed $start, mixed $end): array
    {
        $result = [];
        $current = $tree->getRoot();

        // Find the leaf node where the range starts
        while ($current instanceof BPlusTreeInternalNode) {
            $i = 0;
            while ($i < count($current->keys) && $start > $current->keys[$i]) {
                ++$i;
            }
            $current = $current->children[$i];
        }

        // Collect all values in the range
        /** @var BPlusTreeLeafNode $current */
        while (null !== $current) {
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
}
