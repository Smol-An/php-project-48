<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStylishDiff;
use function Differ\Formatters\Plain\getPlainDiff;
use function Differ\Formatters\Json\getJsonDiff;

function getFormattedDiff($diff, $formatName)
{
    if ($formatName === 'plain') {
        return getPlainDiff($diff);
    } elseif ($formatName === 'json') {
        return getJsonDiff($diff);
    } else {
        return "{\n" . getStylishDiff($diff) . "\n}\n";
    }
}
