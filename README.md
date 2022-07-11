# PHP Tree Type Converter

[![tests](https://img.shields.io/github/workflow/status/vhood/php-tree-converter/release)](https://github.com/vhood/php-tree-converter/actions/workflows/release.yml)
[![version](https://img.shields.io/packagist/v/vhood/tree-converter)](https://packagist.org/packages/vhood/tree-converter)
[![downloads](https://img.shields.io/packagist/dt/vhood/tree-converter)](https://packagist.org/packages/vhood/tree-converter)
[![license](https://img.shields.io/github/license/vhood/php-tree-converter)](/LICENSE)

This package based on native php arrays allows you to switch a tree type.

Supported types:

- Adjacency list
- Materialized path
- Nested set
- Associative array trees

See **[data examples](/tests/_data/)**

## Installation

```bash
composer require vhood/tree-converter
```

## Requirements

- php >=5.6

## Usage

Usage example:

```php
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\AdjacencyList;

$adjacencyList = [
    [
        'id' => 1,
        'name' => 'node1',
        'parent_id' => null,
    ],
    [
        'id' => 2,
        'name' => 'node2',
        'parent_id' => 1,
    ],
];

$adjacencyListConverter = new Converter(new AdjacencyList($adjacencyList));

print_r($adjacencyListConverter->toAssociativeArrayTree('children', 'id'));

// Array
// (
//     [0] => Array
//         (
//             [id] => 1
//             [name] => node1
//             [children] => Array
//                 (
//                     [0] => Array
//                         (
//                             [id] => 2
//                             [name] => node2
//                             [children] => Array
//                                 (
//                                 )

//                         )

//                 )

//         )

// )
```

See **[all types](/src/Type)**

### Extra functionality

Materialized path level calculation example:

```php
use Vhood\TreeType\Converter;
use Vhood\TreeType\Type\MaterializedPath;

$materializedPath = [
    [
        'name' => 'node1',
        'path' => '/1/',
    ],
    [
        'name' => 'node2',
        'path' => '/1/2/',
    ],
];

$materializedPathConverter = new Converter(new MaterializedPath($materializedPath));

print_r($materializedPathConverter->toMaterializedPath('path', '/', 'level'));

// Array
// (
//     [0] => Array
//         (
//             [name] => node1
//             [path] => /1/
//             [level] => 1
//         )

//     [1] => Array
//         (
//             [name] => node2
//             [path] => /1/2/
//             [level] => 2
//         )

// )
```

Other features:

- nodes identification
- keys renaming
- keys removing

See the **[converting interface](/src/Contract/TypeConverter.php)**

---

[History](/CHANGELOG.md)
