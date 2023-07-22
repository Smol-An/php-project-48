<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;

function getValueAsString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return $value;
}

function genDiff($pathToFile1, $pathToFile2)
{
    [$data1, $data2] = parse($pathToFile1, $pathToFile2);

    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);

    $result = [];
    foreach ($keys as $key) {
        if (!array_key_exists($key, $data1)) {
            $result[] = " + $key: " . getValueAsString($data2[$key]);
        } elseif (!array_key_exists($key, $data2)) {
            $result[] = " - $key: " . getValueAsString($data1[$key]);
        } elseif ($data1[$key] !== $data2[$key]) {
            $result[] = " - $key: " . getValueAsString($data1[$key]);
            $result[] = " + $key: " . getValueAsString($data2[$key]);
        } else {
            $result[] = "   $key: " . getValueAsString($data1[$key]);
        }
    }

    return "{\n" . implode("\n", $result) . "\n}\n";
}
