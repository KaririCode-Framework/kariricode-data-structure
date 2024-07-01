<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests;

use KaririCode\Contract\DataStructure\Behavioral\Comparable;
use KaririCode\DataStructure\Heap\BinaryHeap;
use PHPUnit\Framework\TestCase;

final class BinaryHeapTest extends TestCase
{
    public function testAdd(): void
    {
        $heap = new BinaryHeap();
        $heap->add(3);
        $heap->add(1);
        $heap->add(2);

        $this->assertSame(1, $heap->peek());
    }

    public function testPoll(): void
    {
        $heap = new BinaryHeap();
        $heap->add(3);
        $heap->add(1);
        $heap->add(2);

        $this->assertSame(1, $heap->poll());
        $this->assertSame(2, $heap->poll());
        $this->assertSame(3, $heap->poll());
        $this->assertNull($heap->poll());
    }

    public function testHeapifyDown(): void
    {
        $heap = new BinaryHeap();
        $heap->add(5);
        $heap->add(3);
        $heap->add(8);
        $heap->add(1);
        $heap->add(6);

        $this->assertSame(1, $heap->poll());
        $this->assertSame(3, $heap->poll());
        $this->assertSame(5, $heap->poll());
        $this->assertSame(6, $heap->poll());
        $this->assertSame(8, $heap->poll());
    }

    public function testHeapifyDownComplex(): void
    {
        $heap = new BinaryHeap();
        $heap->add(10);
        $heap->add(15);
        $heap->add(20);
        $heap->add(17);
        $heap->add(25);

        $this->assertSame(10, $heap->poll());
        $this->assertSame(15, $heap->poll());
        $this->assertSame(17, $heap->poll());
        $this->assertSame(20, $heap->poll());
        $this->assertSame(25, $heap->poll());
    }

    public function testCompare(): void
    {
        $minHeap = new BinaryHeap('min');
        $minHeap->add(10);
        $minHeap->add(5);
        $minHeap->add(20);
        $this->assertSame(5, $minHeap->poll());
        $this->assertSame(10, $minHeap->poll());
        $this->assertSame(20, $minHeap->poll());

        $maxHeap = new BinaryHeap('max');
        $maxHeap->add(10);
        $maxHeap->add(5);
        $maxHeap->add(20);
        $this->assertSame(20, $maxHeap->poll());
        $this->assertSame(10, $maxHeap->poll());
        $this->assertSame(5, $maxHeap->poll());
    }

    public function testRemove(): void
    {
        $heap = new BinaryHeap();
        $heap->add(3);
        $heap->add(1);
        $heap->add(2);

        $this->assertTrue($heap->remove(1));
        $this->assertFalse($heap->remove(4));
        $this->assertSame(2, $heap->peek());
    }

    public function testPeek(): void
    {
        $heap = new BinaryHeap();
        $this->assertNull($heap->peek());

        $heap->add(3);
        $this->assertSame(3, $heap->peek());

        $heap->add(1);
        $this->assertSame(1, $heap->peek());
    }

    public function testSize(): void
    {
        $heap = new BinaryHeap();
        $this->assertSame(0, $heap->size());

        $heap->add(3);
        $this->assertSame(1, $heap->size());

        $heap->add(1);
        $heap->add(2);
        $this->assertSame(3, $heap->size());

        $heap->poll();
        $this->assertSame(2, $heap->size());
    }

    public function testIsEmpty(): void
    {
        $heap = new BinaryHeap();
        $this->assertTrue($heap->isEmpty());

        $heap->add(3);
        $this->assertFalse($heap->isEmpty());

        $heap->poll();
        $this->assertTrue($heap->isEmpty());
    }

    public function testInsert(): void
    {
        $heap = new BinaryHeap();
        $heap->add(3);
        $heap->add(1);
        $heap->add(2);

        $heap->insert(1, 0);
        $this->assertSame(0, $heap->peek());

        $this->expectException(\OutOfRangeException::class);
        $heap->insert(-1, 4);
    }

    public function testComparableElements(): void
    {
        $element1 = $this->createMock(Comparable::class);
        $element2 = $this->createMock(Comparable::class);
        $element3 = $this->createMock(Comparable::class);

        $element1->method('compareTo')->willReturn(1);
        $element2->method('compareTo')->willReturn(0);
        $element3->method('compareTo')->willReturn(-1);

        $heap = new BinaryHeap('max');
        $heap->add($element1);
        $heap->add($element2);
        $heap->add($element3);

        $this->assertSame($element1, $heap->peek());
    }

    public function testHeapifyDownWithRightChildComparison(): void
    {
        $heap = new BinaryHeap();
        $heap->add(10);
        $heap->add(15);
        $heap->add(20);
        $heap->add(17);
        $heap->add(8);
        $heap->add(25);

        $this->assertSame(8, $heap->poll());
        $this->assertSame(10, $heap->poll());
        $this->assertSame(15, $heap->poll());
        $this->assertSame(17, $heap->poll());
        $this->assertSame(20, $heap->poll());
        $this->assertSame(25, $heap->poll());
    }

    public function testCompareWithMinType(): void
    {
        $heap = new BinaryHeap('min');

        $element1 = $this->createMock(Comparable::class);
        $element2 = $this->createMock(Comparable::class);

        $element1->method('compareTo')->willReturn(1);
        $element2->method('compareTo')->willReturn(-1);

        $reflection = new \ReflectionClass($heap);
        $method = $reflection->getMethod('compare');
        $method->setAccessible(true);

        $result1 = $method->invokeArgs($heap, [$element1, $element2]);
        $result2 = $method->invokeArgs($heap, [$element2, $element1]);

        $this->assertFalse($result1);
        $this->assertTrue($result2);
    }

    public function testCompareWithMaxType(): void
    {
        $heap = new BinaryHeap('max');

        $element1 = $this->createMock(Comparable::class);
        $element2 = $this->createMock(Comparable::class);

        $element1->method('compareTo')->willReturn(1);
        $element2->method('compareTo')->willReturn(-1);

        $reflection = new \ReflectionClass($heap);
        $method = $reflection->getMethod('compare');
        $method->setAccessible(true);

        $result1 = $method->invokeArgs($heap, [$element1, $element2]);
        $result2 = $method->invokeArgs($heap, [$element2, $element1]);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }
}
