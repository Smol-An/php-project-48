<?php

namespace Differ\Formatters\Plain;

use function Functional\flatten;

function buildPlainDiff(array $diff, array $parentKeys = []): array
{
    return array_map(function ($node) use ($parentKeys) {
        $key = $node['key'];
        $value = $node['value'] ?? null;
        $oldValue = $node['oldValue'] ?? null;
        $newValue = $node['newValue'] ?? null;
        $children = $node['children'] ?? null;

        $currentKeys = [...$parentKeys, $key];
        $propertyPath = implode('.', $currentKeys);

        switch ($node['status']) {
            case 'added':
                $formattedValue = formatValue($value);
                return ["Property '$propertyPath' was added with value: $formattedValue"];
            case 'removed':
                return ["Property '$propertyPath' was removed"];
            case 'nested':
                return buildPlainDiff($children, $currentKeys);
            case 'updated':
                $formattedOldValue = formatValue($oldValue);
                $formattedNewValue = formatValue($newValue);
                return ["Property '$propertyPath' was updated. From $formattedOldValue to $formattedNewValue"];
            case 'unchanged':
                return [];
        }
    }, $diff);
}

function formatValue(mixed $value): string
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

function getDiff(array $diff): string
{
    $plainDiff = buildPlainDiff($diff);
    return implode("\n", flatten($plainDiff));
}
