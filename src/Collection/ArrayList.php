<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Collection;

use KaririCode\Contract\DataStructure\Structural\Collection;

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
            if (is_int($index)) {
                array_splice($this->elements, $index, 1);
            } else {
                unset($this->elements[$index]);
            }

            return true;
        }

        return false;
    }

    public function contains(mixed $element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function find(mixed $element): ?int
    {
        $index = array_search($element, $this->elements, true);

        return is_int($index) ? $index : null;
    }

    public function getItems(): array
    {
        return $this->elements;
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function size(): int
    {
        return count($this->elements);
    }

    public function get(mixed $key): mixed
    {
        if (! $this->hasKey($key)) {
            throw new \OutOfRangeException('Key not found: ' . $this->keyToString($key));
        }

        return $this->elements[$key];
    }

    public function set(mixed $key, mixed $element): void
    {
        if (! $this->isValidArrayKey($key)) {
            throw new \InvalidArgumentException('Invalid key type: ' . gettype($key));
        }

        if (! $this->hasKey($key)) {
            throw new \OutOfRangeException('Key not found: ' . $this->keyToString($key));
        }

        $this->elements[$key] = $element;
    }

    private function hasKey(mixed $key): bool
    {
        if (! is_int($key) && ! is_string($key)) {
            return false;
        }

        return array_key_exists($key, $this->elements);
    }

    private function isValidArrayKey(mixed $key): bool
    {
        return is_int($key) || is_string($key);
    }

    private function keyToString(mixed $key): string
    {
        if (is_object($key)) {
            // Check if the object can be converted to a string
            if (method_exists($key, '__toString')) {
                return (string) $key;
            }

            // Fallback for objects without a __toString method
            return get_class($key) . '@' . spl_object_id($key);
        }

        if (is_array($key)) {
            return 'Array';
        }

        if (is_resource($key)) {
            // Returns a safe string representation for resources
            return get_resource_type($key) . ' #' . (int) $key;
        }

        // Now that objects, arrays, and resources are handled,
        // the remaining types (scalar and null) can be safely cast to string.
        return (string) $key;
    }
}
