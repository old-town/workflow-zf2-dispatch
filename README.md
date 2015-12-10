# workflow-zf2-dispatch

[![Build Status](https://secure.travis-ci.org/old-town/workflow-zf2-dispatch.svg?branch=dev)](https://secure.travis-ci.org/old-town/workflow-zf2-dispatch)
[![Coverage Status](https://coveralls.io/repos/old-town/workflow-zf2-dispatch/badge.svg?branch=dev&service=github)](https://coveralls.io/github/old-town/workflow-zf2-dispatch?branch=dev)

# Функционал модуля

Модуль позволяет отработать workflow перед action у контроллера. Для использования функционала модуля необходимо

## Роутинг

Для того что бы workflow отработало перед запуском controller/action нужно добавить роутеры. Добавляемый роутер
должен быть вложенным в роуетер workflow/dispatch. Роутер должен определять следующие параметры:

* workflowManagerName - имя менеджера workflow
* 

```php

<?php

use Test\Controller\TestController;

return [

    'router'       => [
        'routes' => [
            'workflow' => [
                'child_routes' => [
                    'dispatch' => [
                        'child_routes' => [
                            'test' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route' => 'wfManager/:workflowManagerName/wfAction/:workflowActionName/[wfName/:workflowName/][wfEntryId:entryId/]action/:action',
                                    'defaults'    => [
                                        'controller' => TestController::class
                                    ],
                                ],

                            ]
                        ]
                    ]
                ]
            ]
        ],
    ],

    'controllers'  => [
        'invokables' => [
            TestController::class => TestController::class,
        ],
    ]
];

```




Модуль реализует обработчки события dispatch всех контроллеров приложения. В обработчки