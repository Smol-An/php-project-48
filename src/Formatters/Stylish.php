<?php

namespace Differ\Formatters\Stylish;

function formStylishDiff(array $diff, int $depth = 0): string
{
    $indent = str_repeat(" ", $depth * 4);
    $output = [];

    foreach ($diff as $key => $node) {
        switch ($node['status']) {
            case 'added':
                $formattedValue = formatValue($node['value'], $depth + 1);
                $formattedValue === ''
                ? $output[] = "{$indent}  + {$key}:"
                : $output[] = "{$indent}  + {$key}: {$formattedValue}";
                break;
            case 'removed':
                $formattedValue = formatValue($node['value'], $depth + 1);
                $formattedValue === ''
                ? $output[] = "{$indent}  - {$key}:"
                : $output[] = "{$indent}  - {$key}: {$formattedValue}";
                break;
            case 'updated':
                $formattedOldValue = formatValue($node['oldValue'], $depth + 1);
                $formattedNewValue = formatValue($node['newValue'], $depth + 1);
                $formattedOldValue === ''
                ? $output[] = "{$indent}  - {$key}:"
                : $output[] = "{$indent}  - {$key}: {$formattedOldValue}";
                $formattedNewValue === ''
                ? $output[] = "{$indent}  + {$key}:"
                : $output[] = "{$indent}  + {$key}: {$formattedNewValue}";
                break;
            case 'nested':
                $output[] = "{$indent}    {$key}: {\n"
                    . formStylishDiff($node['children'], $depth + 1)
                    . "\n{$indent}    }";
                break;
            case 'unchanged':
                $formattedValue = formatValue($node['value'], $depth + 1);
                $formattedValue === ''
                ? $output[] = "{$indent}    {$key}:"
                : $output[] = "{$indent}    {$key}: {$formattedValue}";
                break;
        }
    }

    return implode("\n", $output);
}

function formatValue($value, int $depth): string
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
    return "{\n" . formStylishDiff($diff) . "\n}\n";
}
