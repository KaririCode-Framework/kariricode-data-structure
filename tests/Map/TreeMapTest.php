<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Map;

use KaririCode\DataStructure\Map\TreeMap;
use KaririCode\DataStructure\TreeMapNode;
use PHPUnit\Framework\TestCase;

final class TreeMapTest extends TestCase
{
    private TreeMap $treeMap;

    protected function setUp(): void
    {
        parent::setUp();
        $this->treeMap = new TreeMap();
    }

    // Test inserting into an empty tree.
    public function testInsertIntoEmptyTree(): void
    {
        $this->treeMap->put(10, 'ten');

        $root = $this->getRootNode();
        $this->assertNotNull($root);
        $this->assertSame(10, $root->key);
        $this->assertSame('ten', $root->value);
        $this->assertTrue($root->isBlack());
    }

    // Test inserting a second node as a right child.
    public function testInsertSecondNodeRightChild(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(20, 'twenty');

        $root = $this->getRootNode();
        $this->assertNotNull($root);
        $this->assertSame(10, $root->key);
        $this->assertSame('ten', $root->value);
        $this->assertTrue($root->isBlack());

        $rightChild = $root->right;
        $this->assertNotNull($rightChild);
        $this->assertSame(20, $rightChild->key);
        $this->assertSame('twenty', $rightChild->value);
        $this->assertTrue($rightChild->isRed());
    }

    // Test inserting a second node as a left child.
    public function testInsertSecondNodeLeftChild(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(5, 'five');

        $root = $this->getRootNode();
        $this->assertNotNull($root);
        $this->assertSame(10, $root->key);
        $this->assertSame('ten', $root->value);
        $this->assertTrue($root->isBlack());

        $leftChild = $root->left;
        $this->assertNotNull($leftChild);
        $this->assertSame(5, $leftChild->key);
        $this->assertSame('five', $leftChild->value);
        $this->assertTrue($leftChild->isRed());
    }

    // Test inserting a duplicate key.
    public function testInsertDuplicateKey(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(10, 'new-ten');

        $root = $this->getRootNode();
        $this->assertNotNull($root);
        $this->assertSame(10, $root->key);
        $this->assertSame('new-ten', $root->value);
        $this->assertTrue($root->isBlack());
    }

    // Test putting and getting values.
    public function testPutAndGet(): void
    {
        $this->treeMap->put(5, 'five');
        $this->treeMap->put(3, 'three');
        $this->treeMap->put(7, 'seven');

        $this->assertEquals('five', $this->treeMap->get(5));
        $this->assertEquals('three', $this->treeMap->get(3));
        $this->assertEquals('seven', $this->treeMap->get(7));
        $this->assertNull($this->treeMap->get(10));
    }

    // Test removing elements.
    public function testRemove(): void
    {
        $this->treeMap->put(5, 'five');
        $this->treeMap->put(3, 'three');
        $this->treeMap->put(7, 'seven');

        $this->assertTrue($this->treeMap->remove(3));
        $this->assertNull($this->treeMap->get(3));
        $this->assertFalse($this->treeMap->remove(10));
    }

    // Test tree balancing after insertion.
    public function testBalancingAfterInsertion(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(20, 'twenty');
        $this->treeMap->put(30, 'thirty');

        $treeStructure = $this->getTreeStructure();
        $this->assertStringContainsString('20:twenty [BLACK]', $treeStructure);
        $this->assertStringContainsString('10:ten [RED]', $treeStructure);
        $this->assertStringContainsString('30:thirty [RED]', $treeStructure);
    }

    // Test tree balancing after removal.
    public function testBalancingAfterRemoval(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(5, 'five');
        $this->treeMap->put(20, 'twenty');
        $this->treeMap->put(15, 'fifteen');
        $this->treeMap->put(30, 'thirty');

        $this->treeMap->remove(5);

        $treeStructure = $this->getTreeStructure();
        $this->assertStringContainsString('20:twenty [BLACK]', $treeStructure);
        $this->assertStringContainsString('10:ten [BLACK]', $treeStructure);
        $this->assertStringContainsString('30:thirty [BLACK]', $treeStructure);
        $this->assertStringContainsString('15:fifteen [RED]', $treeStructure);
    }

    // Test complex operations on the tree.
    public function testComplexOperations(): void
    {
        $operations = [
            [5, 'five'],
            [2, 'two'],
            [7, 'seven'],
            [1, 'one'],
            [3, 'three'],
            [6, 'six'],
            [8, 'eight'],
        ];

        foreach ($operations as [$key, $value]) {
            $this->treeMap->put($key, $value);
        }

        $this->treeMap->remove(2);
        $this->treeMap->remove(7);

        $expectedStructure = <<<TREE
    ├── 5:five [BLACK]
    │   ├── 3:three [BLACK]
    │   │   ├── 1:one [RED]
    │   └── 8:eight [BLACK]
    │       ├── 6:six [RED]

    TREE;

        $this->assertEquals($expectedStructure, $this->getTreeStructure());
    }

    // Test edge cases for the tree operations.
    public function testEdgeCases(): void
    {
        // Test empty tree
        $this->assertNull($this->treeMap->get(1));
        $this->assertFalse($this->treeMap->remove(1));

        // Test single node
        $this->treeMap->put(1, 'one');
        $this->assertEquals('one', $this->treeMap->get(1));
        $this->assertTrue($this->treeMap->remove(1));
        $this->assertNull($this->treeMap->get(1));

        // Test duplicate keys
        $this->treeMap->put(1, 'one');
        $this->treeMap->put(1, 'uno');
        $this->assertEquals('uno', $this->treeMap->get(1));
    }

