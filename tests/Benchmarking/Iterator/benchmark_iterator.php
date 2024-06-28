<?php

declare(strict_types=1);

namespace KaririCode\DataStructure\Benchmarking\Iterator;

require __DIR__ . '/../../../vendor/autoload.php';

use KaririCode\Contract\DataStructure\Behavioral\Iterator;

class ArrayListIteratorNormal implements Iterator
{
    private array $books;
    private int $index = 0;

    public function __construct(array $books)
    {
        $this->books = $books;
    }

    public function current(): mixed
    {
        return $this->books[$this->index];
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function valid(): bool
    {
        return \array_key_exists($this->index, $this->books);
    }
}

class IteratorBenchmark
{
    private const ITERATIONS = 5;
    private const ELEMENT_COUNT = 1000000;

    public static function run(): void
    {
        $data = range(1, self::ELEMENT_COUNT);

        $results = [
            'Foreach Loop' => self::benchmarkForeach($data),
            'ArrayIterator' => self::benchmarkArrayIterator($data),
            'Generator' => self::benchmarkGenerator(),
        ];

        self::printResults($results);
    }

    private static function benchmark(callable $func): array
    {
        $times = [];
        $memories = [];

        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            $func();

            $times[] = microtime(true) - $startTime;
            $memories[] = memory_get_usage() - $startMemory;
        }

        return [
            'time' => array_sum($times) / self::ITERATIONS,
            'memory' => array_sum($memories) / self::ITERATIONS,
        ];
    }

    private static function benchmarkForeach(array $data): array
    {
        return self::benchmark(function () use ($data) {
            foreach ($data as $item) {
                // Simulate work
                $dummy = $item;
            }
        });
    }

    private static function benchmarkArrayIterator(array $data): array
    {
        return self::benchmark(function () use ($data) {
            $iterator = new \ArrayIterator($data);
            while ($iterator->valid()) {
                $dummy = $iterator->current();
                $iterator->next();
            }
        });
    }

    private static function benchmarkGenerator(): array
    {
        return self::benchmark(function () {
            $generator = (function () {
                for ($i = 1; $i <= self::ELEMENT_COUNT; ++$i) {
                    yield $i;
                }
            })();

            foreach ($generator as $item) {
                // Simulate work
                $dummy = $item;
            }
        });
    }

    private static function printResults(array $results): void
    {
        foreach ($results as $name => $result) {
            printf(
                "%s:\n" .
                "  Time: %.6f seconds\n" .
                "  Memory: %.2f KB\n\n",
                $name,
                $result['time'],
                $result['memory'] / 1024
            );
        }

        // Find the fastest method
        $fastest = array_keys($results, min($results))[0];

        echo "Comparação de Desempenho:\n";
        foreach ($results as $name => $result) {
            $timeRatio = $result['time'] / $results[$fastest]['time'];
            $memoryRatio = $result['memory'] / $results[$fastest]['memory'];
            printf(
                "%s é %.2fx mais lento e usa %.2fx mais memória que %s\n",
                $name,
                $timeRatio,
                $memoryRatio,
                $fastest
            );
        }
    }
}

// Execute o benchmark
IteratorBenchmark::run();
