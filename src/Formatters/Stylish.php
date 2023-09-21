<?php

namespace Differ\Formatters\Stylish;

function buildStylishDiff(array $diff, int $depth = 0): string
{
    $output = array_map(function ($node) use ($depth) {
        $indent = getIndent($depth);
        $key = $node['key'];
        switch ($node['status']) {
            case 'added':
                $formattedValue = stringify($node['value'], $depth + 1);
                return "$indent  + $key: $formattedValue";
            case 'removed':
                $formattedValue = stringify($node['value'], $depth + 1);
                return "$indent  - $key: $formattedValue";
            case 'nested':
                $nestedDiff = buildStylishDiff($node['children'], $depth + 1);
                return "$indent    $key: {\n$nestedDiff\n$indent    }";
            case 'updated':
                $formattedOldValue = stringify($node['oldValue'], $depth + 1);
                $formattedNewValue = stringify($node['newValue'], $depth + 1);
                return "$indent  - $key: $formattedOldValue\n$indent  + $key: $formattedNewValue";
            case 'unchanged':
                $formattedValue = stringify($node['value'], $depth + 1);
                return "$indent    $key: $formattedValue";
        }
    }, $diff);

    return implode("\n", $output);
}

function getIndent(int $depth = 1, int $spacesCount = 4): string
{
    return str_repeat(" ", $spacesCount * $depth);
}

function stringify(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return formatValue($value);
    }

    $indent = getIndent($depth);
    $formattedArray = array_map(function ($key, $val) use ($depth, $indent) {
        $formattedValue = stringify($val, $depth + 1);
        return "$indent    $key: $formattedValue";
    }, array_keys($value), $value);

    return "{\n" . implode("\n", $formattedArray) . "\n" . $indent . "}";
}

function formatValue(mixed $value): string
{
    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return trim($value, "'");
}

function getStylishDiff(array $diff): string
{
    $stylishDiff = buildStylishDiff($diff);
    return "{\n" . $stylishDiff . "\n}";
}
