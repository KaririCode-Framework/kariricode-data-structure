<?php

declare(strict_types=1);

class BTreeNode
{
    public int $keyCount;
    public array $keys;
    public array $children;
    public bool $isLeaf;

    public function __construct(int $degree)
    {
        $this->keyCount = 0;
        $this->keys = array_fill(0, 2 * $degree - 1, null);
        $this->children = array_fill(0, 2 * $degree, null);
        $this->isLeaf = true;
    }
}

class BTree
{
    private int $minDegree;
    private ?BTreeNode $root;

    public function __construct(int $degree)
    {
        $this->minDegree = $degree;
        $this->root = new BTreeNode($degree);
    }

    public function insert(int|float|string $key): void
    {
        if ($this->root->keyCount === 2 * $this->minDegree - 1) {
            $newRoot = new BTreeNode($this->minDegree);
            $newRoot->isLeaf = false;
            $newRoot->children[0] = $this->root;
            $this->splitChild($newRoot, 0, $this->root);
            $this->insertNonFull($newRoot, $key);
            $this->root = $newRoot;
        } else {
            $this->insertNonFull($this->root, $key);
        }
    }

    private function insertNonFull(BTreeNode $node, int|float|string $key): void
    {
        $i = $node->keyCount - 1;

        if ($node->isLeaf) {
            while ($i >= 0 && $key < $node->keys[$i]) {
                $node->keys[$i + 1] = $node->keys[$i];
                --$i;
            }
            $node->keys[$i + 1] = $key;
            ++$node->keyCount;
        } else {
            while ($i >= 0 && $key < $node->keys[$i]) {
                --$i;
            }
            ++$i;
            if ($node->children[$i]->keyCount === 2 * $this->minDegree - 1) {
                $this->splitChild($node, $i, $node->children[$i]);
                if ($key > $node->keys[$i]) {
                    ++$i;
                }
            }
            $this->insertNonFull($node->children[$i], $key);
        }
    }

    private function splitChild(BTreeNode $parent, int $index, BTreeNode $fullNode): void
    {
        $newNode = new BTreeNode($this->minDegree);
        $newNode->isLeaf = $fullNode->isLeaf;
        $newNode->keyCount = $this->minDegree - 1;

        for ($j = 0; $j < $this->minDegree - 1; ++$j) {
            $newNode->keys[$j] = $fullNode->keys[$j + $this->minDegree];
        }

        if (! $fullNode->isLeaf) {
            for ($j = 0; $j < $this->minDegree; ++$j) {
                $newNode->children[$j] = $fullNode->children[$j + $this->minDegree];
            }
        }

        $fullNode->keyCount = $this->minDegree - 1;

        for ($j = $parent->keyCount; $j >= $index + 1; --$j) {
            $parent->children[$j + 1] = $parent->children[$j];
        }

        $parent->children[$index + 1] = $newNode;

        for ($j = $parent->keyCount - 1; $j >= $index; --$j) {
            $parent->keys[$j + 1] = $parent->keys[$j];
        }

        $parent->keys[$index] = $fullNode->keys[$this->minDegree - 1];
        ++$parent->keyCount;
    }

    public function remove(int|float|string $key): void
    {
        if (! $this->root) {
            return;
        }

        $this->removeFromNode($this->root, $key);

        if (0 === $this->root->keyCount) {
            $this->root = $this->root->isLeaf ? null : $this->root->children[0];
        }
    }

    private function removeFromNode(BTreeNode $node, int|float|string $key): void
    {
        $idx = $this->findKey($node, $key);

        if ($idx < $node->keyCount && $node->keys[$idx] === $key) {
            if ($node->isLeaf) {
                $this->removeFromLeaf($node, $idx);
            } else {
                $this->removeFromNonLeaf($node, $idx);
            }
        } else {
            if ($node->isLeaf) {
                return;
            }

            $flag = ($idx === $node->keyCount);

            if ($node->children[$idx]->keyCount < $this->minDegree) {
                $this->fill($node, $idx);
            }

            if ($flag && $idx > $node->keyCount) {
                $this->removeFromNode($node->children[$idx - 1], $key);
            } else {
                $this->removeFromNode($node->children[$idx], $key);
            }
        }
    }

    private function removeFromLeaf(BTreeNode $node, int $index): void
    {
        for ($i = $index + 1; $i < $node->keyCount; ++$i) {
            $node->keys[$i - 1] = $node->keys[$i];
        }
        --$node->keyCount;
    }

    private function removeFromNonLeaf(BTreeNode $node, int $index): void
    {
        $key = $node->keys[$index];

        if ($node->children[$index]->keyCount >= $this->minDegree) {
            $pred = $this->getPredecessor($node, $index);
            $node->keys[$index] = $pred;
            $this->removeFromNode($node->children[$index], $pred);
        } elseif ($node->children[$index + 1]->keyCount >= $this->minDegree) {
            $succ = $this->getSuccessor($node, $index);
            $node->keys[$index] = $succ;
            $this->removeFromNode($node->children[$index + 1], $succ);
        } else {
            $this->merge($node, $index);
            $this->removeFromNode($node->children[$index], $key);
        }
    }

    private function getPredecessor(BTreeNode $node, int $index): int|float|string
    {
        $current = $node->children[$index];
        while (! $current->isLeaf) {
            $current = $current->children[$current->keyCount];
        }

        return $current->keys[$current->keyCount - 1];
    }

    private function getSuccessor(BTreeNode $node, int $index): int|float|string
    {
        $current = $node->children[$index + 1];
        while (! $current->isLeaf) {
            $current = $current->children[0];
        }

        return $current->keys[0];
    }

