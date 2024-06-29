<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Stack;

use KaririCode\Contract\DataStructure\Stack;

/**
 * ArrayStack implementation.
 *
 * This class implements a stack using a dynamic array.
 * It provides O(1) time complexity for push, pop, and peek operations.
 *
 * @category  Stacks
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class ArrayStack implements Stack
{
    private array $elements = [];

    public function push(mixed $element): void
    {
        $this->elements[] = $element;
    }

    public function pop(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return array_pop($this->elements);
    }

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->elements[count($this->elements) - 1];
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function size(): int
    {
        return count($this->elements);
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function getItems(): array
    {
        return array_reverse($this->elements);
    }
}
