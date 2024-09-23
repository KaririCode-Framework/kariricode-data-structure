<?php

declare(strict_types=1);

class BTreeNode
{
    public int $numKeys;
    public array $keys;
    public array $children;
    public bool $isLeaf;

    public function __construct(int $degree)
    {
        $this->numKeys = 0;
        $this->keys = array_fill(0, 2 * $degree - 1, 0);
        $this->children = array_fill(0, 2 * $degree, null);
        $this->isLeaf = true;
    }
}

class BTree
{
    private int $degree;
    private ?BTreeNode $root;

    public function __construct(int $degree)
    {
        $this->degree = $degree;
        $this->root = new BTreeNode($degree);
        $this->root->numKeys = 0;
        $this->root->isLeaf = true;
    }

    public function insert(int $key): void
    {
        if ($this->root->numKeys === 2 * $this->degree - 1) {
            $newRoot = new BTreeNode($this->degree);
            $newRoot->isLeaf = false;
            $newRoot->children[0] = $this->root;
            $this->splitChild($newRoot, 0, $this->root);
            $this->insertNonFull($newRoot, $key);
            $this->root = $newRoot;
        } else {
            $this->insertNonFull($this->root, $key);
        }
    }

    private function insertNonFull(BTreeNode $node, int $key): void
    {
        $index = $node->numKeys - 1;

        if ($node->isLeaf) {
            while ($index >= 0 && $key < $node->keys[$index]) {
                $node->keys[$index + 1] = $node->keys[$index];
                --$index;
            }
            $node->keys[$index + 1] = $key;
            ++$node->numKeys;
        } else {
            while ($index >= 0 && $key < $node->keys[$index]) {
                --$index;
            }
            ++$index;
            if ($node->children[$index]->numKeys === 2 * $this->degree - 1) {
                $this->splitChild($node, $index, $node->children[$index]);
                if ($key > $node->keys[$index]) {
                    ++$index;
                }
            }
            $this->insertNonFull($node->children[$index], $key);
        }
    }

    private function splitChild(BTreeNode $parentNode, int $index, BTreeNode $fullChildNode): void
    {
        $newNode = new BTreeNode($this->degree);
        $newNode->isLeaf = $fullChildNode->isLeaf;
        $newNode->numKeys = $this->degree - 1;

        for ($j = 0; $j < $this->degree - 1; ++$j) {
            $newNode->keys[$j] = $fullChildNode->keys[$j + $this->degree];
        }

        if (! $fullChildNode->isLeaf) {
            for ($j = 0; $j < $this->degree; ++$j) {
                $newNode->children[$j] = $fullChildNode->children[$j + $this->degree];
            }
        }

        $fullChildNode->numKeys = $this->degree - 1;

        for ($j = $parentNode->numKeys; $j >= $index + 1; --$j) {
            $parentNode->children[$j + 1] = $parentNode->children[$j];
        }

        $parentNode->children[$index + 1] = $newNode;

        for ($j = $parentNode->numKeys - 1; $j >= $index; --$j) {
            $parentNode->keys[$j + 1] = $parentNode->keys[$j];
        }

        $parentNode->keys[$index] = $fullChildNode->keys[$this->degree - 1];
        ++$parentNode->numKeys;
    }

    public function remove(int $key): void
    {
        if (! $this->root) {
            return;
        }

        $this->removeFromNode($this->root, $key);

        if (0 === $this->root->numKeys) {
            $this->root = $this->root->isLeaf ? null : $this->root->children[0];
        }
    }

