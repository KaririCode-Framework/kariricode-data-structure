<?php

declare(strict_types=1);

use KaririCode\DataStructure\Tree\TreeNode;
use PHPUnit\Framework\TestCase;

final class TreeNodeTest extends TestCase
{
    public function testCreateNode(): void
    {
        $node = new TreeNode(1, 'valor1');

        $this->assertSame(1, $node->key);
        $this->assertSame('valor1', $node->value);
        $this->assertNull($node->left);
        $this->assertNull($node->right);
        $this->assertNull($node->parent);
    }

    public function testSetLeftChild(): void
    {
        $parent = new TreeNode(1, 'parent');
        $child = new TreeNode(2, 'child');

        $parent->setLeft($child);

        $this->assertSame($child, $parent->left);
        $this->assertSame($parent, $child->parent);
    }

    public function testSetRightChild(): void
    {
        $parent = new TreeNode(1, 'parent');
        $child = new TreeNode(2, 'child');

        $parent->setRight($child);

        $this->assertSame($child, $parent->right);
        $this->assertSame($parent, $child->parent);
    }

    public function testRemoveFromParent(): void
    {
        $parent = new TreeNode(1, 'parent');
        $child = new TreeNode(2, 'child');

        $parent->setLeft($child);
        $child->removeFromParent();

        $this->assertNull($parent->left);
        $this->assertNull($child->parent);
    }

    public function testReplaceWith(): void
    {
        $parent = new TreeNode(1, 'parent');
        $child = new TreeNode(2, 'child');
        $replacement = new TreeNode(3, 'replacement');

        $parent->setLeft($child);
        $child->replaceWith($replacement);

        $this->assertSame($replacement, $parent->left);
        $this->assertSame($parent, $replacement->parent);
    }

    public function testSetRightAndLeftTogether(): void
    {
        $parent = new TreeNode(1, 'parent');
        $leftChild = new TreeNode(2, 'left');
        $rightChild = new TreeNode(3, 'right');

        $parent->setLeft($leftChild);
        $parent->setRight($rightChild);

        $this->assertSame($leftChild, $parent->left);
        $this->assertSame($rightChild, $parent->right);
        $this->assertSame($parent, $leftChild->parent);
        $this->assertSame($parent, $rightChild->parent);
    }

    public function testReplaceWithThrowsExceptionWhenNodeIsRoot(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot replace root node');

        $root = new TreeNode(1, 'root');
        $replacement = new TreeNode(2, 'replacement');

        $root->replaceWith($replacement);
    }

    public function testRemoveFromParentRightChild(): void
    {
        $parent = new TreeNode(1, 'parent');
        $child = new TreeNode(2, 'child');

        $parent->setRight($child);
        $child->removeFromParent();

        $this->assertNull($parent->right);
        $this->assertNull($child->parent);
    }

    public function testReplaceWithRightChild(): void
    {
        $parent = new TreeNode(1, 'parent');
        $child = new TreeNode(2, 'child');
        $replacement = new TreeNode(3, 'replacement');

        $parent->setRight($child);
        $child->replaceWith($replacement);

        $this->assertSame($replacement, $parent->right);
        $this->assertSame($parent, $replacement->parent);
    }
}
