# PHP Tree Type Converters

[![tests](https://img.shields.io/github/workflow/status/vhood/php-tree-converter/tests)](/actions)
[![version](https://img.shields.io/packagist/v/vhood/tree-converter)](https://packagist.org/packages/vhood/tree-converter)
[![downloads](https://img.shields.io/packagist/dt/vhood/tree-converter)](https://packagist.org/packages/vhood/tree-converter)
[![license](https://img.shields.io/github/license/vhood/php-tree-converter)](/LICENSE)

This package based on native php arrays allows you to switch a tree type.

Supported types:

- Ajacency list
- Materialized path
- Nested set
- Associative arrays

See [data example](/tests/data/)

## Installation

```bash
composer require vhood/tree-converter
```

## Requirements

- php >=5.6

## Usage

Available methods:

- `AjacencyList::toTree()`
- `AjacencyList::toMaterializedPath()`
- `AjacencyList::toNestedSet()`
- `MaterializedPath::toTree()`
- `MaterializedPath::toAjacencyList()`
- `MaterializedPath::toNestedSet()`
- `NestedSet::toTree()`
- `NestedSet::toAjacencyList()`
- `NestedSet::toMaterializedPath()`

Usage example:

```php
use Vhood\TreeType\AjacencyList;

$flatTree = require 'adjacency-list.php';

$converter = new AjacencyList($flatTree);
$associativeArrayTree = $converter->toTree();
```

---

[History](/CHANGELOG.md)
