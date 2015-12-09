<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch;

return [
    'router' => [
        'routes' => [
            'workflow' => [
                'child_routes' => [
                    'dispatch' => [
                        'type'         => 'Literal',
                        'options'      => [
                            'route' => 'dispatch/'
                        ],
                    ]
                ],
            ]
        ]
    ]
];