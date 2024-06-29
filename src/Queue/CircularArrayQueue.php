<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Queue;

use KaririCode\Contract\DataStructure\Queue;

/**
 * CircularArrayQueue implementation.
 *
 * This class provides the common functionality for array-based queues using a circular array.
 *
 * @category  Queues
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
abstract class CircularArrayQueue implements Queue
{
    protected array $elements;
    protected int $front = 0;
    protected int $size = 0;
    protected int $capacity;

    public function __construct(int $initialCapacity = 16)
    {
        $this->capacity = $initialCapacity;
        $this->elements = array_fill(0, $this->capacity, null);
    }

    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function clear(): void
    {
        $this->elements = array_fill(0, $this->capacity, null);
        $this->front = 0;
        $this->size = 0;
    }

    protected function ensureCapacity(): void
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

    public function peek(): mixed
    {
        return $this->isEmpty() ? null : $this->elements[$this->front];
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

    public function enqueue(mixed $element): void
    {
        $this->ensureCapacity();
        $index = ($this->front + $this->size) % $this->capacity;
        $this->elements[$index] = $element;
        ++$this->size;
    }

    public function getItems(): array
    {
        $items = [];
        for ($i = 0; $i < $this->size; ++$i) {
            $items[] = $this->elements[($this->front + $i) % $this->capacity];
        }
        return $items;
    }
}
