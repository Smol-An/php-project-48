<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileData, string $fileExtension): array
{
    switch ($fileExtension) {
        case 'json':
            return json_decode($fileData, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($fileData);
        default:
            throw new \Exception("Unknown extension: '$fileExtension'");
    }
}
