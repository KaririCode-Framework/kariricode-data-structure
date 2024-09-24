<?php

declare(strict_types=1);

use KaririCode\DataStructure\Tree\BPlusTree;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeLeafNode;
use KaririCode\DataStructure\Tree\BPlusTreeSearcher;
use PHPUnit\Framework\TestCase;

final class BPlusTreeSearcherTest extends TestCase
{
    private BPlusTree $tree;
    private BPlusTreeSearcher $searcher;

    protected function setUp(): void
    {
        $this->tree = new BPlusTree(4); // Using order 4 for the tree
        $this->searcher = new BPlusTreeSearcher();
    }

    public function testFindByKey(): void
    {
        // Insert elements into the tree
        $this->tree->insert(10, 'Value10');
        $this->tree->insert(20, 'Value20');
        $this->tree->insert(30, 'Value30');
        $this->tree->insert(40, 'Value40');

        // Test finding existing keys
        $result = $this->searcher->find($this->tree, 20);
        $this->assertEquals('Value20', $result);

        $result = $this->searcher->find($this->tree, 40);
        $this->assertEquals('Value40', $result);

        // Test finding a non-existing key
        $result = $this->searcher->find($this->tree, 50);
        $this->assertNull($result);
    }

    public function testFindByValue(): void
    {
        // Insert elements into the tree
        $this->tree->insert(5, 'Value5');
        $this->tree->insert(15, 'Value15');
        $this->tree->insert(25, 'Value25');
        $this->tree->insert(35, 'Value35');

        // Test finding existing values
        $result = $this->searcher->find($this->tree, 'Value15');
        $this->assertEquals(15, $result);

        $result = $this->searcher->find($this->tree, 'Value35');
        $this->assertEquals(35, $result);

        // Test finding a non-existing value
        $result = $this->searcher->find($this->tree, 'Value50');
        $this->assertNull($result);
    }

    public function testRangeSearch(): void
    {
        // Insert elements into the tree
        for ($i = 1; $i <= 20; ++$i) {
            $this->tree->insert($i * 5, 'Value' . ($i * 5));
        }

        // Perform a range search
        $result = $this->searcher->rangeSearch($this->tree, 30, 70);

        // Expected values are from 30 to 70 (inclusive)
        $expected = ['Value30', 'Value35', 'Value40', 'Value45', 'Value50', 'Value55', 'Value60', 'Value65', 'Value70'];

        $this->assertEquals($expected, $result);

        // Test an empty range
        $result = $this->searcher->rangeSearch($this->tree, 105, 110);
        $this->assertEmpty($result);
    }

    public function testSearchInLeafNode(): void
    {
        // Directly test the private method searchInLeafNode using reflection
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->keys = [10, 20, 30];
        $leafNode->values = ['Value10', 'Value20', 'Value30'];

        // Use reflection to access the private method
        $reflection = new ReflectionClass(BPlusTreeSearcher::class);
        $method = $reflection->getMethod('searchInLeafNode');
        $method->setAccessible(true);

        // Test finding an existing value
        $index = $method->invokeArgs($this->searcher, [$leafNode, 'Value20']);
        $this->assertEquals(20, $index);

        // Test finding a non-existing value
        $index = $method->invokeArgs($this->searcher, [$leafNode, 'Value50']);
        $this->assertNull($index);
    }

    public function testCompareValues(): void
    {
        // Directly test the private method compareValues using reflection
        $reflection = new ReflectionClass(BPlusTreeSearcher::class);
        $method = $reflection->getMethod('compareValues');
        $method->setAccessible(true);

        // Test comparison of scalars
        $result = $method->invokeArgs($this->searcher, [10, 10]);
        $this->assertTrue($result);

        $result = $method->invokeArgs($this->searcher, [10, 20]);
        $this->assertFalse($result);

        // Test comparison of objects
        $obj1 = (object) ['a' => 1];
        $obj2 = (object) ['a' => 1];
        $obj3 = (object) ['a' => 2];

        $result = $method->invokeArgs($this->searcher, [$obj1, $obj2]);
        $this->assertTrue($result);

        $result = $method->invokeArgs($this->searcher, [$obj1, $obj3]);
        $this->assertFalse($result);
    }

    public function testFindFirstLeafNode(): void
    {
        // Create a tree with multiple levels
        for ($i = 1; $i <= 50; ++$i) {
            $this->tree->insert($i, "Value$i");
        }

        // Use reflection to access the private method
        $reflection = new ReflectionClass(BPlusTreeSearcher::class);
        $method = $reflection->getMethod('findFirstLeafNode');
        $method->setAccessible(true);

        $firstLeaf = $method->invokeArgs($this->searcher, [$this->tree->getRoot()]);
        $this->assertInstanceOf(BPlusTreeLeafNode::class, $firstLeaf);

        // The first leaf node should contain the smallest keys
        $this->assertContains(1, $firstLeaf->keys);
    }

    public function testSearchByValueWithNonExistingValue(): void
    {
        // Insert elements into the tree
        $this->tree->insert(100, 'Value100');
        $this->tree->insert(200, 'Value200');

        // Test searching for a non-existing value
        $result = $this->searcher->find($this->tree, 'NonExistingValue');
        $this->assertNull($result);
    }

    public function testSearchWithEmptyTree(): void
    {
        // Create an empty tree
        $emptyTree = new BPlusTree(4);

        // Test searching in an empty tree
        $result = $this->searcher->find($emptyTree, 10);
        $this->assertNull($result);

        $result = $this->searcher->rangeSearch($emptyTree, 10, 20);
        $this->assertEmpty($result);
    }

    public function testFindWithNullValue(): void
    {
        // Insert elements with null values
        $this->tree->insert(1, null);
        $this->tree->insert(2, 'Value2');

        // Test finding a key with a null value
        $result = $this->searcher->find($this->tree, null);
        $this->assertEquals(1, $result);
    }

    public function testSearchWithDuplicateValues(): void
    {
        // Insert elements with duplicate values
        $this->tree->insert(1, 'DuplicateValue');
        $this->tree->insert(2, 'DuplicateValue');
        $this->tree->insert(3, 'UniqueValue');

        // Test finding by value (should return the first key that matches)
        $result = $this->searcher->find($this->tree, 'DuplicateValue');
        $this->assertEquals(1, $result);

        // Test that the search continues correctly after duplicates
        $result = $this->searcher->find($this->tree, 'UniqueValue');
        $this->assertEquals(3, $result);
    }

    public function testRangeSearchWithSingleElementRange(): void
    {
        // Insert elements into the tree
        $this->tree->insert(10, 'Value10');
        $this->tree->insert(20, 'Value20');

        // Perform a range search where start and end are the same
        $result = $this->searcher->rangeSearch($this->tree, 20, 20);
        $this->assertEquals(['Value20'], $result);
    }

    public function testFindWithEmptyTree(): void
    {
        // Create an empty tree
        $emptyTree = new BPlusTree(4);

        // Ensure the root is null
        $this->assertNull($emptyTree->getRoot());

        // Test finding an element in an empty tree
        $result = $this->searcher->find($emptyTree, 10);
        $this->assertNull($result);
    }
}
