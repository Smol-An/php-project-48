<?php

namespace Differ\Formatters\Plain;

function getPlainDiff($diff, $parentKeys = [])
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
                $output = array_merge($output, getPlainDiff($node['children'], $currentKeys));
                break;
        }
    }

    return $output;
}

function formatValue($value)
{
    if (is_array($value)) {
        return '[complex value]';
    } elseif (is_null($value)) {
        return 'null';
    } else {
        return var_export($value, true);
    }
}
