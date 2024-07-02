<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree\BPlusTreeNode;

abstract class BPlusTreeNode
{
    public array $keys = [];

    public function __construct(protected int $order)
    {
    }

    public function isFull(): bool
    {
        return count($this->keys) >= $this->order - 1;
    }

    abstract public function insert(int $key, mixed $value): BPlusTreeNode;
    abstract public function remove(mixed $key): bool;
    abstract public function search(mixed $input): mixed;
}