    // Test operations on a large data set.
    public function testLargeDataSet(): void
    {
        $data = range(1, 1000);
        shuffle($data);

        foreach ($data as $key) {
            $this->treeMap->put($key, "value_$key");
        }

        $this->assertEquals(1000, $this->countNodes());

        foreach ($data as $key) {
            $this->assertEquals("value_$key", $this->treeMap->get($key));
        }

        shuffle($data);
        $halfData = array_slice($data, 0, 500);

        foreach ($halfData as $key) {
            $this->assertTrue($this->treeMap->remove($key));
        }

        $this->assertEquals(500, $this->countNodes());

        foreach ($data as $key) {
            if (in_array($key, $halfData)) {
                $this->assertNull($this->treeMap->get($key));
            } else {
                $this->assertEquals("value_$key", $this->treeMap->get($key));
            }
        }
    }

    // Test transplanting when U is the root node.
    public function testTransplantWhenUIsRoot(): void
    {
        $this->treeMap->put(10, 'ten');

        $reflection = new \ReflectionClass(TreeMap::class);
        $rootProperty = $reflection->getProperty('root');
        $rootProperty->setAccessible(true);

        $u = $rootProperty->getValue($this->treeMap);
        $v = new TreeMapNode(20, 'twenty');

        $transplantMethod = $reflection->getMethod('transplant');
        $transplantMethod->setAccessible(true);

        $transplantMethod->invoke($this->treeMap, $u, $v);

        $newRoot = $rootProperty->getValue($this->treeMap);
        $this->assertSame($v, $newRoot);
        $this->assertNull($v->parent);
    }

    // Test balance before removal break condition.
    public function testBalanceBeforeRemovalBreakCondition(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(5, 'five');

        $reflection = new \ReflectionClass(TreeMap::class);
        $rootProperty = $reflection->getProperty('root');
        $rootProperty->setAccessible(true);

        $root = $rootProperty->getValue($this->treeMap);

        $node = $root->left;
        $node->setBlack();
        $node->parent = null;

        $balanceBeforeRemovalMethod = $reflection->getMethod('balanceBeforeRemoval');
        $balanceBeforeRemovalMethod->setAccessible(true);
        $balanceBeforeRemovalMethod->invoke($this->treeMap, $node);

        $this->assertTrue(true);
    }

    // Test deleting the root node with a single child.
    public function testDeleteRootNodeWithSingleChild(): void
    {
        $this->treeMap->put(10, 'ten');
        $this->treeMap->put(5, 'five');

        $reflection = new \ReflectionClass(TreeMap::class);
        $rootProperty = $reflection->getProperty('root');
        $rootProperty->setAccessible(true);

        $root = $rootProperty->getValue($this->treeMap);
        $node = $root->left;

        $deleteNodeMethod = $reflection->getMethod('deleteNode');
        $deleteNodeMethod->setAccessible(true);

        $deleteNodeMethod->invoke($this->treeMap, $root);

        $newRoot = $rootProperty->getValue($this->treeMap);
        $this->assertSame($node, $newRoot);
        $this->assertTrue($newRoot->isBlack());
        $this->assertNull($newRoot->parent);
    }

    // Helper method to get the root node of the tree.
    private function getRootNode(): ?TreeMapNode
    {
        $reflection = new \ReflectionClass($this->treeMap);
        $rootProperty = $reflection->getProperty('root');
        $rootProperty->setAccessible(true);

        return $rootProperty->getValue($this->treeMap);
    }

    // Helper method to count the nodes in the tree.
    private function countNodes(): int
    {
        $reflection = new \ReflectionClass($this->treeMap);
        $rootProperty = $reflection->getProperty('root');
        $rootProperty->setAccessible(true);
        $root = $rootProperty->getValue($this->treeMap);

        return $this->countNodesRecursive($root);
    }

    // Recursive method to count nodes in the tree.
    private function countNodesRecursive(?TreeMapNode $node): int
    {
        if (null === $node) {
            return 0;
        }

        return 1 + $this->countNodesRecursive($node->left) + $this->countNodesRecursive($node->right);
    }

    // Helper method to get the tree structure as a string.
    private function getTreeStructure(): string
    {
        $reflection = new \ReflectionClass($this->treeMap);
        $rootProperty = $reflection->getProperty('root');
        $rootProperty->setAccessible(true);
        $root = $rootProperty->getValue($this->treeMap);

        return $this->printTree($root);
    }

    // Recursive method to print the tree structure.
    private function printTree(?TreeMapNode $node, string $prefix = '', bool $isLeft = true): string
    {
        if (null === $node) {
            return '';
        }

        $result = $prefix;

        if ('' === $prefix) {
            $result .= '├── ';
        } else {
            $result .= $isLeft ? '├── ' : '└── ';
        }

        $result .= $node->key . ':' . $node->value . ' [' . ($node->isRed() ? 'RED' : 'BLACK') . "]\n";

        $newPrefix = $prefix . ($isLeft ? '│   ' : '    ');
        $result .= $this->printTree($node->left, $newPrefix, true);
        $result .= $this->printTree($node->right, $newPrefix, false);

        return $result;
    }
}
