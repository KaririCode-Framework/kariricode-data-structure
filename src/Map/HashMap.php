<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Map;

use KaririCode\Contract\DataStructure\Map;

/**
 * HashMap implementation.
 *
 * This class implements a hash map using PHP's built-in array as the underlying storage.
 * It provides O(1) average time complexity for put, get, and remove operations.
 *
 * @category  Maps
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class HashMap implements Map
{
    private array $map = [];

    public function put(mixed $key, mixed $value): void
    {
        $this->map[$key] = $value;
    }

    public function get(mixed $key): mixed
    {
        return $this->map[$key] ?? null;
    }

    public function remove(mixed $key): bool
    {
        if (array_key_exists($key, $this->map)) {
            unset($this->map[$key]);

            return true;
        }

        return false;
    }

    public function containsKey(mixed $key): bool
    {
        return array_key_exists($key, $this->map);
    }

    public function size(): int
    {
        return count($this->map);
    }

    public function clear(): void
    {
        $this->map = [];
    }

    public function keys(): array
    {
        return array_keys($this->map);
    }

    public function values(): array
    {
        return array_values($this->map);
    }
}
