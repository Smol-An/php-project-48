<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getDiff as getStylishDiff;
use function Differ\Formatters\Plain\getDiff as getPlainDiff;
use function Differ\Formatters\Json\getDiff as getJsonDiff;

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
