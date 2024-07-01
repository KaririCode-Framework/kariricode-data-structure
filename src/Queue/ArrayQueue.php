<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Queue;

use KaririCode\Contract\DataStructure\Queue;

/**
 * ArrayQueue implementation.
 *
 * This class implements a simple queue using a circular array.
 * It provides amortized O(1) time complexity for enqueue and dequeue operations.
 *
 * @category  Queues
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class ArrayQueue extends CircularArrayQueue implements Queue
{
    // No additional methods required, uses methods from CircularArrayQueue
}
