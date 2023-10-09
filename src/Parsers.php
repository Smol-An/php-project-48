<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileData, string $fileExtension): object
{
    switch ($fileExtension) {
        case 'json':
            return json_decode($fileData);
        case 'yml':
        case 'yaml':
            return Yaml::parse($fileData, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Unknown extension: '$fileExtension'");
    }
}
