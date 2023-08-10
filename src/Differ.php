<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;

function findDifferences($data1, $data2, $depth = 0)
{
    $diff = [];

    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($allKeys);

    foreach ($allKeys as $key) {
        if (is_array($data1[$key]) && is_array($data2[$key])) {
            $nestedDiff = findDifferences($data1[$key], $data2[$key], $depth + 1);
            if (!empty($nestedDiff)) {
                $diff['    ' . $key] = $nestedDiff;
            }
        } elseif (!array_key_exists($key, $data1)) {
            $diff['  + ' . $key] = $data2[$key];
        } elseif (!array_key_exists($key, $data2)) {
            $diff['  - ' . $key] = $data1[$key];
        } elseif ($data1[$key] !== $data2[$key]) {
            $diff['  - ' . $key] = $data1[$key];
            $diff['  + ' . $key] = $data2[$key];
        } else {
            $diff['    ' . $key] = $data1[$key];
        }
    }

    return $diff;
}

function getFormattedDiff($diff, $depth = 0)
{
    $indent = str_repeat(' ', $depth * 4);
    $closeIndent = str_repeat(' ', $depth * 4 + 4);
    $output = "";

    foreach ($diff as $key => $value) {
        if (is_array($value)) {
            $output .= $indent . $key . ": {\n";
            $output .= getFormattedDiff($value, $depth + 1);
            $output .= $closeIndent . "}\n";
        } else {
            $output .= $indent . $key . ": " . formatValue($value) . "\n";
        }
    }
    return $output;
}

function formatValue($value)
{
    if ($value === null) {
        return 'null';
    }
    return trim(var_export($value, true), "'");
}

function genDiff($pathToFile1, $pathToFile2)
{
    [$data1, $data2] = parse($pathToFile1, $pathToFile2);
    $diff = findDifferences($data1, $data2);
    $output = "{\n" . getFormattedDiff($diff) . "}\n";
    return $output;
}
