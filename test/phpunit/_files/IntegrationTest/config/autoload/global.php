<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */

use OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\IntegrationTest\TestController;

return [
    'router' => [
        'routes' => [
            'test' => [
                'type' => 'Literal',
                'options' => [
                    'route' => 'test',
                    'defaults'=> [
                        'controller' => TestController::class,
                        'action' => 'test'
                    ],
                ],
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