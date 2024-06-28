<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Queue;

use KaririCode\DataStructure\Queue\ArrayQueue;
use PHPUnit\Framework\TestCase;

final class ArrayQueueTest extends TestCase
{
    // Test enqueuing elements
    public function testEnqueueAddsElementToEndOfQueue(): void
    {
        $queue = new ArrayQueue();
        $queue->enqueue(1);
        $this->assertSame(1, $queue->peek());
    }

    // Test dequeuing elements
    public function testDequeueRemovesElementFromFrontOfQueue(): void
    {
        $queue = new ArrayQueue();
        $queue->enqueue(1);
        $queue->enqueue(2);
        $this->assertSame(1, $queue->dequeue());
        $this->assertSame(2, $queue->peek());
    }

    // Test dequeuing from an empty queue
    public function testDequeueFromEmptyQueueReturnsNull(): void
    {
        $queue = new ArrayQueue();
        $this->assertNull($queue->dequeue());
    }

    // Test peeking elements
    public function testPeekReturnsElementFromFrontWithoutRemovingIt(): void
    {
        $queue = new ArrayQueue();
        $queue->enqueue(1);
        $this->assertSame(1, $queue->peek());
        $this->assertSame(1, $queue->peek());
    }

    // Test peeking from an empty queue
    public function testPeekFromEmptyQueueReturnsNull(): void
    {
        $queue = new ArrayQueue();
        $this->assertNull($queue->peek());
    }

    // Test checking if queue is empty
    public function testIsEmptyReturnsTrueIfQueueIsEmpty(): void
    {
        $queue = new ArrayQueue();
        $this->assertTrue($queue->isEmpty());
        $queue->enqueue(1);
        $this->assertFalse($queue->isEmpty());
    }

    // Test getting the size of the queue
    public function testSizeReturnsNumberOfElementsInQueue(): void
    {
        $queue = new ArrayQueue();
        $this->assertSame(0, $queue->size());
        $queue->enqueue(1);
        $queue->enqueue(2);
        $this->assertSame(2, $queue->size());
    }

    // Test ensuring capacity of queue
    public function testEnsureCapacityDoublesCapacityWhenFull(): void
    {
        $queue = new ArrayQueue(2);
        $queue->enqueue(1);
        $queue->enqueue(2);
        $queue->enqueue(3); // Should trigger capacity increase
        $this->assertSame(3, $queue->size());
    }

    // Test handling null values in the queue
    public function testHandlingNullValuesCorrectly(): void
    {
        $queue = new ArrayQueue();
        $queue->enqueue(null);
        $this->assertSame(null, $queue->dequeue());
    }

    // Test queue with various data types
    public function testQueueWithVariousDataTypes(): void
    {
        $queue = new ArrayQueue();
        $queue->enqueue(123);
        $queue->enqueue('string');
        $queue->enqueue([1, 2, 3]);
        $queue->enqueue(new \stdClass());

        $this->assertSame(123, $queue->dequeue());
        $this->assertSame('string', $queue->dequeue());
        $this->assertSame([1, 2, 3], $queue->dequeue());
        $this->assertInstanceOf(\stdClass::class, $queue->dequeue());
    }

    // Test queue behavior after mixed operations
    public function testQueueBehaviorAfterMixedOperations(): void
    {
        $queue = new ArrayQueue();
        $queue->enqueue(1);
        $queue->enqueue(2);
        $queue->enqueue(3);
        $queue->dequeue();
        $queue->enqueue(4);
        $this->assertSame(2, $queue->dequeue());
        $this->assertSame(3, $queue->dequeue());
        $this->assertSame(4, $queue->peek());
    }
}
