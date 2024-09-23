<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Tree;

use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeLeafNode;
use PHPUnit\Framework\TestCase;

final class BPlusTreeLeafNodeTest extends TestCase
{
    public function testInsert(): void
    {
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->insert(10, 'value10');
        $leafNode->insert(20, 'value20');

        $this->assertSame([10, 20], $leafNode->keys);
        $this->assertSame(['value10', 'value20'], $leafNode->values);
    }

    public function testInsertDuplicates(): void
    {
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->insert(10, 'value10');
        $leafNode->insert(10, 'newValue10');

        $this->assertSame('newValue10', $leafNode->search(10));
    }

    public function testInsertAndSearch()
    {
        $order = 4;
        $leafNode = new BPlusTreeLeafNode($order);

        // Inserindo chaves e valores
        $leafNode->insert(10, 'value10');
        $leafNode->insert(20, 'value20');
        $leafNode->insert(30, 'value30');

        // Testando busca
        $this->assertEquals('value10', $leafNode->search(10));
        $this->assertEquals('value20', $leafNode->search(20));
        $this->assertNull($leafNode->search(40));
    }

    public function testRemove(): void
    {
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->insert(10, 'value10');
        $leafNode->insert(20, 'value20');

        $result = $leafNode->remove(10);

        $this->assertTrue($result);
        $this->assertSame([20], $leafNode->keys);
        $this->assertSame(['value20'], $leafNode->values);
    }

    public function testRemoveNonExistent(): void
    {
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->insert(10, 'value10');

        $result = $leafNode->remove(20);

        $this->assertFalse($result);
        $this->assertSame([10], $leafNode->keys);
        $this->assertSame(['value10'], $leafNode->values);
    }

    public function testSearch(): void
    {
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->insert(10, 'value10');
        $leafNode->insert(20, 'value20');

        $result = $leafNode->search(20);

        $this->assertSame('value20', $result);
    }

    public function testSearchNonExistent(): void
    {
        $leafNode = new BPlusTreeLeafNode(4);
        $leafNode->insert(10, 'value10');

        $result = $leafNode->search(20);

        $this->assertNull($result);
    }
}
