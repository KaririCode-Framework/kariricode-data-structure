<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Collection;

use KaririCode\Contract\DataStructure\Structural\Collection;
use KaririCode\DataStructure\Node;

/**
 * LinkedList implementation.
 *
 * This class implements a doubly-linked list, providing O(1) time complexity
 * for adding and removing elements at both ends, and O(n) for arbitrary index access.
 *
 * @category  Collections
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class LinkedList implements Collection
{
    private ?Node $head = null;
    private ?Node $tail = null;
    private int $size = 0;

    public function add(mixed $element): void
    {
        $newNode = new Node($element, $this->tail);
        if ($this->tail) {
            $this->tail->next = $newNode;
        } else {
            $this->head = $newNode;
        }
        $this->tail = $newNode;
        ++$this->size;
    }

    public function addAll(Collection $collection): void
    {
        foreach ($collection->getItems() as $element) {
            $this->add($element);
        }
    }

    public function remove(mixed $element): bool
    {
        $current = $this->head;
        while (null !== $current) {
            if ($current->data === $element) {
                if ($current->prev) {
                    $current->prev->next = $current->next;
                } else {
                    $this->head = $current->next;
                }
                if ($current->next) {
                    $current->next->prev = $current->prev;
                } else {
                    $this->tail = $current->prev;
                }
                --$this->size;

                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    public function contains(mixed $element): bool
    {
        return null !== $this->find($element);
    }

    public function find(mixed $element): ?Node
    {
        $current = $this->head;
        while (null !== $current) {
            if ($current->data === $element) {
                return $current;
            }
            $current = $current->next;
        }

        return null;
    }

    public function clear(): void
    {
        $this->head = null;
        $this->tail = null;
        $this->size = 0;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->size;
    }

    public function getItems(): array
    {
        $items = [];
        $current = $this->head;
        while (null !== $current) {
            $items[] = $current->data;
            $current = $current->next;
        }

        return $items;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function get(mixed $key): mixed
    {
        $node = $this->findNode($key);
        if (null === $node) {
            throw new \OutOfRangeException('Element not found for key: ' . $this->keyToString($key));
        }

        return $node->data;
    }

    public function set(mixed $key, mixed $element): void
    {
        $node = $this->findNode($key);
        if (null === $node) {
            throw new \OutOfRangeException('Element not found for key: ' . $this->keyToString($key));
        }
        $node->data = $element;
    }

    private function findNode(mixed $key): ?Node
    {
        if (is_int($key)) {
            return $this->findNodeByIndex($key);
        }

        return $this->findNodeByValue($key);
    }

    private function findNodeByIndex(int $index): ?Node
    {
        if ($index < 0 || $index >= $this->size) {
            return null;
        }
        $current = $this->head;
        for ($i = 0; $i < $index; ++$i) {
            $current = $current->next;
        }

        return $current;
    }

    private function findNodeByValue(mixed $value): ?Node
    {
        $current = $this->head;
        while (null !== $current) {
            if ($this->compareValues($current->data, $value)) {
                return $current;
            }
            $current = $current->next;
        }

        return null;
    }

    private function compareValues(mixed $a, mixed $b): bool
    {
        if (is_object($a) && is_object($b)) {
            return $a == $b;
        }

        return $a === $b;
    }

    private function keyToString(mixed $key): string
    {
        if (is_object($key)) {
            return get_class($key) . '@' . spl_object_id($key);
        }

        return (string) $key;
    }

    /**
     * Clone method to ensure deep copy of nodes.
     */
    public function __clone()
    {
        $newList = new LinkedList();
        $current = $this->head;
        while (null !== $current) {
            $newList->add($current->data);
            $current = $current->next;
        }
        $this->head = $newList->head;
        $this->tail = $newList->tail;
    }
}
