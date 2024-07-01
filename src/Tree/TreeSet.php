<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Set;

use KaririCode\Contract\DataStructure\Set;
use KaririCode\DataStructure\Map\TreeMap;

/**
 * TreeSet implementation.
 *
 * This class implements a set using a TreeMap to store elements. It provides O(log n) time complexity
 * for add, remove, and contains operations.
 *
 * @category  Sets
 *
 * @package   KaririCode\DataStructure\Set
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class TreeSet implements Set
{
    private TreeMap $map;

    public function __construct()
    {
        $this->map = new TreeMap();
    }

    /**
     * Adds an element to the set.
     *
     * @param mixed $element The element to add
     *
     * @return bool True if the element was added, false if it was already present
     */
    public function add(mixed $element): bool
    {
        if ($this->map->get($element) !== null) {
            return false;
        }
        $this->map->put($element, true);
        return true;
    }

    /**
     * Removes an element from the set.
     *
     * @param mixed $element The element to remove
     *
     * @return bool True if the element was removed, false if it was not present
     */
    public function remove(mixed $element): bool
    {
        return $this->map->remove($element);
    }

    /**
     * Checks if the set contains a specific element.
     *
     * @param mixed $element The element to check for
     *
     * @return bool True if the element is present, false otherwise
     */
    public function contains(mixed $element): bool
    {
        return $this->map->get($element) !== null;
    }

    /**
     * Removes all elements from the set.
     */
    public function clear(): void
    {
        $this->map = new TreeMap();
    }

    /**
     * Returns the number of elements in the set.
     *
     * @return int The number of elements in the set
     */
    public function size(): int
    {
        return $this->map->size();
    }

    /**
     * Checks if the set is empty.
     *
     * @return bool True if the set is empty, false otherwise
     */
    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    /**
     * Returns an array containing all elements in the set.
     *
     * @return array The elements in the set
     */
    public function toArray(): array
    {
        $elements = [];
        foreach ($this->map as $key => $value) {
            $elements[] = $key;
        }
        return $elements;
    }
}
