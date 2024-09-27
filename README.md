# KaririCode Framework: Data Structure Component

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md)
[![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)
![Data Structures](https://img.shields.io/badge/Data_Structures-E74C3C?style=for-the-badge&logo=data-structures&logoColor=white)

The **KaririCode Data Structure** component provides a collection of advanced data structures implemented in PHP, designed with strong typing and object-oriented principles. It includes implementations for various common structures like dynamic arrays, linked lists, heaps, queues, maps, sets, and stacks.

## Features

- **ArrayList**: A dynamic array providing fast access and amortized O(1) complexity for adding elements.
- **LinkedList**: A doubly linked list with O(1) insertion and removal at both ends, and O(n) for arbitrary index access.
- **BinaryHeap**: A binary heap (min-heap or max-heap) with O(log n) for insertions, removals, and polling.
- **HashMap**: A hash-based map providing average O(1) complexity for put, get, and remove operations.
- **TreeMap**: A self-balancing red-black tree map with O(log n) time complexity for put, get, and remove operations.
- **TreeSet**: A set implementation backed by `TreeMap`, ensuring elements are stored in a sorted order.
- **ArrayDeque**: A double-ended queue using a circular array with amortized O(1) operations at both ends.
- **ArrayStack**: A stack implemented using a dynamic array, providing O(1) complexity for push, pop, and peek operations.

## Installation

To install the **KaririCode DataStructure** component, use the following command:

```bash
composer require kariricode/data-structure
```

## Basic Usage

### ArrayList Example

```php
use KaririCode\DataStructure\Collection\ArrayList;

$list = new ArrayList();
$list->add("Item 1");
$list->add("Item 2");
echo $list->get(0); // Outputs: Item 1
```

### LinkedList Example

```php
use KaririCode\DataStructure\Collection\LinkedList;

$linkedList = new LinkedList();
$linkedList->add("First");
$linkedList->add("Second");
$linkedList->remove("First");
```

### BinaryHeap Example

```php
use KaririCode\DataStructure\Heap\BinaryHeap;

$heap = new BinaryHeap();
$heap->add(10);
$heap->add(5);
$heap->add(20);
echo $heap->poll(); // Outputs: 5 (min-heap by default)
```

### HashMap Example

```php
use KaririCode\DataStructure\Map\HashMap;

$map = new HashMap();
$map->put("key1", "value1");
echo $map->get("key1"); // Outputs: value1
```

### TreeSet Example

```php
use KaririCode\DataStructure\Set\TreeSet;

$set = new TreeSet();
$set->add("value1");
$set->add("value2");
echo $set->contains("value1"); // Outputs: true
```

### ArrayStack Example

```php
use KaririCode\DataStructure\Stack\ArrayStack;

$stack = new ArrayStack();
$stack->push("First");
$stack->push("Second");
echo $stack->peek(); // Outputs: Second
$stack->pop();       // Removes "Second"
```

### ArrayDeque Example

```php
use KaririCode\DataStructure\Queue\ArrayDeque;

$deque = new ArrayDeque();
$deque->addFirst("First");
$deque->addLast("Last");
echo $deque->peekLast(); // Outputs: Last
$deque->removeLast();    // Removes "Last"
```

## Testing

To run tests for the **KaririCode DataStructure** component, execute the following command:

```bash
make test
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support and Community

- **Documentation**: [https://kariricode.org](https://kariricode.org)
- **Issue Tracker**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-datastructure/issues)
- **Community**: [KaririCode Club Community](https://kariricode.club)
- **Professional Support**: For enterprise-level support, contact us at support@kariricode.org

---

Built with ❤️ by the KaririCode team. Maintained by Walmir Silva - [walmir.silva@kariricode.org](mailto:walmir.silva@kariricode.org)
