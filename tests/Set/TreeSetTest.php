<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Set;

use KaririCode\DataStructure\Set\TreeSet;
use PHPUnit\Framework\TestCase;

final class TreeSetTest extends TestCase
{
    private TreeSet $set;

    protected function setUp(): void
    {
        parent::setUp();
        $this->set = new TreeSet();
    }

    // Test adding elements to the set
    public function testAddElement(): void
    {
        $this->set->add(1);
        $this->assertTrue($this->set->contains(1));
        $this->set->add(1); // Duplicate element
        $this->assertEquals(1, $this->set->size()); // Size should still be 1
    }

    // Test removing elements from the set
    public function testRemoveElement(): void
    {
        $this->set->add(1);
        $this->assertTrue($this->set->remove(1));
        $this->assertFalse($this->set->contains(1));
        $this->assertFalse($this->set->remove(1)); // Removing non-existing element
    }

    // Test checking if an element exists in the set
    public function testContainsElement(): void
    {
        $this->set->add(1);
        $this->assertTrue($this->set->contains(1));
        $this->assertFalse($this->set->contains(2)); // Non-existing element
    }

    // Test clearing the set
    public function testClearSet(): void
    {
        $this->set->add(1);
        $this->set->add(2);
        $this->set->clear();
        $this->assertTrue($this->set->size() <= 0);
    }

    // Test getting the size of the set
    public function testSizeOfSet(): void
    {
        $this->assertSame(0, $this->set->size());
        $this->set->add(1);
        $this->assertSame(1, $this->set->size());
    }

    // Test converting the set to an array
    public function testToArray(): void
    {
        $this->set->add(1);
        $this->set->add(2);
        $this->assertSame([1, 2], $this->set->getItems());
    }

    // Test adding various data types to the set
    public function testSetWithVariousDataTypes(): void
    {
        $obj = new \stdClass();

        $this->set->add(123);
        $this->set->add('string');
        $this->set->add([1, 2, 3]);
        $this->set->add($obj);

        $this->assertTrue($this->set->contains(123));
        $this->assertTrue($this->set->contains('string'));
        $this->assertTrue($this->set->contains([1, 2, 3]));
        $this->assertTrue($this->set->contains($obj));
    }

    // Test set behavior after mixed operations
    public function testSetBehaviorAfterMixedOperations(): void
    {
        $this->set->add(1);
        $this->set->add(2);
        $this->set->remove(1);
        $this->set->add(3);
        $this->set->clear();
        $this->set->add(4);

        $this->assertFalse($this->set->contains(1));
        $this->assertFalse($this->set->contains(2));
        $this->assertFalse($this->set->contains(3));
        $this->assertTrue($this->set->contains(4));
    }

    // Test adding and removing a large data set
    public function testLargeDataSet(): void
    {
        $data = range(1, 1000);
        foreach ($data as $element) {
            $this->set->add($element);
        }

        $this->assertSame(1000, $this->set->size());

        foreach ($data as $element) {
            $this->assertTrue($this->set->contains($element));
        }

        foreach ($data as $element) {
            $this->set->remove($element);
        }

        $this->assertTrue($this->set->size() <= 0);
    }

    // Test union of two sets
    public function testUnion(): void
    {
        $set1 = new TreeSet();
        $set2 = new TreeSet();

        $set1->add(1);
        $set1->add(2);

        $set2->add(2);
        $set2->add(3);

        $resultSet = $set1->union($set2);

        $this->assertTrue($resultSet->contains(1));
        $this->assertTrue($resultSet->contains(2));
        $this->assertTrue($resultSet->contains(3));
    }

    // Test intersection of two sets
    public function testIntersection(): void
    {
        $set1 = new TreeSet();
        $set2 = new TreeSet();

        $set1->add(1);
        $set1->add(2);
        $set1->add(3);

        $set2->add(2);
        $set2->add(3);
        $set2->add(4);

        $resultSet = $set1->intersection($set2);

        $this->assertFalse($resultSet->contains(1));
        $this->assertTrue($resultSet->contains(2));
        $this->assertTrue($resultSet->contains(3));
        $this->assertFalse($resultSet->contains(4));
    }

    // Test difference of two sets
    public function testDifference(): void
    {
        $set1 = new TreeSet();
        $set2 = new TreeSet();

        $set1->add(1);
        $set1->add(2);
        $set1->add(3);

        $set2->add(2);
        $set2->add(3);
        $set2->add(4);

        $resultSet = $set1->difference($set2);

        $this->assertTrue($resultSet->contains(1));
        $this->assertFalse($resultSet->contains(2));
        $this->assertFalse($resultSet->contains(3));
        $this->assertFalse($resultSet->contains(4));
    }

    // Test find method
    public function testFind(): void
    {
        $this->set->add(1);
        $this->set->add(2);
        $this->assertSame(1, $this->set->find(1));
        $this->assertNull($this->set->find(3)); // Non-existing element
    }
}
