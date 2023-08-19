<?php

namespace Differ\Formatters\Stylish;

function getStylishDiff($diff, $depth = 0)
{
    $indent = str_repeat(' ', $depth * 4);
    $closeIndent = str_repeat(' ', $depth * 4 + 4);
    $output = "";

    foreach ($diff as $key => $value) {
        if (is_array($value)) {
            $output .= $indent . formatKey($key) . ": {\n";
            $output .= getStylishDiff($value, $depth + 1);
            $output .= $closeIndent . "}\n";
        } elseif ($value === '') {
            $output .= $indent . formatKey($key) . ":" . "\n";
        } else {
            $output .= $indent . formatKey($key) . ": " . formatValue($value) . "\n";
        }
    }

    return $output;
}

function formatKey($key)
{
    if (substr($key, 0, 4) === '  + ') {
        return $key;
    } elseif (substr($key, 0, 4) === '  - ') {
        return $key;
    } elseif (substr($key, 0, 4) === '    ') {
        return $key;
    }

    return '    ' . $key;
}

function formatValue($value)
{
    if ($value === null) {
        return 'null';
    }

    return trim(var_export($value, true), "'");
}
