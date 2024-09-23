<?php

declare(strict_types=1);

use KaririCode\DataStructure\Tree\BPlusTree;
use PHPUnit\Framework\TestCase;

class BPlusTreeTest extends TestCase
{
    public const ORDER = 5;

    private BPlusTree $tree;

    protected function setUp(): void
    {
        $this->tree = new BPlusTree(self::ORDER);
    }

    public function testInsertAndFind(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $this->assertEquals('A', $this->tree->find(1));
        $this->assertEquals('B', $this->tree->find(2));
        $this->assertEquals('C', $this->tree->find(3));
    }

    public function testInsertAndRemove(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $this->assertTrue($this->tree->remove(2));
        $this->assertNull($this->tree->find(2));

        $this->assertTrue($this->tree->remove(1));
        $this->assertNull($this->tree->find(1));

        $this->assertFalse($this->tree->remove(5)); // Testa remoção de chave inexistente
    }

    public function testRangeSearch(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');
        $this->tree->insert(4, 'D');

        $result = $this->tree->rangeSearch(2, 3);
        $this->assertEquals(['B', 'C'], $result);
    }

    public function testClearTree(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');

        $this->tree->clear();
        $this->assertNull($this->tree->getRoot());
        $this->assertEquals(0, $this->tree->size());
    }

    public function testGetItems(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $items = $this->tree->getItems();
        $this->assertEquals(['A', 'B', 'C'], $items);
    }

    public function testGetOrder(): void
    {
        $this->assertEquals(self::ORDER, $this->tree->getOrder());
    }

    public function testGetMinimumAndMaximum(): void
    {
        $this->tree->insert(5, 'E');
        $this->tree->insert(1, 'A');
        $this->tree->insert(3, 'C');

        $this->assertEquals('A', $this->tree->getMinimum());
        $this->assertEquals('E', $this->tree->getMaximum());
    }

    // public function testTreeBalance(): void
    // {
    //     $this->tree->insert(1, "A");
    //     $this->tree->insert(2, "B");
    //     $this->tree->insert(3, "C");
    //     $this->tree->insert(4, "D");
    //     $this->tree->insert(5, "E");
    //     $this->tree->insert(6, "F");
    //     $this->tree->insert(7, "G");
    //     $this->tree->insert(8, "H");

    //     $this->assertTrue($this->tree->isBalanced(), "B+ Tree is not balanced after insertions");
    // }

    public function testSetAndGetByIndex(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');

        $this->tree->set(1, 'Z');
        $this->assertEquals('Z', $this->tree->get(1));
    }

    public function testVisualTreeStructure(): void
    {
        $this->tree->insert(1, 'A');
        $this->tree->insert(2, 'B');
        $this->tree->insert(3, 'C');
        $this->tree->insert(4, 'D');
        $this->tree->insert(5, 'E');

        $visual = $this->tree->visualTreeStructure();
        $this->assertStringContainsString('Leaf Node', $visual);
        $this->assertStringContainsString('Internal Node', $visual);
    }

    public function testExceptions(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->tree->get(99); // Tenta acessar um índice fora do intervalo

        $this->expectException(InvalidArgumentException::class);
        new BPlusTree(2); // Ordem menor que 3 deve lançar exceção
    }
}
