<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStylishDiff;
use function Differ\Formatters\Plain\getPlainDiff;
use function Differ\Formatters\Json\getJsonDiff;

function getFormattedDiff(array $diff, string $formatName): string
{
    switch ($formatName) {
        case 'plain':
            return  getPlainDiff($diff);
        case 'json':
            return getJsonDiff($diff);
        case 'stylish':
            return getStylishDiff($diff);
        default:
            throw new \Exception("Unknown format: $formatName");
    }
}
