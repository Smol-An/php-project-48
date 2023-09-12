<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Parsers\parse;
use function Differ\Formatters\getFormattedDiff;

function findDiff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = sort($keys, fn($a, $b) => strcmp($a, $b), true);

    $diff = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return [
                'status' => 'added',
                'value' => $data2[$key]
            ];
        } elseif (!array_key_exists($key, $data2)) {
            return [
                'status' => 'removed',
                'value' => $data1[$key]
            ];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            return [
                'status' => 'nested',
                'children' => findDiff($data1[$key], $data2[$key])
            ];
        } elseif ($data1[$key] !== $data2[$key]) {
            return [
                'status' => 'updated',
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key],
            ];
        } else {
            return [
                'status' => 'unchanged',
                'value' => $data1[$key]
            ];
        }
    }, $sortedKeys);

    return array_combine($sortedKeys, $diff);
}

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    $dataFile = function ($pathToFile) {
        $fileContent = $pathToFile[0] === '/'
        ? file_get_contents($pathToFile)
        : file_get_contents(__DIR__ . '/../tests/fixtures/' . $pathToFile);

        if ($fileContent === false) {
            throw new \Exception("Failed to read file: $pathToFile");
        }

        return $fileContent;
    };

    $formatFile = fn($pathToFile) => pathinfo($pathToFile, PATHINFO_EXTENSION);

    $data1 = parse($dataFile($pathToFile1), $formatFile($pathToFile1));
    $data2 = parse($dataFile($pathToFile2), $formatFile($pathToFile2));

    $diff = findDiff($data1, $data2);
    return getFormattedDiff($diff, $formatName);
}
