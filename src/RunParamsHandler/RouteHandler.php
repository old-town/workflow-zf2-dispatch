<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\RunParamsHandler;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\Dispatcher;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\WorkflowDispatchEventInterface;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\RunWorkflowParam;
use OldTown\Workflow\ZF2\Dispatch\Metadata\ReaderInterface;
use Zend\Mvc\Controller\AbstractController;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams\MetadataInterface;

/**
 * Class RouteHandler
 *
 * @package OldTown\Workflow\ZF2\Dispatch\RunParamsHandler
 */
class RouteHandler extends AbstractListenerAggregate
{
    /**
     * Адаптер для чтения метаданных
     *
     * @var ReaderInterface
     */
    protected $metadataReader;

    /**
     * RouteHandler constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $initOptions = [
            array_key_exists('metadataReader', $options) ? $options['metadataReader'] : null
        ];
        call_user_func_array([$this, 'init'], $initOptions);
    }

    /**
     * @param ReaderInterface $metadataReader
     */
    protected function init(ReaderInterface $metadataReader)
    {
        $this->setMetadataReader($metadataReader);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach(Dispatcher::class, WorkflowDispatchEventInterface::METADATA_WORKFLOW_TO_RUN_EVENT, [$this, 'onMetadataWorkflowToRun'], 80);
    }

    /**
     * Получение метаданных для запуска workflow
     *
     * @param WorkflowDispatchEventInterface $e
     *
     * @return RunWorkflowParam
     *
     * @throws Exception\InvalidMetadataException
     */
    public function onMetadataWorkflowToRun(WorkflowDispatchEventInterface $e)
    {
        $mvcEvent = $e->getMvcEvent();
        $controller = $mvcEvent->getTarget();
        if (!$controller instanceof AbstractController) {
            return null;
        }

        $routeMatch = $mvcEvent->getRouteMatch();
        if (!$routeMatch) {
            return null;
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $actionMethod = AbstractController::getMethodFromAction($action);

        if (!method_exists($controller, $actionMethod)) {
            return null;
        }

        $controllerClassName = get_class($controller);

        $metadata = $this->getMetadataReader()->loadMetadataForAction($controllerClassName, $actionMethod);

        if (!$metadata instanceof MetadataInterface) {
            $errMsg = sprintf('Metadata not implement %s', MetadataInterface::class);
            throw new Exception\InvalidMetadataException($errMsg);
        }

        $workflowManagerNameParam = $metadata->getWorkflowManagerNameRouterParam();
        $workflowManagerName = $routeMatch->getParam($workflowManagerNameParam, null);

        $workflowActionNameParam = $metadata->getWorkflowActionNameRouterParam();
        $workflowActionName = $routeMatch->getParam($workflowActionNameParam, null);

        $workflowNameParam = $metadata->getWorkflowNameRouterParam();
        $workflowName = $routeMatch->getParam($workflowNameParam, null);

        $entryIdParam = $metadata->getEntryIdRouterParam();
        $entryId = $routeMatch->getParam($entryIdParam, null);


        $runType = $e->getMetadata()->getWorkflowRunType();
        if (null !== $workflowManagerName && null !== $workflowActionName
            && ((RunWorkflowParam::WORKFLOW_RUN_INITIALIZE === $runType && null !== $workflowName && null === $entryId)
                ||(RunWorkflowParam::WORKFLOW_RUN_TYPE_DO_ACTION === $runType && null !== $entryId && null === $workflowName))
        ) {
            $runWorkflowParam = new RunWorkflowParam();
            $runWorkflowParam->setRunType($runType);
            $runWorkflowParam->setManagerName($workflowManagerName);
            $runWorkflowParam->setActionName($workflowActionName);
            $runWorkflowParam->setEntryId($entryId);
            $runWorkflowParam->setWorkflowName($workflowName);

            return $runWorkflowParam;
        }

        return null;
    }

    /**
     * @return ReaderInterface
     */
    public function getMetadataReader()
    {
        return $this->metadataReader;
    }

    /**
     * @param ReaderInterface $metadataReader
     *
     * @return $this
     */
    public function setMetadataReader(ReaderInterface $metadataReader)
    {
        $this->metadataReader = $metadataReader;

        return $this;
    }
}
