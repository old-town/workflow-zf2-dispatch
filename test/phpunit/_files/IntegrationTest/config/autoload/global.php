<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */

use OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\IntegrationTest\TestController;

use OldTown\Workflow\Loader\ArrayWorkflowFactory;
use OldTown\Workflow\Util\DefaultVariableResolver;
use OldTown\Workflow\Basic\BasicWorkflow;
use OldTown\Workflow\Spi\Memory\MemoryWorkflowStore;
use Zend\Log\LoggerAbstractServiceFactory;

return [
    'log' => [
        'testLog' => [
            'writers' => [
                'syslog' => [
                    'name' => 'stream',
                    'options' => [
                        'stream' => '/tmp/workflow-zf2-dispatch.log',
                    ]
                ]
            ]
        ]
    ],
    'workflow_zf2_dispatch' => [
        //'logName' => 'testLog'
    ],
    'service_manager' => [
        'abstract_factories' => [
            LoggerAbstractServiceFactory::class => LoggerAbstractServiceFactory::class
        ]
    ],
    'router' => [
        'routes' => [
            'test' => [
                'type' => 'Literal',
                'options' => [
                    'route' => 'test',
                    'defaults'=> [
                        'controller' => TestController::class,
                        'action' => 'test',

                        'workflowManagerNameRouteParam' => 'testWorkflowManager',
                        'workflowActionNameRouteParam' => 'initAction',
                        'workflowNameRouteParam' => 'test',

                    ],
                ],
            ]
        ]
    ],
    'workflow_zf2'    => [
        'configurations' => [
            'default' => [
                'persistence' => [
                    'name' => MemoryWorkflowStore::class,
                ],
                'factory' => [
                    'name' => ArrayWorkflowFactory::class,
                    'options' => [
                        'reload' => true,
                        'workflows' => [
                            'test' => [
                                'location' => __DIR__ . '/test_workflow.xml'
                            ]
                        ]
                    ]
                ],
                'resolver' => DefaultVariableResolver::class,
            ]
        ],

        'managers' => [
            'testWorkflowManager' => [
                'configuration' => 'default',
                'name' => BasicWorkflow::class
            ]
        ]
    ],


    'controllers' => [
        'invokables' => [
            TestController::class => TestController::class
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'old-town/test/test' => __DIR__ . '/../../view/test.phtml'
        ]
    ]
];