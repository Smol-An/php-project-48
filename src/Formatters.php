<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\getStylishDiff;
use function Differ\Formatters\Plain\getPlainDiff;
use function Differ\Formatters\Json\getJsonDiff;

function getFormattedDiff($diff, $formatName)
{
    if ($formatName === 'plain') {
        $plainDiff = getPlainDiff($diff);
        return implode("\n", $plainDiff) . "\n";
    } elseif ($formatName === 'json') {
        $jsonDiff = getJsonDiff($diff);
        return json_encode($jsonDiff, JSON_PRETTY_PRINT) . "\n";
    } else {
        return "{\n" . getStylishDiff($diff) . "\n}\n";
    }
}
