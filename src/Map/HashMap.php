<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Map;

use KaririCode\Contract\DataStructure\Behavioral\IterableCollection;
use KaririCode\Contract\DataStructure\Map;

/**
 * HashMap implementation.
 *
 * This class implements a hash map using PHP's built-in array as the underlying storage.
 * It provides O(1) average time complexity for put, get, and remove operations.
 * It now properly handles object keys by using their hash.
 *
 * @category  Maps
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class HashMap implements Map, IterableCollection, \IteratorAggregate
{
    private array $map = [];

    /**
     * Converts a mixed-type key into a valid array key (int or string).
     *
     * @param mixed $key the original key
     *
     * @throws \InvalidArgumentException If the key is of an invalid type (e.g., an array).
     *
     * @return int|string the converted key
     */
    private function getInternalKey(mixed $key): int|string
    {
        if (is_object($key)) {
            return spl_object_hash($key);
        }

        if (! is_scalar($key) && null !== $key) {
            throw new \InvalidArgumentException('Invalid key type: HashMap keys must be scalar or objects.');
        }

        // For scalar types (int, string, bool, float) and null, PHP's implicit
        // conversion for array keys is the desired behavior.
        // This explicit conversion helps static analysis tools understand the types.
        if (is_bool($key)) {
            return (int) $key;
        }
        if (is_float($key)) {
            return (int) $key;
        }
        if (null === $key) {
            return '';
        }

        return $key; // The key is now guaranteed to be an int or string.
    }

    public function put(mixed $key, mixed $value): void
    {
        $internalKey = $this->getInternalKey($key);
        $this->map[$internalKey] = $value;
    }

    public function get(mixed $key): mixed
    {
        $internalKey = $this->getInternalKey($key);

        return $this->map[$internalKey] ?? null;
    }

    public function remove(mixed $key): bool
    {
        // The key conversion is done here, ensuring the type is safe.
        $internalKey = $this->getInternalKey($key);

        // The check is performed with the already handled key (int|string).
        if (array_key_exists($internalKey, $this->map)) {
            unset($this->map[$internalKey]);

            return true;
        }

        return false;
    }

    public function containsKey(mixed $key): bool
    {
        // The key conversion is also done here.
        $internalKey = $this->getInternalKey($key);

        return array_key_exists($internalKey, $this->map);
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

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->map);
    }
}
