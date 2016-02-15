# Метаданные на основе анотаций для указания слоев

## Пример описания слоев, через анотации

```php

namespace OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\IntegrationTest;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use OldTown\Workflow\ZF2\Dispatch\Annotation as WFD;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\Dispatcher;


/**
 * Class TestController
 *
 * @package OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\IntegrationTest
 */
class TestController extends AbstractActionController
{

    /**
     * Подготовка данных для workflow
     *
     * @return array
     */
    public function prepareWorkflowDataHandler()
    {
        return [
            'test' => 'testData'
        ];
    }

    /**
     * Условие для запуска workflow
     */
    public function testCondition()
    {
        return true;
    }


    /**
     * @WFD\WorkflowDispatch(enabled=true, activity="initialize")
     * @WFD\PrepareData(type="method", handler="prepareWorkflowDataHandler", enabled=true)
     * @WFD\DispatchConditions(
     *     conditions={
     *         @WFD\Condition(type="method", handler="testCondition"),
     *         @WFD\Condition(type="service", handler="httpMethod", params={"allowedHttpMethods":{"GET"}})
     *     }
     * )
     */
    public function testAction()
    {
        $this->getEvent()->getParam(Dispatcher::WORKFLOW_DISPATCH_EVENT);

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        return $viewModel;
    }
}
```

## Использование анотаций
Необходимо с помощью use, декларировать использование пространства имен OldTown\Workflow\ZF2\Dispatch\Annotation.
```php
use OldTown\Workflow\ZF2\Dispatch\Annotation as WFD;
```

Для удобства использования, указать псевдоним для данного пространства имен. В качестве рекоменации предлагается использовать
псевдоним WFD (WorkFlowDispatch)


## Подключение слоя prepare data 

Для подключения слоя PrepareData используется следующая анотация:
```php
@WFD\PrepareData(type="method", handler="prepareWorkflowDataHandler", enabled=true)
```

### Описание параметров:

Название параметра|Обязательный|Значение по умолчанию|Описание           
------------------|------------|---------------------|---------------------------------------------------------------------
enabled           |Нет         |true                 |Включает/выключает использование слоя подготавливающего данные для wf 
type              |Да          |                     |Определят тип обработчика
handler           |Да          |                     |Указывает имя обработчика

### Типы обработчиков

На данный момент поддерживается только один тип обработчика "method". Данный тип указывает на то, что обработчиком
является метод текущего контроллера.

### Имя обработчика


Тип обработчика|Описание
---------------|---------------------------------------------------------------------------------
method         |Содержит имя метода контроллера, в котором реализованна подготовка данных для wf

### Результаты работы слоя prepare data

Результаты работы слоя prepare data, долж быть обязательно либо массивом, либо объектом который реализует интерфейс Traversable

## Запуск workflow (слой бизнес логики)

Для запуска workflow необходимо использовать следующую анотацию
```php
@WFD\WorkflowDispatch(enabled=true, activity="initialize")
```

### Описание параметров:

Название параметра|Обязательный|Значение по умолчанию|Описание           
------------------|------------|---------------------|---------------------------------------------------------------------
enabled           |Нет         |true                 |Включает/выключает использование wf 
activity          |Да          |                     |Определят действие для wf (doAction или initialize)

### Возможные действия для wf

* initialize - создает новый процесс wf
* doAction   - осуществляет переход из одного состояния в другой, для уже созданного процесса wf





