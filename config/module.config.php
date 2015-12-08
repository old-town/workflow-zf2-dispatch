<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-preDispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\PreDispatch;

use OldTown\Workflow\ZF2\PreDispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\PreDispatch\Options\ModuleOptionsFactory;;

return [
    'service_manager'           => [
        'invokables' => [

        ],
        'factories'          => [
            ModuleOptions::class => ModuleOptionsFactory::class,
        ],
        'abstract_factories' => [

        ]
    ],
    'workflow_zf2_preDispatch'         => [
    ]
];