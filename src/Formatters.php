<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStylishDiff;
use function Differ\Formatters\Plain\getPlainDiff;
use function Differ\Formatters\Json\getJsonDiff;

function getFormattedDiff(array $diff, string $formatName): string
{
    switch ($formatName) {
        case 'stylish':
            return getStylishDiff($diff);
        case 'plain':
            return getPlainDiff($diff);
        case 'json':
            return getJsonDiff($diff);
        default:
            throw new \Exception("Unknown format: '$formatName'");
    }
}
