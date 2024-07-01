<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Heap;

use KaririCode\Contract\DataStructure\Behavioral\Comparable;
use KaririCode\Contract\DataStructure\Behavioral\Countable;
use KaririCode\Contract\DataStructure\Heap;

/**
 * BinaryHeap implementation.
 *
 * This class implements a binary heap (min-heap or max-heap) using a dynamic array.
 * It provides O(log n) time complexity for add, poll, and remove operations, and O(1) for peek and isEmpty operations.
 *
 * @category  Heaps
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class BinaryHeap implements Heap, Countable
{
    private array $heap;
    private string $type;

    public function __construct(string $type = 'min')
    {
        $this->heap = [];
        $this->type = $type;
    }

    public function add(mixed $element): void
    {
        $this->heap[] = $element;
        $this->heapifyUp();
    }

    public function insert(int $index, mixed $element): void
    {
        // Inserting at a specific index is not typical for a binary heap
        // but we'll implement it to satisfy the interface
        if ($index < 0 || $index > $this->size()) {
            throw new \OutOfRangeException('Index out of range');
        }
        $this->heap[$index] = $element;
        $this->heapifyUp($index);
        $this->heapifyDown($index);
    }

    public function poll(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $root = $this->heap[0];
        $lastElement = array_pop($this->heap);

        if (! $this->isEmpty()) {
            $this->heap[0] = $lastElement;
            $this->heapifyDown();
        }

        return $root;
    }

    public function remove(mixed $element): bool
    {
        $index = array_search($element, $this->heap, true);
        if (false === $index) {
            return false;
        }

        $lastElement = array_pop($this->heap);
        if ($index < $this->size()) {
            $this->heap[$index] = $lastElement;
            $this->heapifyUp($index);
            $this->heapifyDown($index);
        }

        return true;
    }

    public function peek(): mixed
    {
        return $this->heap[0] ?? null;
    }

    public function size(): int
    {
        return count($this->heap);
    }

    public function isEmpty(): bool
    {
        return empty($this->heap);
    }

    private function heapifyUp(?int $index = null): void
    {
        $index = $index ?? $this->size() - 1;
        while ($index > 0) {
            $parentIndex = ($index - 1) >> 1;
            if ($this->compare($this->heap[$index], $this->heap[$parentIndex])) {
                $this->swap($index, $parentIndex);
                $index = $parentIndex;
            } else {
                break;
            }
        }
    }

    private function heapifyDown(int $index = 0): void
    {
        $size = $this->size();

        while (true) {
            $leftChild = ($index << 1) + 1;
            $rightChild = ($index << 1) + 2;
            $largest = $index;

            if ($leftChild < $size && $this->compare($this->heap[$leftChild], $this->heap[$largest])) {
                $largest = $leftChild;
            }

            if ($rightChild < $size && $this->compare($this->heap[$rightChild], $this->heap[$largest])) {
                $largest = $rightChild;
            }

            if ($largest !== $index) {
                $this->swap($index, $largest);
                $index = $largest;
            } else {
                break;
            }
        }
    }

    private function swap(int $i, int $j): void
    {
        [$this->heap[$i], $this->heap[$j]] = [$this->heap[$j], $this->heap[$i]];
    }

    private function compare(mixed $a, mixed $b): bool
    {
        if ($a instanceof Comparable && $b instanceof Comparable) {
            return 'min' === $this->type
                ? $a->compareTo($b) < 0
                : $a->compareTo($b) > 0;
        }

        return 'min' === $this->type ? $a < $b : $a > $b;
    }
}
