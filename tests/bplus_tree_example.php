<?php

declare(strict_types=1);

class Node
{
    public int $n;
    public array $key;
    public array $child;
    public bool $leaf;

    public function __construct(int $t)
    {
        $this->n = 0;
        $this->key = array_fill(0, 2 * $t - 1, 0);
        $this->child = array_fill(0, 2 * $t, null);
        $this->leaf = true;
    }
}

class BTree
{
    private int $T;
    private ?Node $root;

    public function __construct(int $t)
    {
        $this->T = $t;
        $this->root = new Node($t);
        $this->root->n = 0;
        $this->root->leaf = true;
    }

    public function insert(int $k): void
    {
        if ($this->root->n == 2 * $this->T - 1) {
            $s = new Node($this->T);
            $s->leaf = false;
            $s->child[0] = $this->root;
            $this->splitChild($s, 0, $this->root);
            $this->insertNonFull($s, $k);
            $this->root = $s;
        } else {
            $this->insertNonFull($this->root, $k);
        }
    }

    private function insertNonFull(Node $x, int $k): void
    {
        $i = $x->n - 1;

        if ($x->leaf) {
            while ($i >= 0 && $k < $x->key[$i]) {
                $x->key[$i + 1] = $x->key[$i];
                --$i;
            }
            $x->key[$i + 1] = $k;
            $x->n = $x->n + 1;
        } else {
            while ($i >= 0 && $k < $x->key[$i]) {
                --$i;
            }
            ++$i;
            if ($x->child[$i]->n == 2 * $this->T - 1) {
                $this->splitChild($x, $i, $x->child[$i]);
                if ($k > $x->key[$i]) {
                    ++$i;
                }
            }
            $this->insertNonFull($x->child[$i], $k);
        }
    }

    private function splitChild(Node $x, int $i, Node $y): void
    {
        $z = new Node($this->T);
        $z->leaf = $y->leaf;
        $z->n = $this->T - 1;

        for ($j = 0; $j < $this->T - 1; ++$j) {
            $z->key[$j] = $y->key[$j + $this->T];
        }

        if (! $y->leaf) {
            for ($j = 0; $j < $this->T; ++$j) {
                $z->child[$j] = $y->child[$j + $this->T];
            }
        }

        $y->n = $this->T - 1;

        for ($j = $x->n; $j >= $i + 1; --$j) {
            $x->child[$j + 1] = $x->child[$j];
        }

        $x->child[$i + 1] = $z;

        for ($j = $x->n - 1; $j >= $i; --$j) {
            $x->key[$j + 1] = $x->key[$j];
        }

        $x->key[$i] = $y->key[$this->T - 1];
        $x->n = $x->n + 1;
    }

    public function remove(int $k): void
    {
        if (! $this->root) {
            return;
        }

        $this->removeFromNode($this->root, $k);

        if (0 == $this->root->n) {
            if ($this->root->leaf) {
                $this->root = null;
            } else {
                $this->root = $this->root->child[0];
            }
        }
    }

    private function removeFromNode(Node $x, int $k): void
    {
        $idx = $this->findKey($x, $k);

        if ($idx < $x->n && $x->key[$idx] == $k) {
            if ($x->leaf) {
                $this->removeFromLeaf($x, $idx);
            } else {
                $this->removeFromNonLeaf($x, $idx);
            }
        } else {
            if ($x->leaf) {
                return;
            }

            $flag = ($idx == $x->n);

            if ($x->child[$idx]->n < $this->T) {
                $this->fill($x, $idx);
            }

            if ($flag && $idx > $x->n) {
                $this->removeFromNode($x->child[$idx - 1], $k);
            } else {
                $this->removeFromNode($x->child[$idx], $k);
            }
        }
    }

    private function removeFromLeaf(Node $x, int $idx): void
    {
        for ($i = $idx + 1; $i < $x->n; ++$i) {
            $x->key[$i - 1] = $x->key[$i];
        }
        --$x->n;
    }

    private function removeFromNonLeaf(Node $x, int $idx): void
    {
        $k = $x->key[$idx];

        if ($x->child[$idx]->n >= $this->T) {
            $pred = $this->getPred($x, $idx);
            $x->key[$idx] = $pred;
            $this->removeFromNode($x->child[$idx], $pred);
        } elseif ($x->child[$idx + 1]->n >= $this->T) {
            $succ = $this->getSucc($x, $idx);
            $x->key[$idx] = $succ;
            $this->removeFromNode($x->child[$idx + 1], $succ);
        } else {
            $this->merge($x, $idx);
            $this->removeFromNode($x->child[$idx], $k);
        }
    }

    private function getPred(Node $x, int $idx): int
    {
        $cur = $x->child[$idx];
        while (! $cur->leaf) {
            $cur = $cur->child[$cur->n];
        }

        return $cur->key[$cur->n - 1];
    }

    private function getSucc(Node $x, int $idx): int
    {
        $cur = $x->child[$idx + 1];
        while (! $cur->leaf) {
            $cur = $cur->child[0];
        }

        return $cur->key[0];
    }

