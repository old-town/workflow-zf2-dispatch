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
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\Dispatcher;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\DispatcherFactory;

return [
    'service_manager' => [
        'invokables'         => [

        ],
        'factories'          => [
            ModuleOptions::class            => ModuleOptionsFactory::class,
            WorkflowDispatchListener::class => WorkflowDispatchListenerFactory::class,
            Dispatcher::class               => DispatcherFactory::class

        ],
        'abstract_factories' => [

        ]
    ],
];


