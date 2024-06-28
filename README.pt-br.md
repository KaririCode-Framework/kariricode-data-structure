# KaririCode Contract

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md)
[![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Makefile](https://img.shields.io/badge/Makefile-1D1D1D?style=for-the-badge&logo=gnu&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PHPUnit](https://img.shields.io/badge/PHPUnit-78E130?style=for-the-badge&logo=phpunit&logoColor=white)

## Visão Geral

O pacote `kariricode/kariricode-data-structure` fornece um conjunto de interfaces padronizadas para estruturas de dados e padrões comuns dentro do KaririCode Framework. Esta biblioteca garante consistência e interoperabilidade entre vários componentes do ecossistema KaririCode, seguindo os padrões PSR e utilizando práticas modernas de PHP.

## Funcionalidades

- **🗂️ Padrões PSR**: Adere aos padrões PSR do PHP-FIG para interoperabilidade.
- **📚 Interfaces Abrangentes**: Inclui interfaces para estruturas de dados comuns, como Collection, Heap, Map, Queue, Stack e Tree.
- **🚀 PHP Moderno**: Utiliza recursos do PHP 8.3 para garantir segurança de tipos e práticas de codificação modernas.
- **🔍 Alta Qualidade**: Garante qualidade e segurança do código através de rigorosos testes e ferramentas de análise.

## Instalação

Você pode instalar o pacote via Composer:

```bash
composer require kariricode/kariricode-data-structure
```

## Uso

Implemente as interfaces fornecidas em suas classes para garantir funcionalidade consistente e confiável entre diferentes componentes do KaririCode Framework.

Exemplo de implementação da interface `CollectionList`:

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

## Ambiente de Desenvolvimento

### Docker

Para manter a consistência e garantir a integridade do ambiente, fornecemos uma configuração Docker:

- **🐳 Docker Compose**: Usado para gerenciar aplicações Docker de múltiplos contêineres.
- **📦 Dockerfile**: Define a imagem Docker para o ambiente PHP.

Para iniciar o ambiente:

```bash
make up
```

### Makefile

Incluímos um `Makefile` para simplificar tarefas comuns de desenvolvimento:

- **Iniciar serviços**: `make up`
- **Parar serviços**: `make down`
- **Executar testes**: `make test`
- **Instalar dependências**: `make composer-install`
- **Verificar estilo de código**: `make cs-check`
- **Corrigir problemas de estilo de código**: `make cs-fix`
- **Verificações de segurança**: `make security-check`

Para uma lista completa de comandos, execute:

```bash
make help
```

## Testes

Para executar os testes, você pode usar o seguinte comando:

```bash
make test
```

## Contribuindo

Contribuições são bem-vindas! Por favor, leia nossas [diretrizes de contribuição](CONTRIBUTING.md) para detalhes sobre o processo de envio de pull requests.

## Suporte

Para qualquer problema, por favor, visite nosso [rastreador de problemas](https://github.com/Kariri-PHP-Framework/kariri-contract/issues).

## Licença

Este projeto é licenciado sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## Sobre o KaririCode

O KaririCode Framework é um framework PHP moderno, robusto e escalável, projetado para simplificar o desenvolvimento web, fornecendo um conjunto abrangente de ferramentas e componentes. Para mais informações, visite o [site do KaririCode](https://kariricode.org/).

Junte-se ao Clube KaririCode para ter acesso a conteúdos exclusivos, suporte da comunidade e tutoriais avançados sobre PHP e o KaririCode Framework. Saiba mais em [Clube KaririCode](https://kariricode.org/club).

---

Mantido por Walmir Silva - [walmir.silva@kariricode.org](mailto:walmir.silva@kariricode.org)