    private function removeFromNode(BTreeNode $node, int $key): void
    {
        $index = $this->findKeyIndex($node, $key);

        if ($index < $node->numKeys && $node->keys[$index] === $key) {
            if ($node->isLeaf) {
                $this->removeFromLeaf($node, $index);
            } else {
                $this->removeFromNonLeaf($node, $index);
            }
        } else {
            if ($node->isLeaf) {
                return;
            }

            $isLastChild = ($index === $node->numKeys);

            if ($node->children[$index]->numKeys < $this->degree) {
                $this->fillNode($node, $index);
            }

            if ($isLastChild && $index > $node->numKeys) {
                $this->removeFromNode($node->children[$index - 1], $key);
            } else {
                $this->removeFromNode($node->children[$index], $key);
            }
        }
    }

    private function removeFromLeaf(BTreeNode $node, int $index): void
    {
        for ($i = $index + 1; $i < $node->numKeys; ++$i) {
            $node->keys[$i - 1] = $node->keys[$i];
        }
        --$node->numKeys;
    }

    private function removeFromNonLeaf(BTreeNode $node, int $index): void
    {
        $key = $node->keys[$index];

        if ($node->children[$index]->numKeys >= $this->degree) {
            $predKey = $this->getPredecessor($node, $index);
            $node->keys[$index] = $predKey;
            $this->removeFromNode($node->children[$index], $predKey);
        } elseif ($node->children[$index + 1]->numKeys >= $this->degree) {
            $succKey = $this->getSuccessor($node, $index);
            $node->keys[$index] = $succKey;
            $this->removeFromNode($node->children[$index + 1], $succKey);
        } else {
            $this->mergeNodes($node, $index);
            $this->removeFromNode($node->children[$index], $key);
        }
    }

    private function getPredecessor(BTreeNode $node, int $index): int
    {
        $currentNode = $node->children[$index];
        while (! $currentNode->isLeaf) {
            $currentNode = $currentNode->children[$currentNode->numKeys];
        }

        return $currentNode->keys[$currentNode->numKeys - 1];
    }

    private function getSuccessor(BTreeNode $node, int $index): int
    {
        $currentNode = $node->children[$index + 1];
        while (! $currentNode->isLeaf) {
            $currentNode = $currentNode->children[0];
        }

        return $currentNode->keys[0];
    }

    private function fillNode(BTreeNode $parentNode, int $index): void
    {
        if (0 !== $index && $parentNode->children[$index - 1]->numKeys >= $this->degree) {
            $this->borrowFromPrevious($parentNode, $index);
        } elseif ($index !== $parentNode->numKeys && $parentNode->children[$index + 1]->numKeys >= $this->degree) {
            $this->borrowFromNext($parentNode, $index);
        } else {
            if ($index !== $parentNode->numKeys) {
                $this->mergeNodes($parentNode, $index);
            } else {
                $this->mergeNodes($parentNode, $index - 1);
            }
        }
    }

    private function borrowFromPrevious(BTreeNode $parentNode, int $index): void
    {
        $childNode = $parentNode->children[$index];
        $siblingNode = $parentNode->children[$index - 1];

        for ($i = $childNode->numKeys - 1; $i >= 0; --$i) {
            $childNode->keys[$i + 1] = $childNode->keys[$i];
        }

        if (! $childNode->isLeaf) {
            for ($i = $childNode->numKeys; $i >= 0; --$i) {
                $childNode->children[$i + 1] = $childNode->children[$i];
            }
        }

        $childNode->keys[0] = $parentNode->keys[$index - 1];

        if (! $childNode->isLeaf) {
            $childNode->children[0] = $siblingNode->children[$siblingNode->numKeys];
        }

        $parentNode->keys[$index - 1] = $siblingNode->keys[$siblingNode->numKeys - 1];

        ++$childNode->numKeys;
        --$siblingNode->numKeys;
    }

