<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Map;

use KaririCode\DataStructure\TreeMapNode;
use PHPUnit\Framework\TestCase;

final class TreeMapNodeTest extends TestCase
{
    private TreeMapNode $node;

    protected function setUp(): void
    {
        parent::setUp();
        $this->node = new TreeMapNode(10, 'ten');
    }

    // Test node initialization.
    public function testNodeInitialization(): void
    {
        $this->assertSame(10, $this->node->key);
        $this->assertSame('ten', $this->node->value);
        $this->assertTrue($this->node->isRed());
        $this->assertNull($this->node->left);
        $this->assertNull($this->node->right);
        $this->assertNull($this->node->parent);
    }

    // Test setting node color to red.
    public function testSetRed(): void
    {
        $this->node->setRed();
        $this->assertTrue($this->node->isRed());
    }

    // Test setting node color to black.
    public function testSetBlack(): void
    {
        $this->node->setBlack();
        $this->assertTrue($this->node->isBlack());
    }

    // Test setting left child node.
    public function testSetLeft(): void
    {
        $leftChild = new TreeMapNode(5, 'five');
        $this->node->setLeft($leftChild);
        $this->assertSame($leftChild, $this->node->left);
        $this->assertSame($this->node, $leftChild->parent);
    }

    // Test setting right child node.
    public function testSetRight(): void
    {
        $rightChild = new TreeMapNode(15, 'fifteen');
        $this->node->setRight($rightChild);
        $this->assertSame($rightChild, $this->node->right);
        $this->assertSame($this->node, $rightChild->parent);
    }

    // Test removing node from parent.
    public function testRemoveFromParent(): void
    {
        $parent = new TreeMapNode(20, 'twenty');
        $this->node->parent = $parent;
        $parent->left = $this->node;

        $this->node->removeFromParent();

        $this->assertNull($this->node->parent);
        $this->assertNull($parent->left);
    }

    // Test replacing node with another node.
    public function testReplaceWith(): void
    {
        $parent = new TreeMapNode(20, 'twenty');
        $this->node->parent = $parent;
        $parent->left = $this->node;

        $replacement = new TreeMapNode(30, 'thirty');
        $this->node->replaceWith($replacement);

        $this->assertSame($replacement, $parent->left);
        $this->assertSame($parent, $replacement->parent);
    }

    // Test replace root node throws exception.
    public function testReplaceRootNodeThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot replace root node');

        $replacement = new TreeMapNode(30, 'thirty');
        $this->node->replaceWith($replacement);
    }
}
