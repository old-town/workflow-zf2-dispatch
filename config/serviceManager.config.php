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
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManager;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManagerFactory;
use OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler;
use OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandlerFactory;

return [
    'service_manager' => [
        'invokables'         => [

        ],
        'factories'          => [
            ModuleOptions::class            => ModuleOptionsFactory::class,
            WorkflowDispatchListener::class => WorkflowDispatchListenerFactory::class,
            Dispatcher::class               => DispatcherFactory::class,
            MetadataReaderManager::class    => MetadataReaderManagerFactory::class,
            RouteHandler::class             => RouteHandlerFactory::class

        ],
        'abstract_factories' => [

        ]
    ],
];


