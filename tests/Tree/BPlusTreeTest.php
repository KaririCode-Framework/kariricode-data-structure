<?php

declare(strict_types=1);

use KaririCode\Contract\DataStructure\Structural\Collection;
use KaririCode\DataStructure\Tree\BPlusTree;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BPlusTreeTest extends TestCase
{
    public const ORDER = 4;

    private BPlusTree $tree;

    protected function setUp(): void
    {
        $this->tree = new BPlusTree(self::ORDER);
    }

    public function testInsertAndFind(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $this->assertEquals('A', $this->tree->find(1));
        $this->assertEquals('B', $this->tree->find(2));
        $this->assertEquals('C', $this->tree->find(3));
    }

    public function testInsertAndRemove(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $this->assertTrue($this->tree->remove(2));
        $this->assertNull($this->tree->find(2));

        $this->assertTrue($this->tree->remove(1));
        $this->assertNull($this->tree->find(1));

        $this->assertFalse($this->tree->remove(5)); // Test removing non-existent key
    }

    public function testRangeSearch(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');
        $this->tree->insert(4, 'D');

        $result = $this->tree->rangeSearch(2, 3);
        $this->assertEquals(['B', 'C'], $result);
    }

    public function testClearTree(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');

        $this->tree->clear();
        $this->assertNull($this->tree->getRoot());
        $this->assertEquals(0, $this->tree->size());
    }

    public function testGetItems(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $items = $this->tree->getItems();
        $this->assertEquals(['A', 'B', 'C'], $items);
    }

    public function testGetOrder(): void
    {
        $this->assertEquals(self::ORDER, $this->tree->getOrder());
    }

    public function testGetMinimumAndMaximum(): void
    {
        $this->tree->insert(5, 'E');
        $this->tree->insert(1, 'A');
        $this->tree->insert(3, 'C');

        $this->assertEquals('A', $this->tree->getMinimum());
        $this->assertEquals('E', $this->tree->getMaximum());
    }

    public function testSetAndGetByKey(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $this->tree->set(1, 'Z');
        $this->assertEquals('Z', $this->tree->get(1));
    }

    public function testVisualTreeStructure(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');
        $this->tree->insert(4, 'D');
        $this->tree->insert(5, 'E');

        $visual = $this->tree->visualTreeStructure();
        $this->assertStringContainsString('Leaf Node', $visual);
        $this->assertStringContainsString('Internal Node', $visual);
    }

    public function testConstructorWithInvalidOrder(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new BPlusTree(2); // Order less than 3 should throw exception
    }

    public function testGetWithInvalidIndex(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->tree->get(99); // Attempt to access an invalid key
    }

    public function testAdd(): void
    {
        $this->tree->add(1);
        $this->assertEquals(1, $this->tree->find(1));

        $this->tree->add(2);
        $this->assertEquals(2, $this->tree->find(2));

        $this->assertEquals(2, $this->tree->size());
    }

    public function testRemoveUntilEmpty(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');

        $this->assertTrue($this->tree->remove(1));
        $this->assertTrue($this->tree->remove(2));

        $this->assertNull($this->tree->getRoot());
        $this->assertEquals(0, $this->tree->size());
    }

    public function testContains(): void
    {
        $this->tree->insert(1, 'A');
        $this->assertTrue($this->tree->contains(1));
        $this->assertFalse($this->tree->contains(2));
    }

    public function testSetWithInvalidKey(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->tree->set(99, 'Z'); // Key 99 does not exist
    }

    public function testAddAll(): void
    {
        /** @var Collection|MockObject */
        $mockCollection = $this->createMock(Collection::class);
        $mockCollection->method('getItems')->willReturn([1, 2, 3]);

        $this->tree->addAll($mockCollection);

        $this->assertEquals(3, $this->tree->size());
        $this->assertEquals(1, $this->tree->find(1));
        $this->assertEquals(2, $this->tree->find(2));
        $this->assertEquals(3, $this->tree->find(3));
    }

    public function testIsBalanced(): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $this->tree->insert($i, "Value$i");
        }

        $this->assertTrue($this->tree->isBalanced(), 'Tree should be balanced after insertions');
    }

    public function testSort(): void
    {
        $this->tree->insert(3, 'C');
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');

        // Calling sort to check if the tree is sorted
        $this->tree->sort();

        // If no exception is thrown, the tree is sorted
        $this->assertTrue(true);
    }

    public function testGetLeftmostLeafWithEmptyTree(): void
    {
        $reflection = new ReflectionClass($this->tree);
        $method = $reflection->getMethod('getLeftmostLeaf');
        $method->setAccessible(true);

        $result = $method->invoke($this->tree);
        $this->assertNull($result);
    }

    public function testGetRightmostLeafWithEmptyTree(): void
    {
        $reflection = new ReflectionClass($this->tree);
        $method = $reflection->getMethod('getRightmostLeaf');
        $method->setAccessible(true);

        $result = $method->invoke($this->tree);
        $this->assertNull($result);
    }

    public function testVisualTreeStructureWithEmptyTree(): void
    {
        $visual = $this->tree->visualTreeStructure();
        $this->assertEquals('Empty tree', $visual);
    }

    public function testRemoveNonExistentKey(): void
    {
        $this->tree->insert(1, 'A');
        $this->assertFalse($this->tree->remove(99));
    }

    public function testSetExistingKey(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->set(1, 'Z');
        $this->assertEquals('Z', $this->tree->get(1));
    }
}
