<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Queue;

use KaririCode\Contract\DataStructure\Deque;

/**
 * ArrayDeque implementation.
 *
 * This class implements a double-ended queue using a circular array.
 * It provides amortized O(1) time complexity for add and remove operations at both ends.
 *
 * @category  Queues
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class ArrayDeque extends CircularArrayQueue implements Deque
{
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

    public function add(mixed $element): void
    {
        $this->addLast($element);
    }
}
