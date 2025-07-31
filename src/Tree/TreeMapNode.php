<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tree;

/**
 * TreeMapNode class.
 *
 * This class represents a node in a Red-Black Tree used by TreeMap.
 * Each node contains a key, a value, a color (red or black), and references
 * to its left, right, and parent nodes.
 *
 * @category  Data Structures
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class TreeMapNode
{
    public const RED = true;
    public const BLACK = false;

    public function __construct(
        public mixed $key,
        public mixed $value,
        public bool $color = self::RED,
        public ?TreeMapNode $left = null,
        public ?TreeMapNode $right = null,
        public ?TreeMapNode $parent = null,
    ) {
    }

    public function isRed(): bool
    {
        return self::RED === $this->color;
    }

    public function isBlack(): bool
    {
        return self::BLACK === $this->color;
    }

    public function setRed(): void
    {
        $this->color = self::RED;
    }

    public function setBlack(): void
    {
        $this->color = self::BLACK;
    }

    public function setLeft(?TreeMapNode $node): void
    {
        $this->left = $node;
        if (null !== $node) {
            $node->parent = $this;
        }
    }

    public function setRight(?TreeMapNode $node): void
    {
        $this->right = $node;
        if (null !== $node) {
            $node->parent = $this;
        }
    }

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

    public function replaceWith(TreeMapNode $replacement): void
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
