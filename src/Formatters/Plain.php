<?php

namespace Differ\Formatters\Plain;

use function Functional\flatten;

function buildPlainDiff(array $diff, array $parentKeys = []): array
{
    return array_map(function ($node) use ($parentKeys) {
        $key = $node['key'];
        $currentKeys = [...$parentKeys, $key];
        $propertyPath = implode('.', $currentKeys);

        switch ($node['status']) {
            case 'added':
                $formattedValue = formatValue($node['value']);
                return ["Property '$propertyPath' was added with value: $formattedValue"];
            case 'removed':
                return ["Property '$propertyPath' was removed"];
            case 'nested':
                return buildPlainDiff($node['children'], $currentKeys);
            case 'updated':
                $formattedOldValue = formatValue($node['oldValue']);
                $formattedNewValue = formatValue($node['newValue']);
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

    return "'$value'";
}

function getPlainDiff(array $diff): string
{
    $plainDiff = buildPlainDiff($diff);
    return implode("\n", flatten($plainDiff));
}
