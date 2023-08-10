<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($pathToFile1, $pathToFile2)
{
    $contents1 = $pathToFile1[0] === '/'
                ? file_get_contents($pathToFile1)
                : file_get_contents(__DIR__ . '/' . $pathToFile1);
    $contents2 = $pathToFile2[0] === '/'
                ? file_get_contents($pathToFile2)
                : file_get_contents(__DIR__ . '/' . $pathToFile2);

    if (
        (pathinfo($pathToFile1, PATHINFO_EXTENSION) === 'json')
        && (pathinfo($pathToFile2, PATHINFO_EXTENSION) === 'json')
    ) {
        $data1 = json_decode($contents1, true);
        $data2 = json_decode($contents2, true);
    } elseif (
        (pathinfo($pathToFile1, PATHINFO_EXTENSION) === 'yml')
        && (pathinfo($pathToFile2, PATHINFO_EXTENSION) === 'yml')
    ) {
        $data1 = Yaml::parse($contents1);
        $data2 = Yaml::parse($contents2);
    }

    return [$data1, $data2];
}
