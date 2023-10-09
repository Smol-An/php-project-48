<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Parsers\parse;
use function Differ\Formatters\getFormattedDiff;

function buildDiff(object $data1, object $data2): array
{
    $data1Array = get_object_vars($data1);
    $data2Array = get_object_vars($data2);

    $keys = array_unique(array_merge(array_keys($data1Array), array_keys($data2Array)));
    $sortedKeys = sort($keys, fn($a, $b) => strcmp($a, $b));

    $diff = array_map(function ($key) use ($data1Array, $data2Array) {
        $value1 = $data1Array[$key] ?? null;
        $value2 = $data2Array[$key] ?? null;

        if (!array_key_exists($key, $data1Array)) {
            return [
                'key' => $key,
                'status' => 'added',
                'value' => $value2
            ];
        } elseif (!array_key_exists($key, $data2Array)) {
            return [
                'key' => $key,
                'status' => 'removed',
                'value' => $value1
            ];
        } elseif (is_object($value1) && is_object($value2)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => buildDiff($value1, $value2)
            ];
        } elseif ($value1 !== $value2) {
            return [
                'key' => $key,
                'status' => 'updated',
                'oldValue' => $value1,
                'newValue' => $value2,
            ];
        } else {
            return [
                'key' => $key,
                'status' => 'unchanged',
                'value' => $value1
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
