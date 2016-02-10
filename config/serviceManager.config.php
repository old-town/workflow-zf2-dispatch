<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch;

use OldTown\Workflow\ZF2\Dispatch\Listener\WorkflowDispatchListenerFactory;
use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptionsFactory;
use OldTown\Workflow\ZF2\Dispatch\Listener\WorkflowDispatchListener;


return [
    'service_manager'           => [
        'invokables' => [

        ],
        'factories'          => [
            ModuleOptions::class => ModuleOptionsFactory::class,
            WorkflowDispatchListener::class => WorkflowDispatchListenerFactory::class
        ],
        'abstract_factories' => [

        ]
    ],
];


