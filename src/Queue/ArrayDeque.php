<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Queue;

use KaririCode\Contract\DataStructure\Queue;

/**
 * ArrayDeque implementation.
 *
 * This class implements a double-ended queue using a circular array.
 * It provides amortized O(1) time complexity for add and remove operations at both ends.
 *
 * @category  Queues
 *
 * @implements Queue<mixed>
 */
class ArrayDeque implements Queue
{
    private array $elements;
    private int $front = 0;
    private int $size = 0;
    private int $capacity;

    public function __construct(int $initialCapacity = 16)
    {
        $this->capacity = $initialCapacity;
        $this->elements = array_fill(0, $this->capacity, null);
    }

    public function enqueue(mixed $element): void
    {
        $this->ensureCapacity();
        $index = ($this->front + $this->size) % $this->capacity;
        $this->elements[$index] = $element;
        ++$this->size;
    }

    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }
        $element = $this->elements[$this->front];
        $this->elements[$this->front] = null;
        $this->front = ($this->front + 1) % $this->capacity;
        --$this->size;

        return $element;
    }

    public function peek(): mixed
    {
        return $this->isEmpty() ? null : $this->elements[$this->front];
    }

    public function addFirst(mixed $element): void
    {
        $this->ensureCapacity();
        $this->front = ($this->front - 1 + $this->capacity) % $this->capacity;
        $this->elements[$this->front] = $element;
        ++$this->size;
    }

    public function removeLast(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }
        $index = ($this->front + $this->size - 1) % $this->capacity;
        $element = $this->elements[$index];
        $this->elements[$index] = null;
        --$this->size;

        return $element;
    }

    public function peekLast(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }
        $index = ($this->front + $this->size - 1) % $this->capacity;

        return $this->elements[$index];
    }

    public function isEmpty(): bool
    {
        return 0 === $this->size;
    }

    public function size(): int
    {
        return $this->size;
    }

    /**
     * Ensures that the deque has enough capacity to add a new element.
     */
    private function ensureCapacity(): void
    {
        if ($this->size === $this->capacity) {
            $newCapacity = $this->capacity * 2;
            $newElements = array_fill(0, $newCapacity, null);
            for ($i = 0; $i < $this->size; ++$i) {
                $newElements[$i] = $this->elements[($this->front + $i) % $this->capacity];
            }
            $this->elements = $newElements;
            $this->front = 0;
            $this->capacity = $newCapacity;
        }
    }
}
