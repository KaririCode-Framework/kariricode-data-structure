# KaririCode Framework: Data Structures Component

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md)
[![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Makefile](https://img.shields.io/badge/Makefile-1D1D1D?style=for-the-badge&logo=gnu&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PHPUnit](https://img.shields.io/badge/PHPUnit-78E130?style=for-the-badge&logo=phpunit&logoColor=white)

## Overview

The Data Structures component is a cornerstone of the KaririCode Framework, offering robust, high-performance, and type-safe implementations of essential data structures for PHP applications. This component is meticulously designed to meet the demands of modern, scalable software development, providing developers with a powerful toolkit to optimize their applications' data management capabilities.

## Key Features

- **Optimized Performance**: Carefully crafted implementations ensure optimal time and space complexity for all operations.
- **Type Safety**: Leverages PHP 8.0+ features for enhanced type checking and improved code reliability.
- **Memory Efficiency**: Implements custom memory management strategies to minimize overhead.
- **Iterative and Recursive API**: Offers both iterative and recursive methods for key operations, allowing developers to choose based on their specific use case.
- **Serialization Support**: All data structures implement PHP's Serializable interface for easy storage and transmission.
- **Extensive Testing**: Comprehensive unit and integration tests ensure reliability and correctness.
- **PSR Compliance**: Adheres to PHP-FIG standards for coding style (PSR-12) and autoloading (PSR-4).

## Available Data Structures

### TreeSet

An ordered set implementation based on a self-balancing binary search tree (Red-Black Tree).

#### Complexity Analysis

- Time Complexity:
  - Add, Remove, Contains: O(log n)
  - Minimum/Maximum: O(log n)
  - Iteration: O(n)
- Space Complexity: O(n)

#### Key Methods

```php
public function add($element): void
public function remove($element): bool
public function contains($element): bool
public function union(TreeSet $other): TreeSet
public function intersection(TreeSet $other): TreeSet
public function difference(TreeSet $other): TreeSet
public function find(mixed $element): ?mixed
```

#### Usage Example

```php
use KaririCode\DataStructure\Set\TreeSet;

$set = new TreeSet();
$set->add(5);
$set->add(3);
$set->add(7);
echo $set->contains(3); // Output: true
echo $set->find(5);   // Output: 5
```

### ArrayDeque

A double-ended queue using a dynamic circular array.

#### Complexity Analysis

- Time Complexity:
  - AddFirst, AddLast, RemoveFirst, RemoveLast: Amortized O(1)
  - Get, Set: O(1)
- Space Complexity: O(n)

#### Key Methods

```php
public function addFirst($element): void
public function addLast($element): void
public function removeFirst(): mixed
public function removeLast(): mixed
public function getFirst(): mixed
public function getLast(): mixed
public function size(): int
public function add(mixed $element): void
```

#### Usage Example

```php
use KaririCode\DataStructure\Queue\ArrayDeque;

$deque = new ArrayDeque();
$deque->addFirst(1);
$deque->addLast(2);
echo $deque->removeFirst(); // Output: 1
echo $deque->removeLast();  // Output: 2
```

### ArrayQueue

A simple queue using a circular array, providing amortized O(1) time complexity for enqueue and dequeue operations.

#### Complexity Analysis

- Time Complexity:
  - Enqueue, Dequeue: Amortized O(1)
- Space Complexity: O(n)

#### Key Methods

```php
public function enqueue(mixed $element): void
public function dequeue(): mixed
public function peek(): mixed
public function isEmpty(): bool
public function size(): int
public function clear(): void
public function add(mixed $element): void
public function removeFirst(): mixed
```

#### Usage Example

```php
use KaririCode\DataStructure\Queue\ArrayQueue;

$queue = new ArrayQueue();
$queue->add(1);
$queue->enqueue(2);
echo $queue->dequeue(); // Output: 1
```

### TreeMap

A map implementation based on a self-balancing binary search tree (Red-Black Tree).

#### Complexity Analysis

- Time Complexity:
  - Put, Get, Remove: O(log n)
  - ContainsKey: O(log n)
  - Iteration: O(n)
- Space Complexity: O(n)

#### Key Methods

```php
public function put($key, $value): void
public function get($key): ?mixed
public function remove($key): bool
public function containsKey($key): bool
public function keys(): array
public function values(): array
public function clear(): void
public function getItems(): array
```

#### Usage Example

```php
use KaririCode\DataStructure\Map\TreeMap;

$map = new TreeMap();
$map->put("one", 1);
$map->put("two", 2);
echo $map->get("one"); // Output: 1
$map->remove("two");
echo $map->containsKey("two"); // Output: false
```

### LinkedList

A doubly-linked list implementation providing efficient insertions and deletions.

#### Complexity Analysis

- Time Complexity:
  - Add, Remove: O(1)
  - Get, Set: O(n)
  - Iteration: O(n)
- Space Complexity: O(n)

#### Key Methods

```php
public function add($element): void
public function remove($element): bool
public function contains($element): bool
public function get(int $index): mixed
public function set(int $index, $element): void
public function clear(): void
public function size(): int
public function getItems(): array
```

#### Usage Example

```php
use KaririCode\DataStructure\Collection\LinkedList;

$list = new LinkedList();
$list->add("first");
$list->add("second");
echo $list->get(1); // Output: "second"
$list->remove("first");
echo $list->contains("first"); // Output: false
```

### BinaryHeap

A binary heap implementation supporting both min-heap and max-heap functionality.

#### Complexity Analysis

- Time Complexity:
  - Insert, ExtractMin/Max: O(log n)
  - PeekMin/Max: O(1)
- Space Complexity: O(n)

#### Key Methods

```php
public function insert($element): void
public function extractMin(): mixed // For MinHeap
public function extractMax(): mixed // For MaxHeap
public function peek(): mixed
public function size(): int
public function isEmpty(): bool
```

#### Usage Example

```php
use KaririCode\DataStructure\BinaryHeap;

$heap = new BinaryHeap();
$heap->insert(5);
$heap->insert(3);
$heap->insert(7);
echo $heap->extractMin(); // Output: 3
echo $heap->peek();       // Output: 5
```

### HashMap

A hash map using PHP's built-in array as the underlying storage, providing O(1) average time complexity for put, get, and remove operations.

#### Complexity Analysis

- Time Complexity:
  - Put, Get, Remove: Average O(1), Worst O(n)
  - ContainsKey: Average O(1), Worst O(n)
- Space Complexity: O(n)

#### Key Methods

```php
public function put($key, $value): void
public function get($key): ?mixed
public function remove($key): bool
public function containsKey($key): bool
public function keys(): array
public function values(): array
public function clear(): void
public function size(): int
public function getIterator(): \Iterator
```

#### Usage Example

```php
use KaririCode\DataStructure\Map\HashMap;

$map = new HashMap();
$map->put("one", 1);
$map->put("two", 2);
echo $map->get("one"); // Output: 1
$map->remove("two");
echo $map->containsKey("two"); // Output: false
```

### BinaryHeap

A binary heap implementation supporting both min-heap and max-heap functionality.

#### Complexity Analysis

- Time Complexity:
  - Insert, ExtractMin/Max: O(log n)
  - PeekMin/Max: O(1)
- Space Complexity: O(n)

#### Key Methods

```php
public function insert($element): void
public function extractMin(): mixed // For MinHeap
public function extractMax(): mixed // For MaxHeap
public function peek(): mixed
public function size(): int
public function isEmpty(): bool
```

#### Usage Example

```php
use KaririCode\DataStructure\BinaryHeap;

$heap = new BinaryHeap('min');
$heap->add(5);
$heap->add(3);
$heap->add(7);
echo $heap->poll(); // Output: 3
echo $heap->peek(); // Output: 5
```

## Installation

### Requirements

- PHP 8.0 or higher
- Composer

### Via Composer

```bash
composer require kariricode/data-structures
```

### Manual Installation

Add the following to your `composer.json`:

```json
{
  "require": {
    "kariricode/data-structures": "^1.0"
  }
}
```

Then run:

```bash
composer update
```

## Testing

To run tests, use PHPUnit. Ensure you have PHPUnit installed and configured. You can run the tests using the following command:

```bash
vendor/bin/phpunit --testdox
```

## Contributing

We welcome contributions from the community! Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

### Development Setup

1. Fork and clone the repository.
2. Install dependencies: `composer install`.
3. Run tests: `./vendor/bin/phpunit`.
4. Submit a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support and Community

- **Documentation**: [https://docs.kariricode.com/data-structures](https://docs.kariricode.com/data-structures)
- **Issue Tracker**: [GitHub Issues](https://github.com/kariricode/data-structures/issues)
- **Community Forum**: [KaririCode Community](https://community.kariricode.com)
- **Professional Support**: For enterprise-grade support, contact us at enterprise@kariricode.com

## Acknowledgments

- The KaririCode Framework team and contributors.
- The PHP community for their continuous support and inspiration.
- [PHPBench](https://github.com/phpbench/phpbench) for performance benchmarking tools.

## Roadmap

- [ ] Implement Skip List data structure.
- [ ] Add support for concurrent access in HashMap.
- [ ] Develop a B-Tree implementation for large datasets.
- [ ] Enhance documentation with more real-world use cases.
- [ ] Implement a graph data structure and common algorithms.

---

Built with ❤️ by the KaririCode team. Empowering developers to build faster, more efficient PHP applications.

Maintained by Walmir Silva - [walmir.silva@kariricode.org](mailto:walmir.silva@kariricode.org)
