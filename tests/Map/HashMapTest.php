<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Tests\Map;

use KaririCode\DataStructure\Map\HashMap;
use PHPUnit\Framework\TestCase;

final class HashMapTest extends TestCase
{
    private HashMap $hashMap;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hashMap = new HashMap();
    }

    // Test adding a key-value pair to the map
    public function testPutAddsKeyValuePairToMap(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $this->assertSame('value1', $map->get('key1'));
    }

    // Test retrieving a value by key
    public function testGetReturnsValueForKey(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $this->assertSame('value1', $map->get('key1'));
        $this->assertNull($map->get('key2'));
    }

    // Test removing a key-value pair by key
    public function testRemoveDeletesKeyValuePairFromMap(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $this->assertTrue($map->remove('key1'));
        $this->assertFalse($map->remove('key2'));
        $this->assertNull($map->get('key1'));
    }

    // Test checking if the map contains a specific key
    public function testContainsKeyReturnsTrueIfKeyExists(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $this->assertTrue($map->containsKey('key1'));
        $this->assertFalse($map->containsKey('key2'));
    }

    // Test clearing all key-value pairs from the map
    public function testClearRemovesAllKeyValuePairsFromMap(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');
        $map->clear();
        $this->assertSame(0, $map->size());
        $this->assertNull($map->get('key1'));
        $this->assertNull($map->get('key2'));
    }

    // Test getting all keys from the map
    public function testKeysReturnsAllKeysInMap(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');
        $this->assertSame(['key1', 'key2'], $map->keys());
    }

    // Test getting all values from the map
    public function testValuesReturnsAllValuesInMap(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');
        $this->assertSame(['value1', 'value2'], $map->values());
    }

    // Test getting the size of the map
    public function testSizeReturnsNumberOfKeyValuePairsInMap(): void
    {
        $map = new HashMap();
        $this->assertSame(0, $map->size());
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');
        $this->assertSame(2, $map->size());
    }

    // Test handling null values in the map
    public function testHandlingNullValuesCorrectly(): void
    {
        $map = new HashMap();
        $map->put('key1', null);
        $this->assertTrue($map->containsKey('key1'));
        $this->assertNull($map->get('key1'));
        $this->assertTrue($map->remove('key1'));
        $this->assertFalse($map->containsKey('key1'));
    }

    // Test replacing a value for an existing key
    public function testPutReplacesValueForExistingKey(): void
    {
        $map = new HashMap();
        $map->put('key1', 'value1');
        $map->put('key1', 'value2');
        $this->assertSame('value2', $map->get('key1'));
    }

    // Test for the getIterator method
    public function testGetIterator(): void
    {
        $this->hashMap->put(1, 'one');
        $this->hashMap->put(2, 'two');
        $this->hashMap->put(3, 'three');

        $items = [];
        foreach ($this->hashMap as $key => $value) {
            $items[$key] = $value;
        }

        $expectedItems = [
            1 => 'one',
            2 => 'two',
            3 => 'three',
        ];

        $this->assertSame($expectedItems, $items);
    }
}
