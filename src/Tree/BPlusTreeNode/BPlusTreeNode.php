<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree\BPlusTreeNode;

/**
 * BPlusTreeNode is an abstract class representing a node in a B+ Tree.
 * It contains common properties and methods for both internal and leaf nodes.
 *
 * @category  Trees
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
abstract class BPlusTreeNode
{
    public array $keys = [];
    protected int $order;

    public function __construct(int $order)
    {
        $this->order = $order;
    }

    public function isFull(): bool
    {
        return count($this->keys) >= $this->order - 1;
    }

    abstract public function insert(int $key, mixed $value): BPlusTreeNode;

    abstract public function remove(mixed $key): bool;

    abstract public function search(mixed $input): mixed;
}
