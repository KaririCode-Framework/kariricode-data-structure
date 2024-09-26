# KaririCode Framework: DataStructure Component

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md)
[![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)
![Estruturas de Dados](https://img.shields.io/badge/Estruturas_de_Dados-E74C3C?style=for-the-badge&logo=data-structures&logoColor=white)

O **KaririCode DataStructure** é um componente que fornece uma coleção de estruturas de dados avançadas implementadas em PHP, com foco em tipagem forte e princípios de programação orientada a objetos. Ele inclui implementações de diversas estruturas comuns, como arrays dinâmicos, listas encadeadas, heaps, filas, mapas, conjuntos e pilhas.

## Funcionalidades

- **ArrayList**: Um array dinâmico que oferece acesso rápido e complexidade amortizada O(1) para adicionar elementos.
- **LinkedList**: Uma lista duplamente encadeada com inserção e remoção O(1) nas extremidades e O(n) para acesso em índices arbitrários.
- **BinaryHeap**: Um heap binário (min-heap ou max-heap) com O(log n) para inserção, remoção e polling.
- **HashMap**: Um mapa baseado em hash que oferece complexidade média de O(1) para as operações `put`, `get` e `remove`.
- **TreeMap**: Um mapa implementado como uma árvore rubro-negra, com complexidade O(log n) para `put`, `get` e `remove`.
- **TreeSet**: Um conjunto implementado com base no `TreeMap`, garantindo que os elementos sejam armazenados em ordem.
- **ArrayDeque**: Uma fila de duas extremidades usando um array circular com operações amortizadas O(1) nas duas extremidades.
- **ArrayStack**: Uma pilha implementada usando um array dinâmico, fornecendo complexidade O(1) para `push`, `pop` e `peek`.

## Instalação

Para instalar o componente **KaririCode DataStructure**, utilize o seguinte comando:

```bash
composer require kariricode/data-structure
```

## Exemplos de Uso

### Exemplo de ArrayList

```php
use KaririCode\DataStructure\Collection\ArrayList;

$list = new ArrayList();
$list->add("Item 1");
$list->add("Item 2");
echo $list->get(0); // Saída: Item 1
```

### Exemplo de LinkedList

```php
use KaririCode\DataStructure\Collection\LinkedList;

$linkedList = new LinkedList();
$linkedList->add("Primeiro");
$linkedList->add("Segundo");
$linkedList->remove("Primeiro");
```

### Exemplo de BinaryHeap

```php
use KaririCode\DataStructure\Heap\BinaryHeap;

$heap = new BinaryHeap();
$heap->add(10);
$heap->add(5);
$heap->add(20);
echo $heap->poll(); // Saída: 5 (min-heap por padrão)
```

### Exemplo de HashMap

```php
use KaririCode\DataStructure\Map\HashMap;

$map = new HashMap();
$map->put("chave1", "valor1");
echo $map->get("chave1"); // Saída: valor1
```

### Exemplo de TreeSet

```php
use KaririCode\DataStructure\Set\TreeSet;

$set = new TreeSet();
$set->add("valor1");
$set->add("valor2");
echo $set->contains("valor1"); // Saída: true
```

### Exemplo de ArrayStack

```php
use KaririCode\DataStructure\Stack\ArrayStack;

$stack = new ArrayStack();
$stack->push("Primeiro");
$stack->push("Segundo");
echo $stack->peek(); // Saída: Segundo
$stack->pop();       // Remove "Segundo"
```

### Exemplo de ArrayDeque

```php
use KaririCode\DataStructure\Queue\ArrayDeque;

$deque = new ArrayDeque();
$deque->addFirst("Primeiro");
$deque->addLast("Último");
echo $deque->peekLast(); // Saída: Último
$deque->removeLast();    // Remove "Último"
```

## Testes

Para rodar os testes do componente **KaririCode DataStructure**, execute o seguinte comando:

```bash
make test
```

## Licença

Este projeto é licenciado sob a licença MIT. Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.

## Suporte e Comunidade

- **Documentação**: [https://kariricode.org](https://kariricode.org)
- **Rastreador de Problemas**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-datastructure/issues)
- **Comunidade**: [KaririCode Club Community](https://kariricode.club)
- **Suporte Profissional**: Para suporte empresarial, entre em contato conosco pelo e-mail support@kariricode.org

---

Feito com ❤️ pela equipe KaririCode. Mantido por Walmir Silva - [walmir.silva@kariricode.org](mailto:walmir.silva@kariricode.org)
