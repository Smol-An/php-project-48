<?php

namespace Differ\Formatters\Plain;

function genPlainDiff(array $diff, array $parentKeys = []): array
{
    return array_merge(
        ...array_map(function ($key, $node) use ($parentKeys) {
            $currentKeys = [...$parentKeys, $key];
            $propertyPath = implode('.', $currentKeys);

            switch ($node['status']) {
                case 'added':
                    $formattedValue = formatValue($node['value']);
                    return ["Property '{$propertyPath}' was added with value: {$formattedValue}"];
                case 'removed':
                    return ["Property '{$propertyPath}' was removed"];
                case 'updated':
                    $formattedOldValue = formatValue($node['oldValue']);
                    $formattedNewValue = formatValue($node['newValue']);
                    return ["Property '{$propertyPath}' was updated. From {$formattedOldValue} to {$formattedNewValue}"];
                case 'nested':
                    return genPlainDiff($node['children'], $currentKeys);
                default:
                    return [];
            }
        }, array_keys($diff), $diff)
    );
}

function formatValue(mixed $value)
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
    return implode("\n", $plainDiff);
}
