<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Parsers\parse;
use function Differ\Formatters\getFormattedDiff;

function buildDiff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = sort($keys, fn($a, $b) => strcmp($a, $b));

    $diff = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return [
                'key' => $key,
                'status' => 'added',
                'value' => $data2[$key]
            ];
        } elseif (!array_key_exists($key, $data2)) {
            return [
                'key' => $key,
                'status' => 'removed',
                'value' => $data1[$key]
            ];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => buildDiff($data1[$key], $data2[$key])
            ];
        } elseif ($data1[$key] !== $data2[$key]) {
            return [
                'key' => $key,
                'status' => 'updated',
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key],
            ];
        } else {
            return [
                'key' => $key,
                'status' => 'unchanged',
                'value' => $data1[$key]
            ];
        }
    }, $sortedKeys);

    return $diff;
}

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    $data1 = parse(
        getFileData($pathToFile1),
        pathinfo($pathToFile1, PATHINFO_EXTENSION)
    );
    $data2 = parse(
        getFileData($pathToFile2),
        pathinfo($pathToFile2, PATHINFO_EXTENSION)
    );

    $diff = buildDiff($data1, $data2);
    return getFormattedDiff($diff, $formatName);
}

function getFileData(string $pathToFile): string
{
    if (!file_exists($pathToFile)) {
        return throw new \Exception("File not found: '$pathToFile'");
    }

    return file_get_contents($pathToFile);
}
