<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Collection;

use KaririCode\Contract\DataStructure\Structural\Collection;

/**
 * ArrayList implementation.
 *
 * This class implements a dynamic array using PHP's built-in array.
 * It provides O(1) time complexity for index-based access and amortized O(1) for adding elements.
 *
 * @category  Collections
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class ArrayList implements Collection
{
    private array $elements = [];

    public function add(mixed $element): void
    {
        $this->elements[] = $element;
    }

    public function addAll(Collection $collection): void
    {
        foreach ($collection->getItems() as $element) {
            $this->add($element);
        }
    }

    public function remove(mixed $element): bool
    {
        $index = array_search($element, $this->elements, true);
        if (false !== $index) {
            array_splice($this->elements, $index, 1);

            return true;
        }

        return false;
    }

    public function contains(mixed $element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function getItems(): array
    {
        return $this->elements;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function get(int $index): mixed
    {
        if ($index < 0 || $index >= count($this->elements)) {
            throw new \OutOfRangeException("Index out of range: $index");
        }

        return $this->elements[$index];
    }

    public function set(int $index, mixed $element): void
    {
        if ($index < 0 || $index >= count($this->elements)) {
            throw new \OutOfRangeException("Index out of range: $index");
        }
        $this->elements[$index] = $element;
    }
}
