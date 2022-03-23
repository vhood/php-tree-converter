# PHP Tree Type Converters

This package based on native php arrays allows you to switch a tree type.

Supported types:

- Ajacency list
- Materialized path
- Nested set
- Associative array as tree

## Installation

```bash
composer require vhood/tree-converter
```

## Requirements

- php >=5.6

## Usage

```php
use Vhood\TreeType\AjacencyList;

$flatTree = require 'adjacency-list.php';

$converter = new AjacencyList($flatTree);
$converter->toTree();
```

---

[History](/CHANGELOG.md)
