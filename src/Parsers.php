<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($pathToFile1, $pathToFile2)
{
    $contents = function ($pathToFile) {
        return $pathToFile[0] === '/'
        ? file_get_contents($pathToFile)
        : file_get_contents(__DIR__ . '/../tests/fixtures/' . $pathToFile);
    };

    $data = function ($pathToFile) use ($contents) {
        if (pathinfo($pathToFile, PATHINFO_EXTENSION) === 'json') {
            return json_decode($contents($pathToFile), true);
        } elseif (pathinfo($pathToFile, PATHINFO_EXTENSION) === 'yml') {
            return Yaml::parse($contents($pathToFile));
        }
    };

    $data1 = $data($pathToFile1);
    $data2 = $data($pathToFile2);

    return [$data1, $data2];
}
