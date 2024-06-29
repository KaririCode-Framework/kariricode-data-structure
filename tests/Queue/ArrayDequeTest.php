<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Queue;

use KaririCode\DataStructure\Queue\ArrayDeque;
use PHPUnit\Framework\TestCase;

final class ArrayDequeTest extends TestCase
{
    // Test enqueuing elements
    public function testEnqueueAddsElementToEndOfDeque(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $this->assertSame(1, $deque->peek());
    }

    // Test dequeuing elements
    public function testDequeueRemovesElementFromFrontOfDeque(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->enqueue(2);
        $this->assertSame(1, $deque->dequeue());
        $this->assertSame(2, $deque->peek());
    }

    // Test dequeuing from an empty deque
    public function testDequeueFromEmptyDequeReturnsNull(): void
    {
        $deque = new ArrayDeque();
        $this->assertNull($deque->dequeue());
    }

    // Test peeking elements
    public function testPeekReturnsElementFromFrontWithoutRemovingIt(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $this->assertSame(1, $deque->peek());
        $this->assertSame(1, $deque->peek());
    }

    // Test peeking from an empty deque
    public function testPeekFromEmptyDequeReturnsNull(): void
    {
        $deque = new ArrayDeque();
        $this->assertNull($deque->peek());
    }

    // Test adding elements to the front
    public function testAddFirstAddsElementToFrontOfDeque(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->addFirst(2);
        $this->assertSame(2, $deque->peek());
    }

    // Test removing last elements
    public function testRemoveLastRemovesElementFromEndOfDeque(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->enqueue(2);
        $this->assertSame(2, $deque->removeLast());
        $this->assertSame(1, $deque->peekLast());
    }

    // Test removing last element from an empty deque
    public function testRemoveLastFromEmptyDequeReturnsNull(): void
    {
        $deque = new ArrayDeque();
        $this->assertNull($deque->removeLast());
    }

    // Test peeking last elements
    public function testPeekLastReturnsElementFromEndWithoutRemovingIt(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->enqueue(2);
        $this->assertSame(2, $deque->peekLast());
        $this->assertSame(2, $deque->peekLast());
    }

    // Test peeking last from an empty deque
    public function testPeekLastFromEmptyDequeReturnsNull(): void
    {
        $deque = new ArrayDeque();
        $this->assertNull($deque->peekLast());
    }

    // Test checking if deque is empty
    public function testIsEmptyReturnsTrueIfDequeIsEmpty(): void
    {
        $deque = new ArrayDeque();
        $this->assertTrue($deque->isEmpty());
        $deque->enqueue(1);
        $this->assertFalse($deque->isEmpty());
    }

    // Test getting the size of the deque
    public function testSizeReturnsNumberOfElementsInDeque(): void
    {
        $deque = new ArrayDeque();
        $this->assertSame(0, $deque->size());
        $deque->enqueue(1);
        $deque->enqueue(2);
        $this->assertSame(2, $deque->size());
    }

    // Test ensuring capacity of deque
    public function testEnsureCapacityDoublesCapacityWhenFull(): void
    {
        $deque = new ArrayDeque(2);
        $deque->enqueue(1);
        $deque->enqueue(2);
        $deque->enqueue(3); // Should trigger capacity increase
        $this->assertSame(3, $deque->size());
    }

    // Test handling null values in the deque
    public function testHandlingNullValuesCorrectly(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(null);
        $this->assertSame(null, $deque->dequeue());
    }

    // Test circular nature of the deque
    public function testCircularBehavior(): void
    {
        $deque = new ArrayDeque(3);
        $deque->enqueue(1);
        $deque->enqueue(2);
        $deque->enqueue(3);
        $deque->dequeue();
        $deque->enqueue(4);
        $this->assertSame(2, $deque->dequeue());
        $this->assertSame(3, $deque->dequeue());
        $this->assertSame(4, $deque->dequeue());
    }

    // Test deque with various data types
    public function testDequeWithVariousDataTypes(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(123);
        $deque->enqueue('string');
        $deque->enqueue([1, 2, 3]);
        $deque->enqueue(new \stdClass());

        $this->assertSame(123, $deque->dequeue());
        $this->assertSame('string', $deque->dequeue());
        $this->assertSame([1, 2, 3], $deque->dequeue());
        $this->assertInstanceOf(\stdClass::class, $deque->dequeue());
    }

    // Test deque behavior after mixed operations
    public function testDequeBehaviorAfterMixedOperations(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->enqueue(2);
        $deque->addFirst(0);
        $deque->removeLast();
        $deque->enqueue(3);
        $this->assertSame(0, $deque->dequeue());
        $this->assertSame(1, $deque->dequeue());
        $this->assertSame(3, $deque->peekLast());
    }

    // Test capacity expansion during addFirst operations
    public function testEnsureCapacityExpandsDuringAddFirstOperations(): void
    {
        $deque = new ArrayDeque(2);
        $deque->addFirst(1);
        $deque->addFirst(2);
        $deque->addFirst(3); // Should trigger capacity increase
        $this->assertSame(3, $deque->size());
        $this->assertSame(3, $deque->peek());
        $this->assertSame(1, $deque->peekLast());
    }

    // Test capacity expansion during removeLast operations
    public function testEnsureCapacityExpandsDuringRemoveLastOperations(): void
    {
        $deque = new ArrayDeque(2);
        $deque->enqueue(1);
        $deque->enqueue(2);
        $deque->enqueue(3); // Should trigger capacity increase
        $deque->removeLast();
        $deque->removeLast();
        $this->assertSame(1, $deque->size());
        $this->assertSame(1, $deque->peek());
    }

    // Test mixed operations of addFirst, addLast, removeFirst, and removeLast
    public function testMixedOperations(): void
    {
        $deque = new ArrayDeque();
        $deque->addFirst(1);
        $deque->enqueue(2);
        $deque->addFirst(0);
        $this->assertSame(0, $deque->dequeue());
        $this->assertSame(1, $deque->dequeue());
        $this->assertSame(2, $deque->removeLast());
        $deque->enqueue(3);
        $deque->addFirst(4);
        $this->assertSame(4, $deque->peek());
        $this->assertSame(3, $deque->peekLast());
    }

    // Test clearing the deque
    public function testClearEmptiesTheDeque(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->enqueue(2);
        $deque->clear();
        $this->assertTrue($deque->isEmpty());
        $this->assertNull($deque->peek());
        $this->assertNull($deque->peekLast());
        $this->assertNull($deque->dequeue());
        $this->assertNull($deque->removeLast());
    }

    // Test getting all items
    public function testGetItemsReturnsAllElementsInCorrectOrder(): void
    {
        $deque = new ArrayDeque();
        $deque->enqueue(1);
        $deque->enqueue(2);
        $deque->enqueue(3);
        $this->assertSame([1, 2, 3], $deque->getItems());
    }
}