    private function borrowFromNext(BTreeNode $parentNode, int $index): void
    {
        $childNode = $parentNode->children[$index];
        $siblingNode = $parentNode->children[$index + 1];

        $childNode->keys[$childNode->numKeys] = $parentNode->keys[$index];

        if (! $childNode->isLeaf) {
            $childNode->children[$childNode->numKeys + 1] = $siblingNode->children[0];
        }

        $parentNode->keys[$index] = $siblingNode->keys[0];

        for ($i = 1; $i < $siblingNode->numKeys; ++$i) {
            $siblingNode->keys[$i - 1] = $siblingNode->keys[$i];
        }

        if (! $siblingNode->isLeaf) {
            for ($i = 1; $i <= $siblingNode->numKeys; ++$i) {
                $siblingNode->children[$i - 1] = $siblingNode->children[$i];
            }
        }

        ++$childNode->numKeys;
        --$siblingNode->numKeys;
    }

    private function mergeNodes(BTreeNode $parentNode, int $index): void
    {
        $childNode = $parentNode->children[$index];
        $siblingNode = $parentNode->children[$index + 1];

        $childNode->keys[$this->degree - 1] = $parentNode->keys[$index];

        for ($i = 0; $i < $siblingNode->numKeys; ++$i) {
            $childNode->keys[$i + $this->degree] = $siblingNode->keys[$i];
        }

        if (! $childNode->isLeaf) {
            for ($i = 0; $i <= $siblingNode->numKeys; ++$i) {
                $childNode->children[$i + $this->degree] = $siblingNode->children[$i];
            }
        }

        for ($i = $index + 1; $i < $parentNode->numKeys; ++$i) {
            $parentNode->keys[$i - 1] = $parentNode->keys[$i];
        }

        for ($i = $index + 2; $i <= $parentNode->numKeys; ++$i) {
            $parentNode->children[$i - 1] = $parentNode->children[$i];
        }

        $childNode->numKeys += $siblingNode->numKeys + 1;
        --$parentNode->numKeys;
    }

    private function findKeyIndex(BTreeNode $node, int $key): int
    {
        $index = 0;
        while ($index < $node->numKeys && $node->keys[$index] < $key) {
            ++$index;
        }

        return $index;
    }

    public function search(int $key): ?BTreeNode
    {
        return $this->searchInNode($this->root, $key);
    }

    private function searchInNode(?BTreeNode $node, int $key): ?BTreeNode
    {
        if (null === $node) {
            return null;
        }

        $index = 0;
        while ($index < $node->numKeys && $key > $node->keys[$index]) {
            ++$index;
        }
        if ($index < $node->numKeys && $key === $node->keys[$index]) {
            return $node;
        }
        if ($node->isLeaf) {
            return null;
        }

        return $this->searchInNode($node->children[$index], $key);
    }

    public function printTree(): void
    {
        if ($this->root) {
            $this->printNode($this->root, 0);
        } else {
            echo "The tree is empty.\n";
        }
    }

    private function printNode(BTreeNode $node, int $level): void
    {
        echo str_repeat('  ', $level);
        echo "Level $level: ";
        for ($i = 0; $i < $node->numKeys; ++$i) {
            echo $node->keys[$i] . ' ';
        }
        echo "\n";

        if (! $node->isLeaf) {
            for ($i = 0; $i <= $node->numKeys; ++$i) {
                $this->printNode($node->children[$i], $level + 1);
            }
        }
    }
}

// Exemplo de uso
$degree = 3; // Árvore B com grau mínimo 3
$btree = new BTree($degree);
$keys = [10, 20, 5, 6, 12, 30, 7, 17];

echo 'Inserting keys: ' . implode(', ', $keys) . "\n";
foreach ($keys as $key) {
    $btree->insert($key);
}

echo "\nInitial B-Tree:\n";
$btree->printTree();

echo "\nRemoving key 6:\n";
$btree->remove(6);
$btree->printTree();

echo "\nRemoving key 30:\n";
$btree->remove(30);
$btree->printTree();

echo "\nSearching for key 12:\n";
$result = $btree->search(12);
echo $result ? "Found\n" : "Not found\n";

echo "\nSearching for key 15:\n";
$result = $btree->search(15);
echo $result ? "Found\n" : "Not found\n";
