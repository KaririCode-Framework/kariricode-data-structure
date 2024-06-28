<?php

declare(strict_types=1);

namespace KaririCode\DataStructure;

class Node
{
    public function __construct(
        public mixed $data,
        public ?Node $prev = null,
        public ?Node $next = null
    ) {
    }
}
