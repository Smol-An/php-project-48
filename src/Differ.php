<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\getFormattedDiff;

function findDiff($data1, $data2)
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);
    $diff = [];

    foreach ($keys as $key) {
        if (!array_key_exists($key, $data1)) {
            $diff[$key] = ['status' => 'added', 'value' => $data2[$key]];
        } elseif (!array_key_exists($key, $data2)) {
            $diff[$key] = ['status' => 'removed', 'value' => $data1[$key]];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            $diff[$key] = ['status' => 'nested', 'children' => findDiff($data1[$key], $data2[$key])];
        } elseif ($data1[$key] !== $data2[$key]) {
            $diff[$key] = [
                'status' => 'updated',
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key],
            ];
        } else {
            $diff[$key] = ['status' => 'unchanged', 'value' => $data1[$key]];
        }
    }

    return $diff;
}

function genDiff($pathToFile1, $pathToFile2, $formatName = 'stylish')
{
    [$data1, $data2] = parse($pathToFile1, $pathToFile2);
    $diff = findDiff($data1, $data2);
    return getFormattedDiff($diff, $formatName);
}
