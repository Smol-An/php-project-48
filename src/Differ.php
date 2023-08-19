<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\getFormattedDiff;

function findDifferences($data1, $data2, $depth = 0)
{
    $diff = [];

    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($allKeys);

    foreach ($allKeys as $key) {
        if (
            isset($data1[$key]) && isset($data2[$key])
            && is_array($data1[$key]) && is_array($data2[$key])
        ) {
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

function genDiff($pathToFile1, $pathToFile2, $formatName = 'stylish')
{
    [$data1, $data2] = parse($pathToFile1, $pathToFile2);
    $diff = findDifferences($data1, $data2);
    return getFormattedDiff($diff, $formatName);
}