    private function fill(BTreeNode $node, int $index): void
    {
        if (0 !== $index && $node->children[$index - 1]->keyCount >= $this->minDegree) {
            $this->borrowFromPrevious($node, $index);
        } elseif ($index !== $node->keyCount && $node->children[$index + 1]->keyCount >= $this->minDegree) {
            $this->borrowFromNext($node, $index);
        } else {
            if ($index !== $node->keyCount) {
                $this->merge($node, $index);
            } else {
                $this->merge($node, $index - 1);
            }
        }
    }

    private function borrowFromPrevious(BTreeNode $node, int $index): void
    {
        $child = $node->children[$index];
        $sibling = $node->children[$index - 1];

        for ($i = $child->keyCount - 1; $i >= 0; --$i) {
            $child->keys[$i + 1] = $child->keys[$i];
        }

        if (! $child->isLeaf) {
            for ($i = $child->keyCount; $i >= 0; --$i) {
                $child->children[$i + 1] = $child->children[$i];
            }
        }

        $child->keys[0] = $node->keys[$index - 1];

        if (! $child->isLeaf) {
            $child->children[0] = $sibling->children[$sibling->keyCount];
        }

        $node->keys[$index - 1] = $sibling->keys[$sibling->keyCount - 1];

        ++$child->keyCount;
        --$sibling->keyCount;
    }

    private function borrowFromNext(BTreeNode $node, int $index): void
    {
        $child = $node->children[$index];
        $sibling = $node->children[$index + 1];

        $child->keys[$child->keyCount] = $node->keys[$index];

        if (! $child->isLeaf) {
            $child->children[$child->keyCount + 1] = $sibling->children[0];
        }

        $node->keys[$index] = $sibling->keys[0];

        for ($i = 1; $i < $sibling->keyCount; ++$i) {
            $sibling->keys[$i - 1] = $sibling->keys[$i];
        }

        if (! $sibling->isLeaf) {
            for ($i = 1; $i <= $sibling->keyCount; ++$i) {
                $sibling->children[$i - 1] = $sibling->children[$i];
            }
        }

        ++$child->keyCount;
        --$sibling->keyCount;
    }

    private function merge(BTreeNode $node, int $index): void
    {
        $child = $node->children[$index];
        $sibling = $node->children[$index + 1];

        $child->keys[$this->minDegree - 1] = $node->keys[$index];

        for ($i = 0; $i < $sibling->keyCount; ++$i) {
            $child->keys[$i + $this->minDegree] = $sibling->keys[$i];
        }

        if (! $child->isLeaf) {
            for ($i = 0; $i <= $sibling->keyCount; ++$i) {
                $child->children[$i + $this->minDegree] = $sibling->children[$i];
            }
        }

        for ($i = $index + 1; $i < $node->keyCount; ++$i) {
            $node->keys[$i - 1] = $node->keys[$i];
        }

        for ($i = $index + 2; $i <= $node->keyCount; ++$i) {
            $node->children[$i - 1] = $node->children[$i];
        }

        $child->keyCount += $sibling->keyCount + 1;
        --$node->keyCount;
    }

    private function findKey(BTreeNode $node, int|float|string $key): int
    {
        $index = 0;
        while ($index < $node->keyCount && $node->keys[$index] < $key) {
            ++$index;
        }

        return $index;
    }

    public function search(int|float|string $key): ?BTreeNode
    {
        return $this->searchKeyInNode($this->root, $key);
    }

    private function searchKeyInNode(?BTreeNode $node, int|float|string $key): ?BTreeNode
    {
        if (null === $node) {
            return null;
        }

        $i = 0;
        while ($i < $node->keyCount && $key > $node->keys[$i]) {
            ++$i;
        }
        if ($i < $node->keyCount && $key == $node->keys[$i]) {
            return $node;
        }
        if ($node->isLeaf) {
            return null;
        }

        return $this->searchKeyInNode($node->children[$i], $key);
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
        for ($i = 0; $i < $node->keyCount; ++$i) {
            echo $node->keys[$i] . ' ';
        }
        echo "\n";

        if (! $node->isLeaf) {
            for ($i = 0; $i <= $node->keyCount; ++$i) {
                $this->printNode($node->children[$i], $level + 1);
            }
        }
    }
}

// Exemplo de uso
$tree = new BTree(3); // Árvore B com grau mínimo 3
$keys = [10, 20, 5, 6, 12, 30, 7, 17];

echo 'Inserting keys: ' . implode(', ', $keys) . "\n";
foreach ($keys as $key) {
    $tree->insert($key);
}

echo "\nInitial B-Tree:\n";
$tree->printTree();

echo "\nRemoving key 6:\n";
$tree->remove(6);
$tree->printTree();

echo "\nRemoving key 30:\n";
$tree->remove(30);
$tree->printTree();

echo "\nSearching for key 12:\n";
$result = $tree->search(12);
echo $result ? "Found\n" : "Not found\n";

echo "\nSearching for key 15:\n";
$result = $tree->search(15);
echo $result ? "Found\n" : "Not found\n";

$tree = new BTree(3); // Árvore B com grau mínimo 3
$keys = ['D', 'B', 'A', 'C', 'F', 'E', 'H', 'G'];

echo 'Inserting keys: ' . implode(', ', $keys) . "\n";
foreach ($keys as $key) {
    $tree->insert($key);
}

echo "\nInitial B-Tree:\n";
$tree->printTree();

echo "\nRemoving key 'C':\n";
$tree->remove('C');
$tree->printTree();

echo "\nRemoving key 'F':\n";
$tree->remove('F');
$tree->printTree();

echo "\nSearching for key 'B':\n";
$result = $tree->search('B');
echo $result ? "Found\n" : "Not found\n";

echo "\nSearching for key 'Z':\n";
$result = $tree->search('Z');
echo $result ? "Found\n" : "Not found\n";