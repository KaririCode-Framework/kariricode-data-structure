<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Map;

use KaririCode\Contract\DataStructure\Behavioral\IterableCollection;
use KaririCode\Contract\DataStructure\Map;
use KaririCode\DataStructure\Tree\TreeMapNode;

/**
 * TreeMap implementation.
 *
 * This class implements a map using a self-balancing binary search tree (Red-Black Tree).
 * It provides O(log n) time complexity for put, get, and remove operations.
 *
 * @category  Maps
 *
 * @author    Walmir Silva <walmir.silva@kariricode.org>
 * @license   MIT
 *
 * @see       https://kariricode.org/
 */
class TreeMap implements Map, IterableCollection, \IteratorAggregate
{
    private ?TreeMapNode $root = null;
    private int $size = 0;

    public function put(mixed $key, mixed $value): void
    {
        $newNode = new TreeMapNode($key, $value);
        if (null === $this->root) {
            $this->root = $newNode;
            $this->root->setBlack();
            ++$this->size;
        } else {
            $this->insertNode($newNode);
            $this->balanceAfterInsertion($newNode);
        }
    }

    public function get(mixed $key): mixed
    {
        return $this->findNode($key)?->value;
    }

    public function keys(): array
    {
        $keys = [];
        $this->inOrderTraversalKeys($this->root, $keys);

        return $keys;
    }

    public function values(): array
    {
        $values = [];
        $this->inOrderTraversalValues($this->root, $values);

        return $values;
    }

    public function remove(mixed $key): bool
    {
        $node = $this->findNode($key);
        if (null === $node) {
            return false;
        }
        $this->deleteNode($node);
        --$this->size;

        return true;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function clear(): void
    {
        $this->root = null;
        $this->size = 0;
    }

    public function containsKey(mixed $key): bool
    {
        return null !== $this->findNode($key);
    }

    public function getItems(): array
    {
        $items = [];
        $this->inOrderTraversal($this->root, $items);

        return $items;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->getItems());
    }

    private function inOrderTraversalKeys(?TreeMapNode $node, array &$keys): void
    {
        if (null !== $node) {
            $this->inOrderTraversalKeys($node->left, $keys);
            $keys[] = $node->key;
            $this->inOrderTraversalKeys($node->right, $keys);
        }
    }

    private function inOrderTraversalValues(?TreeMapNode $node, array &$values): void
    {
        if (null !== $node) {
            $this->inOrderTraversalValues($node->left, $values);
            $values[] = $node->value;
            $this->inOrderTraversalValues($node->right, $values);
        }
    }

    private function inOrderTraversal(?TreeMapNode $node, array &$items): void
    {
        if (null !== $node) {
            $this->inOrderTraversal($node->left, $items);
            $items[$node->key] = $node->value;
            $this->inOrderTraversal($node->right, $items);
        }
    }

    private function insertNode(TreeMapNode $newNode): void
    {
        $current = $this->root;
        $parent = null;
        while (null !== $current) {
            $parent = $current;
            if ($newNode->key < $current->key) {
                $current = $current->left;
            } elseif ($newNode->key > $current->key) {
                $current = $current->right;
            } else {
                // Key already exists, update the value
                $current->value = $newNode->value;

                return;
            }
        }

        $newNode->parent = $parent;
        if ($newNode->key < $parent->key) {
            $parent->left = $newNode;
        } else {
            $parent->right = $newNode;
        }

        ++$this->size;
        $this->balanceAfterInsertion($newNode);
    }

    private function balanceAfterInsertion(TreeMapNode $node): void
    {
        while ($node !== $this->root && null !== $node->parent && $node->parent->isRed()) {
            if ($node->parent === $node->parent->parent->left) {
                $uncle = $node->parent->parent->right;
                if (null !== $uncle && $uncle->isRed()) {
                    $node->parent->setBlack();
                    $uncle->setBlack();
                    if (null !== $node->parent->parent) {
                        $node->parent->parent->setRed();
                        $node = $node->parent->parent;
                    }
                } else {
                    if ($node === $node->parent->right) {
                        $node = $node->parent;
                        $this->rotateLeft($node);
                    }
                    if (null !== $node->parent) {
                        $node->parent->setBlack();
                        if (null !== $node->parent->parent) {
                            $node->parent->parent->setRed();
                            $this->rotateRight($node->parent->parent);
                        }
                    }
                }
            } else {
                $uncle = $node->parent->parent->left;
                if (null !== $uncle && $uncle->isRed()) {
                    $node->parent->setBlack();
                    $uncle->setBlack();
                    if (null !== $node->parent->parent) {
                        $node->parent->parent->setRed();
                        $node = $node->parent->parent;
                    }
                } else {
                    if ($node === $node->parent->left) {
                        $node = $node->parent;
                        $this->rotateRight($node);
                    }
                    if (null !== $node->parent) {
                        $node->parent->setBlack();
                        if (null !== $node->parent->parent) {
                            $node->parent->parent->setRed();
                            $this->rotateLeft($node->parent->parent);
                        }
                    }
                }
            }
        }
        $this->root?->setBlack();
    }

    private function rotateLeft(TreeMapNode $node): void
    {
        $rightChild = $node->right;
        $node->setRight($rightChild->left);
        if (null !== $rightChild->left) {
            $rightChild->left->parent = $node;
        }
        $rightChild->parent = $node->parent;
        if (null === $node->parent) {
            $this->root = $rightChild;
        } elseif ($node === $node->parent->left) {
            $node->parent->left = $rightChild;
        } else {
            $node->parent->right = $rightChild;
        }
        $rightChild->left = $node;
        $node->parent = $rightChild;
    }

