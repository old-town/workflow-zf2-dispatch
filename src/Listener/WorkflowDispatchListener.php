<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Listener;


use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\Router\RouteMatch;
use OldTown\Workflow\ZF2\Service\Workflow as WorkflowService;
use OldTown\Workflow\Spi\WorkflowEntryInterface;


/**
 * Class InjectTypeResolver
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Listener
 */
class WorkflowDispatchListener extends AbstractListenerAggregate
{
    use EventManagerAwareTrait;

    /**
     * @var string
     */
    const TRANSITION_RESULT = 'transitionResult';

    /**
     * @var WorkflowService
     */
    protected $workflowService;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        call_user_func_array([$this, 'init'], $options);
    }

    /**
     * @param WorkflowService $workflowService
     */
    protected function init(WorkflowService $workflowService)
    {
        $this->setWorkflowService($workflowService);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach(AbstractController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatchWorkflow'], 80);
    }

    /**
     * @param MvcEvent $e
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\WorkflowDispatchException
     */
    public function onDispatchWorkflow(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        if (!$routeMatch instanceof RouteMatch || 0 !== strpos($routeMatch->getMatchedRouteName(), 'workflow/dispatch/')) {
            return;
        }

        $workflowManagerName = $routeMatch->getParam('workflowManagerName', null);
        if (null === $workflowManagerName) {
            $errMsg = 'Param managerName not found';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $workflowActionName = $routeMatch->getParam('workflowActionName', null);
        if (null === $workflowActionName) {
            $errMsg = 'Param actionName not found';
            throw new Exception\InvalidArgumentException($errMsg);
        }


        $workflowName = $routeMatch->getParam('workflowName', null);
        $entryId = $routeMatch->getParam('entryId', null);

        if (null === $workflowName && null === $entryId) {
            $errMsg = 'workflowName and entryId not found';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        try {
            if (null !== $entryId) {
                if (null !== $workflowName) {
                    $this->validateWorkflowParams($workflowManagerName, $workflowName, $entryId);
                }
                $result = $this->doAction($workflowManagerName, $workflowActionName, $entryId);
            } else {
                $result = $this->initialize($workflowManagerName, $workflowActionName, $workflowName);
            }
        } catch (\Exception $e) {
            throw new Exception\WorkflowDispatchException($e->getMessage(), $e->getCode(), $e);
        }

        $e->setParam(static::TRANSITION_RESULT, $result);
    }


    /**
     * @param          $workflowManagerName
     * @param          $workflowActionName
     * @param          $entryId
     *
     * @return WorkflowService\TransitionResultInterface
     *
     * @throws \OldTown\Workflow\ZF2\Service\Exception\DoActionException
     */
    public function doAction($workflowManagerName, $workflowActionName, $entryId)
    {
        return $this->getWorkflowService()->doAction($workflowManagerName, $entryId, $workflowActionName);
    }


    /**
     * @param          $workflowManagerName
     * @param          $workflowActionName
     * @param          $workflowName
     *
     * @return WorkflowService\TransitionResultInterface
     * @throws \OldTown\Workflow\ZF2\Service\Exception\InvalidInitializeWorkflowEntryException
     */
    public function initialize($workflowManagerName, $workflowActionName, $workflowName)
    {
        return $this->getWorkflowService()->initialize($workflowManagerName, $workflowName, $workflowActionName);
    }

    /**
     * @param $workflowManagerName
     * @param $workflowName
     * @param $entryId
     *
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\RuntimeException
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\InvalidWorkflowNameException
     */
    public function validateWorkflowParams($workflowManagerName, $workflowName, $entryId)
    {
        try {
            $entry = $this->getWorkflowService()
                ->getWorkflowManager($workflowManagerName)
                ->getConfiguration()
                ->getWorkflowStore()
                ->findEntry($entryId);

            if (!$entry instanceof WorkflowEntryInterface) {
                $errMsg = 'Invalid workflow entry';
                throw new Exception\RuntimeException($errMsg);
            }
        } catch (\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        if ($workflowName !== $entry->getWorkflowName()) {
            $errMsg = sprintf('Invalid workflow name %s. Expected: %s', $workflowName, $entry->getWorkflowName());
            throw new Exception\InvalidWorkflowNameException($errMsg);
        }
    }

    /**
     * @return WorkflowService
     */
    public function getWorkflowService()
    {
        return $this->workflowService;
    }

    /**
     * @param WorkflowService $workflowService
     *
     * @return $this
     */
    public function setWorkflowService(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;

        return $this;
    }


}
