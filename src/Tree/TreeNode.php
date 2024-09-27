<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree;

/**
 * TreeNode class.
 *
 * This class represents a generic node in a binary tree, which can be used for various tree structures.
 * Each node contains a key, a value, and references to its left and right child nodes, as well as an optional parent node.
 *
 * @category  Data Structures
 *
 * @author    Walmir Silva
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class TreeNode
{
    public function __construct(
        public mixed $key,
        public mixed $value,
        public ?TreeNode $left = null,
        public ?TreeNode $right = null,
        public ?TreeNode $parent = null
    ) {
    }

    /**
     * Sets the left child of this node and updates the parent reference of the child node.
     */
    public function setLeft(?TreeNode $node): void
    {
        $this->left = $node;
        if (null !== $node) {
            $node->parent = $this;
        }
    }

    /**
     * Sets the right child of this node and updates the parent reference of the child node.
     */
    public function setRight(?TreeNode $node): void
    {
        $this->right = $node;
        if (null !== $node) {
            $node->parent = $this;
        }
    }

    /**
     * Removes this node from its parent node, detaching it from the tree.
     */
    public function removeFromParent(): void
    {
        if (null !== $this->parent) {
            if ($this === $this->parent->left) {
                $this->parent->left = null;
            } else {
                $this->parent->right = null;
            }
            $this->parent = null;
        }
    }

    /**
     * Replaces this node with the specified replacement node.
     *
     * @param TreeNode $replacement the node that will replace the current node
     *
     * @throws \RuntimeException if trying to replace the root node without a parent
     */
    public function replaceWith(TreeNode $replacement): void
    {
        if (null === $this->parent) {
            throw new \RuntimeException('Cannot replace root node');
        }
        if ($this === $this->parent->left) {
            $this->parent->setLeft($replacement);
        } else {
            $this->parent->setRight($replacement);
        }
    }
}
