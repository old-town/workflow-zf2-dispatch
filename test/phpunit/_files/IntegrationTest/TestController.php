<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\IntegrationTest;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use OldTown\Workflow\ZF2\Dispatch\Annotation as WFD;


/**
 * Class TestController
 *
 * @package OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\IntegrationTest
 */
class TestController extends AbstractActionController
{

    /**
     * Подготовка данных для workflow
     */
    public function prepareWorkflowDataHandler()
    {

    }

    /**
     * @WFD\WorkflowDispatch(enabled=true, activity="initialize")
     * @WFD\PrepareData(type="method", handler="prepareWorkflowDataHandler", enabled=true)
     * @WFD\DispatchConditions(conditions={@WFD\Condition(type="service", handler="httpMethod", params={"POST"})})
     */
    public function testAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        return $viewModel;
    }
}
