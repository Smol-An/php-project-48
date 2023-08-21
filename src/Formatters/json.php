<?php

namespace Differ\Formatters\Json;

function getJsonDiff($diff)
{
    $output = [];

    foreach ($diff as $key => $node) {
        switch ($node['status']) {
            case 'added':
                $output[] = ['key' => $key, 'status' => 'added', 'value' => $node['value']];
                break;
            case 'removed':
                $output[] = ['key' => $key, 'status' => 'removed'];
                break;
            case 'updated':
                $output[] = ['key' => $key, 'status' => 'updated',
                    'oldValue' => $node['oldValue'], 'newValue' => $node['newValue']];
                break;
            case 'nested':
                $output[] = ['key' => $key, 'status' => 'nested', 'children' => getJsonDiff($node['children'])];
                break;
        }
    }

    return $output;
}
