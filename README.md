# KaririCode Contract

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md)
[![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Makefile](https://img.shields.io/badge/Makefile-1D1D1D?style=for-the-badge&logo=gnu&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PHPUnit](https://img.shields.io/badge/PHPUnit-78E130?style=for-the-badge&logo=phpunit&logoColor=white)

## Overview

The `kariricode/kariricode-data-structure` package provides a set of standardized interfaces for common data structures and patterns within the KaririCode Framework. This library ensures consistency and interoperability across various components of the KaririCode ecosystem, following PSR standards and utilizing modern PHP practices.

## Features

- **🗂️ PSR Standards**: Adheres to PHP-FIG PSR standards for interoperability.
- **📚 Comprehensive Interfaces**: Includes interfaces for common data structures such as Collection, Heap, Map, Queue, Stack, and Tree.
- **🚀 Modern PHP**: Utilizes PHP 8.3 features to ensure type safety and modern coding practices.
- **🔍 High Quality**: Ensures code quality and security through rigorous testing and analysis tools.

## Installation

You can install the package via Composer:

```bash
composer require kariricode/kariricode-data-structure
```

## Usage

Implement the provided interfaces in your classes to ensure consistent and reliable functionality across different components of the KaririCode Framework.

Example of implementing the `CollectionList` interface:

```php
<?php

declare(strict_types=1);

namespace YourNamespace;

use KaririCode\Contract\DataStructure\CollectionList;

class MyCollection implements CollectionList
{
    private array $items = [];

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function remove(mixed $item): bool
    {
        $index = array_search($item, $this->items, true);
        if ($index === false) {
            return false;
        }
        unset($this->items[$index]);
        return true;
    }

    public function get(int $index): mixed
    {
        return $this->items[$index] ?? null;
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }
}
```

## Development Environment

### Docker

To maintain consistency and ensure the environment's integrity, we provide a Docker setup:

- **🐳 Docker Compose**: Used to manage multi-container Docker applications.
- **📦 Dockerfile**: Defines the Docker image for the PHP environment.

To start the environment:

```bash
make up
```

### Makefile

We include a `Makefile` to streamline common development tasks:

- **Start services**: `make up`
- **Stop services**: `make down`
- **Run tests**: `make test`
- **Install dependencies**: `make composer-install`
- **Run code style checks**: `make cs-check`
- **Fix code style issues**: `make cs-fix`
- **Security checks**: `make security-check`

For a complete list of commands, run:

```bash
make help
```

## Testing

To run the tests, you can use the following command:

```bash
make test
```

## Contributing

Contributions are welcome! Please read our [contributing guidelines](CONTRIBUTING.md) for details on the process for submitting pull requests.

## Support

For any issues, please visit our [issue tracker](https://github.com/Kariri-PHP-Framework/kariri-contract/issues).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## About KaririCode

The KaririCode Framework is a modern, robust, and scalable PHP framework designed to streamline web development by providing a comprehensive set of tools and components. For more information, visit the [KaririCode website](https://kariricode.org/).

Join the KaririCode Club for access to exclusive content, community support, and advanced tutorials on PHP and the KaririCode Framework. Learn more at [KaririCode Club](https://kariricode.org/club).

---

Maintained by Walmir Silva - [walmir.silva@kariricode.org](mailto:walmir.silva@kariricode.org)
