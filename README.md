# PHP Tree Type Converters

[![tests](https://img.shields.io/github/workflow/status/vhood/php-tree-converter/release)](https://github.com/vhood/php-tree-converter/actions/workflows/release.yml)
[![version](https://img.shields.io/packagist/v/vhood/tree-converter)](https://packagist.org/packages/vhood/tree-converter)
[![downloads](https://img.shields.io/packagist/dt/vhood/tree-converter)](https://packagist.org/packages/vhood/tree-converter)
[![license](https://img.shields.io/github/license/vhood/php-tree-converter)](/LICENSE)

This package based on native php arrays allows you to switch a tree type.

Supported types:

- Adjacency list
- Materialized path
- Nested set
- Associative arrays

See **[data example](/tests/data/)**

## Installation

```bash
composer require vhood/tree-converter
```

## Requirements

- php >=5.6

## Usage

Available methods:

- `AdjacencyList::toTree()`
- `AdjacencyList::toMaterializedPath()`
- `AdjacencyList::toNestedSet()`
- `MaterializedPath::toTree()`
- `MaterializedPath::toAdjacencyList()`
- `MaterializedPath::toNestedSet()`
- `NestedSet::toTree()`
- `NestedSet::toAdjacencyList()`
- `NestedSet::toMaterializedPath()`
- `Tree::toAdjacencyList()`
- `Tree::toMaterializedPath()`
- `Tree::toNestedSet()`

Usage example:

```php
use Vhood\TreeType\AdjacencyList;

$flatTree = require 'adjacency-list.php';

$converter = new AdjacencyList($flatTree);
$associativeArrayTree = $converter->toTree();
```

---

[History](/CHANGELOG.md)
