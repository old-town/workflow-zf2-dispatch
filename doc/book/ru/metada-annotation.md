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

