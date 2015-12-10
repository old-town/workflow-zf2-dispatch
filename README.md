# workflow-zf2-dispatch

[![Build Status](https://secure.travis-ci.org/old-town/workflow-zf2-dispatch.svg?branch=dev)](https://secure.travis-ci.org/old-town/workflow-zf2-dispatch)
[![Coverage Status](https://coveralls.io/repos/old-town/workflow-zf2-dispatch/badge.svg?branch=dev&service=github)](https://coveralls.io/github/old-town/workflow-zf2-dispatch?branch=dev)

# Функционал модуля

Модуль позволяет отработать workflow перед запусом action  контроллера. Для использования функционала модуля необходимо
корректно описать роутинг. Общая схема работы следующая:

* Добавялется роутинг
* Перед запуском любого контроллера унаследованного от \Zend\Mvc\Controller\AbstractController проверяется нужно ли запустить workflow
  (критерий - имя сработавшего роутера начинается с workflow/dispatch)
* Из параметров роуетра извлекаются необходимы для запуска workflow данные
* Запускается workflow
* Запускается стандартный механизм диспетчирезации контроллера (вызов action)


## Роутинг

Для того что бы workflow отработало перед запуском controller/action нужно добавить роутеры. Добавляемый роутер
должен быть вложенным в роуетер workflow/dispatch. Роутер должен определять следующие параметры:

Имя параметра      |Описание                        |Обязательный|Коментарий
-------------------|--------------------------------|------------|----------
workflowManagerName|имя менеджера workflow          |да          |
workflowActionName |имя действия workflow           |да          |
workflowName       |имя workflow                    |нет         |Если не указан, то обязательно должен быть  указан entryId
entryId            |id запущенного процесса workflow|нет         |Если не указан, то обязательно должен быть  указан workflowName
controller         |Имя контроллера                 |да          |Стандартный механизм для zf
action             |Имя action                      |да          |Стандартный механизм для zf


В зависиомости от набора параметров определяется какое действие должно быть выполненно workflow:

workflowName|entryId  |Описание
------------|---------|--------
указан      |не указан|Инициируется новый процесс workflow
не указан   |указан   |Вызывается переход для для уже запущенного процесса workflow с id=entryId
указан      |указан   |Проверятеся что entryId запущен для workflow  с именем workflowName. Вызывается переход для запущенного процесса workflow с id=entryId
не указан   |не указан|Бросается исключение.Недопустимая ситуация


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

## Описание реализации 

В обработчике [WorkflowDispatchListener](src/Listener/WorkflowDispatchListener.php) происходит подписка на все
события dispatch бросаемых контроллерами(Контроллер должен быть унаследован от \Zend\Mvc\Controller\AbstractController).

В случае если роутер является потомком роутера workflow/dispatch, то происходит запуск workflow. Результаты работы
описываются \OldTown\Workflow\ZF2\Service\Workflow\TransitionResult. Эти результаты сохраняются в виде параметра в 
объекте MvcEvent.

Далее происходит запуск controller/action где получить результаты работы workflow можно следующим образом:

```php
<?php

namespace DispatchWorkflow\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use OldTown\Workflow\ZF2\Service\Workflow\TransitionResult;


/**
 * Class TestController
 *
 * @package DispatchWorkflow\Controller
 */
class TestController extends AbstractActionController
{
    /**
     * @return array
     */
    public function viewResultInitAction()
    {
        /** @var  TransitionResult $transitionResult */
        $transitionResult = $this->getEvent()->getParam('transitionResult');

        return [
            'transitionResult' => $transitionResult
        ];
    }
}

```