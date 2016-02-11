<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch;

use OldTown\Workflow\ZF2\Dispatch\Validator\HttpMethod;

return [
    'validators' => [
        'invokables' => [
            HttpMethod::class => HttpMethod::class
        ],
        'aliases' => [
            'httpMethod' => HttpMethod::class
        ]
    ]
];