<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Tree;

use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeInternalNode;
use KaririCode\DataStructure\Tree\BPlusTreeNode\BPlusTreeLeafNode;
use PHPUnit\Framework\TestCase;

final class BPlusTreeInternalNodeTest extends TestCase
{
    public function testInsert(): void
    {
        $internalNode = new BPlusTreeInternalNode(4);
        $leafNode1 = new BPlusTreeLeafNode(4);
        $leafNode2 = new BPlusTreeLeafNode(4);

        $leafNode1->insert(10, 'value10');
        $leafNode1->insert(20, 'value20');
        $leafNode2->insert(30, 'value30');
        $leafNode2->insert(40, 'value40');

        $internalNode->keys = [30];
        $internalNode->children = [$leafNode1, $leafNode2];

        /** @var BPlusTreeInternalNode $result */
        $result = $internalNode->insert(25, 'value25');

        $this->assertInstanceOf(BPlusTreeInternalNode::class, $result);
        $this->assertSame([25, 30], $result->keys);
        $this->assertCount(3, $result->children);
    }

    public function testRemove(): void
    {
        $internalNode = new BPlusTreeInternalNode(4);
        $leafNode1 = new BPlusTreeLeafNode(4);
        $leafNode2 = new BPlusTreeLeafNode(4);

        $leafNode1->insert(10, 'value10');
        $leafNode1->insert(20, 'value20');
        $leafNode2->insert(30, 'value30');
        $leafNode2->insert(40, 'value40');

        $internalNode->keys = [30];
        $internalNode->children = [$leafNode1, $leafNode2];

        $result = $internalNode->remove(20);
        $this->assertTrue($result);

        $this->assertSame([30], $internalNode->keys);
        $this->assertCount(2, $internalNode->children);
        $this->assertSame([10], $internalNode->children[0]->keys);
    }

    public function testSearch(): void
    {
        $internalNode = new BPlusTreeInternalNode(4);
        $leafNode1 = new BPlusTreeLeafNode(4);
        $leafNode2 = new BPlusTreeLeafNode(4);

        $leafNode1->insert(10, 'value10');
        $leafNode1->insert(20, 'value20');
        $leafNode2->insert(30, 'value30');
        $leafNode2->insert(40, 'value40');

        $internalNode->keys = [30];
        $internalNode->children = [$leafNode1, $leafNode2];

        $result = $internalNode->search(30);
        $this->assertSame('value30', $result);
    }
}