    private function fill(Node $x, int $idx): void
    {
        if (0 != $idx && $x->child[$idx - 1]->n >= $this->T) {
            $this->borrowFromPrev($x, $idx);
        } elseif ($idx != $x->n && $x->child[$idx + 1]->n >= $this->T) {
            $this->borrowFromNext($x, $idx);
        } else {
            if ($idx != $x->n) {
                $this->merge($x, $idx);
            } else {
                $this->merge($x, $idx - 1);
            }
        }
    }

    private function borrowFromPrev(Node $x, int $idx): void
    {
        $child = $x->child[$idx];
        $sibling = $x->child[$idx - 1];

        for ($i = $child->n - 1; $i >= 0; --$i) {
            $child->key[$i + 1] = $child->key[$i];
        }

        if (! $child->leaf) {
            for ($i = $child->n; $i >= 0; --$i) {
                $child->child[$i + 1] = $child->child[$i];
            }
        }

        $child->key[0] = $x->key[$idx - 1];

        if (! $child->leaf) {
            $child->child[0] = $sibling->child[$sibling->n];
        }

        $x->key[$idx - 1] = $sibling->key[$sibling->n - 1];

        ++$child->n;
        --$sibling->n;
    }

    private function borrowFromNext(Node $x, int $idx): void
    {
        $child = $x->child[$idx];
        $sibling = $x->child[$idx + 1];

        $child->key[$child->n] = $x->key[$idx];

        if (! $child->leaf) {
            $child->child[$child->n + 1] = $sibling->child[0];
        }

        $x->key[$idx] = $sibling->key[0];

        for ($i = 1; $i < $sibling->n; ++$i) {
            $sibling->key[$i - 1] = $sibling->key[$i];
        }

        if (! $sibling->leaf) {
            for ($i = 1; $i <= $sibling->n; ++$i) {
                $sibling->child[$i - 1] = $sibling->child[$i];
            }
        }

        ++$child->n;
        --$sibling->n;
    }

    private function merge(Node $x, int $idx): void
    {
        $child = $x->child[$idx];
        $sibling = $x->child[$idx + 1];

        $child->key[$this->T - 1] = $x->key[$idx];

        for ($i = 0; $i < $sibling->n; ++$i) {
            $child->key[$i + $this->T] = $sibling->key[$i];
        }

        if (! $child->leaf) {
            for ($i = 0; $i <= $sibling->n; ++$i) {
                $child->child[$i + $this->T] = $sibling->child[$i];
            }
        }

        for ($i = $idx + 1; $i < $x->n; ++$i) {
            $x->key[$i - 1] = $x->key[$i];
        }

        for ($i = $idx + 2; $i <= $x->n; ++$i) {
            $x->child[$i - 1] = $x->child[$i];
        }

        $child->n += $sibling->n + 1;
        --$x->n;
    }

    private function findKey(Node $x, int $k): int
    {
        $idx = 0;
        while ($idx < $x->n && $x->key[$idx] < $k) {
            ++$idx;
        }

        return $idx;
    }

    public function search(int $k): ?Node
    {
        return $this->searchKeyInNode($this->root, $k);
    }

    private function searchKeyInNode(?Node $x, int $k): ?Node
    {
        if (null === $x) {
            return null;
        }

        $i = 0;
        while ($i < $x->n && $k > $x->key[$i]) {
            ++$i;
        }
        if ($i < $x->n && $k == $x->key[$i]) {
            return $x;
        }
        if ($x->leaf) {
            return null;
        }

        return $this->searchKeyInNode($x->child[$i], $k);
    }

    public function printTree(): void
    {
        if ($this->root) {
            $this->printNode($this->root, 0);
        } else {
            echo "The tree is empty.\n";
        }
    }

    private function printNode(Node $x, int $level): void
    {
        echo str_repeat('  ', $level);
        echo "Level $level: ";
        for ($i = 0; $i < $x->n; ++$i) {
            echo $x->key[$i] . ' ';
        }
        echo "\n";

        if (! $x->leaf) {
            for ($i = 0; $i <= $x->n; ++$i) {
                $this->printNode($x->child[$i], $level + 1);
            }
        }
    }
}

// Exemplo de uso
$t = new BTree(3); // Árvore B com grau mínimo 3
$keys = [10, 20, 5, 6, 12, 30, 7, 17];

echo 'Inserting keys: ' . implode(', ', $keys) . "\n";
foreach ($keys as $key) {
    $t->insert($key);
}

echo "\nInitial B-Tree:\n";
$t->printTree();

echo "\nRemoving key 6:\n";
$t->remove(6);
$t->printTree();

echo "\nRemoving key 30:\n";
$t->remove(30);
$t->printTree();

echo "\nSearching for key 12:\n";
$result = $t->search(12);
echo $result ? "Found\n" : "Not found\n";

echo "\nSearching for key 15:\n";
$result = $t->search(15);
echo $result ? "Found\n" : "Not found\n";