    private function rotateRight(TreeMapNode $node): void
    {
        $leftChild = $node->left;
        $node->setLeft($leftChild->right);
        if (null !== $leftChild->right) {
            $leftChild->right->parent = $node;
        }
        $leftChild->parent = $node->parent;
        if (null === $node->parent) {
            $this->root = $leftChild;
        } elseif ($node === $node->parent->right) {
            $node->parent->right = $leftChild;
        } else {
            $node->parent->left = $leftChild;
        }
        $leftChild->right = $node;
        $node->parent = $leftChild;
    }

    private function findNode(mixed $key): ?TreeMapNode
    {
        $current = $this->root;
        while (null !== $current) {
            if ($key === $current->key) {
                return $current;
            }
            $current = $key < $current->key ? $current->left : $current->right;
        }

        return null;
    }

    private function deleteNode(TreeMapNode $node): void
    {
        $replacementNode = $this->getReplacementNode($node);
        $needsBalancing = $node->isBlack() && (null === $replacementNode || $replacementNode->isBlack());

        if (null === $replacementNode) {
            if ($node === $this->root) {
                $this->root = null;
            } else {
                if ($needsBalancing) {
                    $this->balanceBeforeRemoval($node);
                }
                $node->removeFromParent();
            }

            return;
        }

        if (null === $node->left || null === $node->right) {
            if ($node === $this->root) {
                $this->root = $replacementNode;
                $replacementNode->setBlack();
                $replacementNode->parent = null;
            } else {
                $node->replaceWith($replacementNode);
                if ($needsBalancing) {
                    $this->balanceBeforeRemoval($replacementNode);
                }
            }

            return;
        }

        $successor = $this->minimum($node->right);
        $originalColor = $successor->isBlack();
        $node->key = $successor->key;
        $node->value = $successor->value;
        $replacementNode = $successor->right;

        if ($successor->parent === $node) {
            if (null !== $replacementNode) {
                $replacementNode->parent = $successor;
            }
        } else {
            $this->transplant($successor, $successor->right);
            $successor->right = $node->right;
            if (null !== $successor->right) {
                $successor->right->parent = $successor;
            }
        }

        $this->transplant($node, $successor);
        $successor->left = $node->left;
        if (null !== $successor->left) {
            $successor->left->parent = $successor;
        }
        $successor->color = $node->color;

        if (TreeMapNode::BLACK === $originalColor && null !== $replacementNode) {
            $this->balanceBeforeRemoval($replacementNode);
        }
    }

    private function transplant(TreeMapNode $u, ?TreeMapNode $v): void
    {
        if (null === $u->parent) {
            $this->root = $v;
        } elseif ($u === $u->parent->left) {
            $u->parent->left = $v;
        } else {
            $u->parent->right = $v;
        }
        if (null !== $v) {
            $v->parent = $u->parent;
        }
    }

    private function getReplacementNode(TreeMapNode $node): ?TreeMapNode
    {
        if (null !== $node->left && null !== $node->right) {
            return $this->minimum($node->right);
        }

        return $node->left ?? $node->right;
    }

    private function balanceBeforeRemoval(TreeMapNode $node): void
    {
        while ($node !== $this->root && $node->isBlack()) {
            if (null === $node->parent) {
                break;
            }
            if ($node === $node->parent->left) {
                $sibling = $node->parent->right;
                if (null !== $sibling && $sibling->isRed()) {
                    $sibling->setBlack();
                    $node->parent->setRed();
                    $this->rotateLeft($node->parent);
                    $sibling = $node->parent->right;
                }
                if (null === $sibling
                    || (null === $sibling->left || $sibling->left->isBlack())
                    && (null === $sibling->right || $sibling->right->isBlack())) {
                    if (null !== $sibling) {
                        $sibling->setRed();
                    }
                    $node = $node->parent;
                } else {
                    if (null === $sibling->right || $sibling->right->isBlack()) {
                        if (null !== $sibling->left) {
                            $sibling->left->setBlack();
                        }
                        $sibling->setRed();
                        $this->rotateRight($sibling);
                        $sibling = $node->parent->right;
                    }
                    if (null !== $sibling) {
                        $sibling->color = $node->parent->color;
                        $node->parent->setBlack();
                        if (null !== $sibling->right) {
                            $sibling->right->setBlack();
                        }
                        $this->rotateLeft($node->parent);
                    }
                    $node = $this->root;
                }
            } else {
                $sibling = $node->parent->left;
                if (null !== $sibling && $sibling->isRed()) {
                    $sibling->setBlack();
                    $node->parent->setRed();
                    $this->rotateRight($node->parent);
                    $sibling = $node->parent->left;
                }
                if (null === $sibling
                    || (null === $sibling->right || $sibling->right->isBlack())
                    && (null === $sibling->left || $sibling->left->isBlack())) {
                    if (null !== $sibling) {
                        $sibling->setRed();
                    }
                    $node = $node->parent;
                } else {
                    if (null === $sibling->left || $sibling->left->isBlack()) {
                        if (null !== $sibling->right) {
                            $sibling->right->setBlack();
                        }
                        $sibling->setRed();
                        $this->rotateLeft($sibling);
                        $sibling = $node->parent->left;
                    }
                    if (null !== $sibling) {
                        $sibling->color = $node->parent->color;
                        $node->parent->setBlack();
                        if (null !== $sibling->left) {
                            $sibling->left->setBlack();
                        }
                        $this->rotateRight($node->parent);
                    }
                    $node = $this->root;
                }
            }
        }
        $node->setBlack();
    }

    private function minimum(TreeMapNode $node): TreeMapNode
    {
        while (null !== $node->left) {
            $node = $node->left;
        }

        return $node;
    }
}
