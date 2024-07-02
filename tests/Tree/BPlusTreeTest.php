<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Tree;

use KaririCode\Contract\DataStructure\Structural\Collection;
use KaririCode\DataStructure\Tree\BPlusTree;
use PHPUnit\Framework\TestCase;

final class BPlusTreeTest extends TestCase
{
    private BPlusTree $tree;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tree = new BPlusTree(3); // B+ Tree of order 3
    }

    // public function testGetThrowsOutOfRangeException(): void
    // {
    //     $this->expectException(\OutOfRangeException::class);
    //     $this->tree->get(0);
    // }

    // Test insertion and root splitting
    public function testInsertAndSearch(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->insert(3, 'c');

        // Test search by key
        $this->assertSame('a', $this->tree->find(1));
        $this->assertSame('b', $this->tree->find(2));
        $this->assertSame('c', $this->tree->find(3));

        // Test search by value
        $this->assertSame(1, $this->tree->find('a'));
        $this->assertSame(2, $this->tree->find('b'));
        $this->assertSame(3, $this->tree->find('c'));
    }



    // Test removal and balancing
    public function testRemoveAndBalance(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->insert(3, 'c');
        $this->tree->remove(2);

        $this->assertFalse($this->tree->contains(2));
        $this->assertTrue($this->tree->contains(1));
        $this->assertTrue($this->tree->contains(3));
    }

    // Test clear method
    public function testClear(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->clear();

        $this->assertSame(0, $this->tree->size());
        $this->assertNull($this->tree->find(1));
        $this->assertNull($this->tree->find(2));
    }

    // Test contains method
    public function testContains(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');

        $this->assertTrue($this->tree->contains(1));
        $this->assertTrue($this->tree->contains(2));
        $this->assertFalse($this->tree->contains(3));
    }

    // Test find method
    public function testFind(): void
    {
        $this->tree->insert(1, 'a');
        $this->assertSame('a', $this->tree->find(1));
        $this->assertNull($this->tree->find(2));
    }

    // Test get method
    public function testGet(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->insert(3, 'c');

        $this->assertSame('a', $this->tree->get(0));
        $this->assertSame('b', $this->tree->get(1));
        $this->assertSame('c', $this->tree->get(2));
    }

    // Test set method
    public function testSet(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->set(1, 'new-a');

        $this->assertSame('new-a', $this->tree->find(1));
        $this->assertSame('b', $this->tree->find(2));
    }

    // Test size method
    public function testSize(): void
    {
        $this->assertSame(0, $this->tree->size());
        $this->tree->insert(1, 'a');
        $this->assertSame(1, $this->tree->size());
    }

    // Test getItems method
    public function testGetItems(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->insert(3, 'c');

        $this->assertSame(['a', 'b', 'c'], $this->tree->getItems());
    }

    // Test addAll method
    public function testAddAll(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->method('getItems')->willReturn([1, 2, 3]);

        $this->tree->addAll($collection);

        $this->assertSame(3, $this->tree->size());
    }

    // Test getOrder method
    public function testGetOrder(): void
    {
        $this->assertSame(3, $this->tree->getOrder());
    }

    // Test rangeSearch method
    public function testRangeSearch(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->insert(3, 'c');

        $this->assertSame(['a', 'b'], $this->tree->rangeSearch(1, 2));
    }

    // Test getMinimum method
    public function testGetMinimum(): void
    {
        $this->tree->insert(2, 'b');
        $this->tree->insert(1, 'a');
        $this->tree->insert(3, 'c');

        $this->assertSame('a', $this->tree->getMinimum());
    }

    // Test getMaximum method
    public function testGetMaximum(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(3, 'c');
        $this->tree->insert(2, 'b');

        $this->assertSame('c', $this->tree->getMaximum());
    }

    // Test balance method
    public function testBalance(): void
    {
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->balance();

        $this->assertSame(['a', 'b'], $this->tree->getItems());
    }

    // Test sort method
    public function testSort(): void
    {
        $this->tree->insert(3, 'c');
        $this->tree->insert(1, 'a');
        $this->tree->insert(2, 'b');
        $this->tree->sort();

        $this->assertSame(['a', 'b', 'c'], $this->tree->getItems());
    }
}
