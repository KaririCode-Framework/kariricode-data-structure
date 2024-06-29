<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Stack;

use KaririCode\DataStructure\Stack\ArrayStack;
use PHPUnit\Framework\TestCase;

final class ArrayStackTest extends TestCase
{
    // Test pushing elements onto the stack
    public function testPushAddsElementToStack(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $this->assertSame(1, $stack->peek());
    }

    // Test popping elements from the stack
    public function testPopRemovesElementFromStack(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $stack->push(2);
        $this->assertSame(2, $stack->pop());
        $this->assertSame(1, $stack->peek());
    }

    // Test popping from an empty stack
    public function testPopFromEmptyStackReturnsNull(): void
    {
        $stack = new ArrayStack();
        $this->assertNull($stack->pop());
    }

    // Test peeking elements
    public function testPeekReturnsElementFromTopWithoutRemovingIt(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $this->assertSame(1, $stack->peek());
        $this->assertSame(1, $stack->peek());
    }

    // Test peeking from an empty stack
    public function testPeekFromEmptyStackReturnsNull(): void
    {
        $stack = new ArrayStack();
        $this->assertNull($stack->peek());
    }

    // Test checking if stack is empty
    public function testIsEmptyReturnsTrueIfStackIsEmpty(): void
    {
        $stack = new ArrayStack();
        $this->assertTrue($stack->isEmpty());
        $stack->push(1);
        $this->assertFalse($stack->isEmpty());
    }

    // Test getting the size of the stack
    public function testSizeReturnsNumberOfElementsInStack(): void
    {
        $stack = new ArrayStack();
        $this->assertSame(0, $stack->size());
        $stack->push(1);
        $stack->push(2);
        $this->assertSame(2, $stack->size());
    }

    // Test clearing the stack
    public function testClearRemovesAllElementsFromStack(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $stack->push(2);
        $stack->clear();
        $this->assertTrue($stack->isEmpty());
    }

    // Test converting the stack to an array
    public function testGetItemsReturnsAllElementsInStack(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $stack->push(2);
        $this->assertSame([2, 1], $stack->getItems());
    }

    // Test stack with various data types
    public function testStackWithVariousDataTypes(): void
    {
        $stack = new ArrayStack();
        $stack->push(123);
        $stack->push('string');
        $stack->push([1, 2, 3]);
        $stack->push(new \stdClass());

        $this->assertInstanceOf(\stdClass::class, $stack->pop());
        $this->assertSame([1, 2, 3], $stack->pop());
        $this->assertSame('string', $stack->pop());
        $this->assertSame(123, $stack->pop());
    }

    // Test stack behavior after mixed operations
    public function testStackBehaviorAfterMixedOperations(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $stack->push(2);
        $stack->pop();
        $stack->push(3);
        $stack->clear();
        $stack->push(4);
        $stack->push(5);

        $this->assertSame(5, $stack->pop());
        $this->assertSame(4, $stack->peek());
    }

    // Test LIFO behavior
    public function testStackFollowsLifoOrder(): void
    {
        $stack = new ArrayStack();
        $stack->push(1);
        $stack->push(2);
        $stack->push(3);

        $this->assertSame(3, $stack->pop());
        $this->assertSame(2, $stack->pop());
        $this->assertSame(1, $stack->pop());
    }
}
