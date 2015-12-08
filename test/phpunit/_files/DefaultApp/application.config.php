<?php

use \OldTown\Workflow\ZF2\PreDispatch\PhpUnit\TestData\TestPaths;

return [
    'modules' => [
        'OldTown\\Workflow\\ZF2\\PreDispatch'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'OldTown\\Workflow\\ZF2\\PreDispatch' => TestPaths::getPathToModule()
        ],
        'config_glob_paths' => []
    ]
];