<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $dataFile, string $formatFile): array
{
    switch ($formatFile) {
        case 'json':
            return json_decode($dataFile, true);
        case 'yml':
            return Yaml::parse($dataFile);
        default:
            throw new \Exception("Unknown format: $format");
    }
}
