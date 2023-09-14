<?php

namespace Differ\Formatters\Stylish;

function genStylishDiff(array $diff, int $depth = 0): string
{
    $indent = str_repeat(" ", $depth * 4);

    $output = array_map(function ($key, $node) use ($depth, $indent) {
        switch ($node['status']) {
            case 'added':
                $formattedValue = formatValue($node['value'], $depth + 1);
                return "{$indent}  + {$key}: {$formattedValue}";
            case 'removed':
                $formattedValue = formatValue($node['value'], $depth + 1);
                return "{$indent}  - {$key}: {$formattedValue}";
            case 'updated':
                $formattedOldValue = formatValue($node['oldValue'], $depth + 1);
                $formattedNewValue = formatValue($node['newValue'], $depth + 1);
                return [
                    "{$indent}  - {$key}: {$formattedOldValue}",
                    "{$indent}  + {$key}: {$formattedNewValue}"
                ];
            case 'nested':
                $nestedDiff = genStylishDiff($node['children'], $depth + 1);
                return "{$indent}    {$key}: {\n{$nestedDiff}\n{$indent}    }";
            case 'unchanged':
                $formattedValue = formatValue($node['value'], $depth + 1);
                return "{$indent}    {$key}: {$formattedValue}";
        }
    }, array_keys($diff), $diff);

    return implode("\n", array_reduce($output, function ($carry, $item) {
        if (is_array($item)) {
            return array_merge($carry, $item);
        } else {
            $carry[] = $item;
            return $carry;
        }
    }, []));
}

function formatValue(mixed $value, int $depth): string
{
    if (is_array($value)) {
        $formattedArray = array_map(function ($key, $val) use ($depth) {
            $formattedValue = formatValue($val, $depth + 1);
            $keyIndent = str_repeat(" ", $depth * 4);
            return "{$keyIndent}    {$key}: {$formattedValue}";
        }, array_keys($value), $value);
        return "{\n" . implode("\n", $formattedArray) . "\n" . str_repeat(" ", $depth * 4) . "}";
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
    return "{\n" . genStylishDiff($diff) . "\n}";
}
