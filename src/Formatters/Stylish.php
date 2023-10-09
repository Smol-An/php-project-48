<?php

namespace Differ\Formatters\Stylish;

function buildStylishDiff(array $diff, int $depth = 0): string
{
    $output = array_map(function ($node) use ($depth) {
        $indent = getIndent($depth);
        $key = $node['key'];
        $value = $node['value'] ?? null;
        $oldValue = $node['oldValue'] ?? null;
        $newValue = $node['newValue'] ?? null;
        $children = $node['children'] ?? null;

        switch ($node['status']) {
            case 'added':
                $formattedValue = formatValue($value, $depth + 1);
                return "$indent  + $key: $formattedValue";
            case 'removed':
                $formattedValue = formatValue($value, $depth + 1);
                return "$indent  - $key: $formattedValue";
            case 'nested':
                $nestedDiff = buildStylishDiff($children, $depth + 1);
                return "$indent    $key: {\n$nestedDiff\n$indent    }";
            case 'updated':
                $formattedOldValue = formatValue($oldValue, $depth + 1);
                $formattedNewValue = formatValue($newValue, $depth + 1);
                return "$indent  - $key: $formattedOldValue\n$indent  + $key: $formattedNewValue";
            case 'unchanged':
                $formattedValue = formatValue($value, $depth + 1);
                return "$indent    $key: $formattedValue";
        }
    }, $diff);

    return implode("\n", $output);
}

function getIndent(int $depth = 1, int $spacesCount = 4): string
{
    return str_repeat(" ", $spacesCount * $depth);
}

function formatValue(mixed $value, int $depth): string
{
    if (is_object($value)) {
        $valueArray = get_object_vars($value);
        $indent = getIndent($depth);
        $formattedArray = array_map(function ($key, $val) use ($depth, $indent) {
            $formattedValue = formatValue($val, $depth + 1);
            return "$indent    $key: $formattedValue";
        }, array_keys($valueArray), $valueArray);

        return "{\n" . implode("\n", $formattedArray) . "\n" . $indent . "}";
    }

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
