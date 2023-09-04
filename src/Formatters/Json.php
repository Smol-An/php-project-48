<?php

namespace Differ\Formatters\Json;

function getJsonDiff(array $diff): string
{
    return json_encode($diff, JSON_PRETTY_PRINT) . "\n";
}
