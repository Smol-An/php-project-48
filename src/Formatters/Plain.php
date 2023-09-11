<?php

namespace Differ\Formatters\Plain;

function genPlainDiff(array $diff, array $parentKeys = []): array
{
    $output = [];

    foreach ($diff as $key => $node) {
        $currentKeys = [...$parentKeys, $key];
        $propertyPath = implode('.', $currentKeys);

        switch ($node['status']) {
            case 'added':
                $formattedValue = formatValue($node['value']);
                $output[] = "Property '{$propertyPath}' was added with value: {$formattedValue}";
                break;
            case 'removed':
                $formattedValue = formatValue($node['value']);
                $output[] = "Property '{$propertyPath}' was removed";
                break;
            case 'updated':
                $formattedOldValue = formatValue($node['oldValue']);
                $formattedNewValue = formatValue($node['newValue']);
                $output[] = "Property '{$propertyPath}' was updated. From {$formattedOldValue} to {$formattedNewValue}";
                break;
            case 'nested':
                $output = array_merge($output, genPlainDiff($node['children'], $currentKeys));
                break;
        }
    }

    return $output;
}

function formatValue($value)
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    return $value;
}

function getPlainDiff(array $diff): string
{
    $plainDiff = genPlainDiff($diff);
    return implode("\n", $plainDiff) . "\n";
}
