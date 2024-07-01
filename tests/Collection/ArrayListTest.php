<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Collection;

use KaririCode\DataStructure\Collection\ArrayList;
use PHPUnit\Framework\TestCase;

final class ArrayListTest extends TestCase
{
    // Test adding a single element to the list
    public function testAddElementAddsElementToList(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $this->assertSame([1], $list->getItems());
    }

    // Test adding all elements from another collection
    public function testAddAllElementsAddsAllElementsFromAnotherCollection(): void
    {
        $list1 = new ArrayList();
        $list1->add(1);
        $list1->add(2);

        $list2 = new ArrayList();
        $list2->addAll($list1);

        $this->assertSame([1, 2], $list2->getItems());
    }

    // Test removing an element from the list
    public function testRemoveElementRemovesElementFromList(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);

        $this->assertTrue($list->remove(1));
        $this->assertFalse($list->remove(3));
        $this->assertSame([2], $list->getItems());
    }

    // Test checking if the list contains an element
    public function testContainsElementReturnsTrueIfElementExists(): void
    {
        $list = new ArrayList();
        $list->add(1);

        $this->assertTrue($list->contains(1));
        $this->assertFalse($list->contains(2));
    }

    // Test clearing all elements from the list
    public function testClearElementsRemovesAllElementsFromList(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->clear();

        $this->assertTrue($list->size() <= 0);
        $this->assertSame([], $list->getItems());
    }

    // Test checking if the list is empty
    public function testIsEmptyReturnsTrueIfListIsEmpty(): void
    {
        $list = new ArrayList();
        $this->assertTrue($list->size() <= 0);

        $list->add(1);
        $this->assertFalse($list->size() <= 0);
    }

    // Test getting all items from the list
    public function testGetItemsReturnsAllElementsInList(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);

        $this->assertSame([1, 2], $list->getItems());
    }

    // Test counting the elements in the list
    public function testCountElementsReturnsNumberOfElementsInList(): void
    {
        $list = new ArrayList();
        $this->assertSame(0, $list->size());

        $list->add(1);
        $list->add(2);
        $this->assertSame(2, $list->size());
    }

    // Test getting an element by its index
    public function testGetElementByIndexThrowsExceptionForInvalidIndex(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $list = new ArrayList();
        $list->add(1);
        $list->add(2);

        $this->assertSame(1, $list->get(0));
        $this->assertSame(2, $list->get(1));
        $list->get(2); // This should throw an exception
    }

    // Test setting an element by its index
    public function testSetElementByIndexThrowsExceptionForInvalidIndex(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $list = new ArrayList();
        $list->add(1);
        $list->add(2);

        $list->set(1, 3);
        $this->assertSame(3, $list->get(1));
        $list->set(2, 4); // This should throw an exception
    }

    // Test iterating over elements in the list
    public function testIterationOverElementsReturnsAllElements(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);
        $list->add(3);

        $items = [];
        foreach ($list->getItems() as $item) {
            $items[] = $item;
        }

        $this->assertSame([1, 2, 3], $items);
    }

    // Test adding different data types to the list
    public function testDifferentDataTypesCanBeAddedToList(): void
    {
        $list = new ArrayList();
        $list->add('string');
        $list->add(123);
        $list->add(45.6);
        $list->add(['key' => 'value']);
        $list->add(new \stdClass());

        $this->assertSame('string', $list->get(0));
        $this->assertSame(123, $list->get(1));
        $this->assertSame(45.6, $list->get(2));
        $this->assertSame(['key' => 'value'], $list->get(3));
        $this->assertInstanceOf(\stdClass::class, $list->get(4));
    }

    // Test adding and removing a large number of elements
    public function testMassAddAndRemoveHandlesLargeNumberOfElements(): void
    {
        $list = new ArrayList();

        for ($i = 0; $i < 1000; ++$i) {
            $list->add($i);
        }

        $this->assertSame(1000, $list->size());

        for ($i = 0; $i < 1000; ++$i) {
            $list->remove($i);
        }

        $this->assertTrue($list->size() <= 0);
    }

    // Test index consistency after removing an element
    public function testIndexConsistencyAfterRemoval(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);
        $list->add(3);

        $list->remove(2);

        $this->assertSame(1, $list->get(0));
        $this->assertSame(3, $list->get(1));

        $this->expectException(\OutOfRangeException::class);
        $list->get(2);
    }

    // Test accessing an element by an out-of-bounds index
    public function testOutOfBoundsAccessThrowsException(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $list = new ArrayList();
        $list->add(1);
        $list->get(1); // This should throw an exception
    }

    // Test setting an element by an out-of-bounds index
    public function testOutOfBoundsSetThrowsException(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $list = new ArrayList();
        $list->add(1);
        $list->set(1, 2); // This should throw an exception
    }

    // Test removing a non-existent element
    public function testOutOfBoundsRemoveReturnsFalse(): void
    {
        $list = new ArrayList();
        $list->add(1);

        $this->assertFalse($list->remove(2)); // Attempt to remove a non-existent element
    }

    // Test handling null values in the list
    public function testHandlingNullValuesCorrectly(): void
    {
        $list = new ArrayList();
        $list->add(null);

        $this->assertTrue($list->contains(null));
        $this->assertTrue($list->remove(null));
        $this->assertFalse($list->contains(null));
    }

    // Test serializing and deserializing the list
    public function testSerializationAndDeserialization(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);

        $serialized = serialize($list);
        $unserializedList = unserialize($serialized);

        $this->assertEquals($list->getItems(), $unserializedList->getItems());
    }

    // Test cloning the list
    public function testCloningCreatesIndependentCopy(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);

        $clonedList = clone $list;

        $this->assertEquals($list->getItems(), $clonedList->getItems());

        // Modify the cloned list and ensure the original list remains unchanged
        $clonedList->add(3);
        $this->assertNotEquals($list->getItems(), $clonedList->getItems());
        $this->assertEquals([1, 2], $list->getItems());
        $this->assertEquals([1, 2, 3], $clonedList->getItems());
    }

    // Test list integrity after a sequence of mixed operations
    public function testListIntegrityAfterMixedOperations(): void
    {
        $list = new ArrayList();
        $list->add(1);
        $list->add(2);
        $list->remove(1);
        $list->add(3);
        $list->clear();
        $list->add(4);
        $list->set(0, 5);

        $this->assertSame([5], $list->getItems());
    }
}
