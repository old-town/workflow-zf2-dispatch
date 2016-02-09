<?php

use \OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\TestPaths;

return [
    'modules' => [
        'OldTown\\Workflow\\ZF2',
        'OldTown\\Workflow\\ZF2\\Dispatch'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'OldTown\\Workflow\\ZF2\\Dispatch' => TestPaths::getPathToModule()
        ],
        'config_glob_paths' => []
    ]
];