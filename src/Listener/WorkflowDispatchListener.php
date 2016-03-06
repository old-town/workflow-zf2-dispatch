<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractController;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\DispatcherInterface;


/**
 * Class InjectTypeResolver
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Listener
 */
class WorkflowDispatchListener extends AbstractListenerAggregate
{
    /**
     * @var DispatcherInterface
     */
    protected $workflowDispatcher;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        call_user_func_array([$this, 'init'], $options);
    }

    /**
     * @param DispatcherInterface $workflowDispatcher
     */
    protected function init(DispatcherInterface $workflowDispatcher)
    {
        $this->setWorkflowDispatcher($workflowDispatcher);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->getSharedManager()->attach(AbstractController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatchWorkflow'], 80);
    }

    /**
     * @param MvcEvent $e
     */
    public function onDispatchWorkflow(MvcEvent $e)
    {
        $event = $this->getWorkflowDispatcher()->workflowDispatchEventFactory();
        $event->setMvcEvent($e);
        $this->getWorkflowDispatcher()->dispatch($event);
    }


    /**
     * @return DispatcherInterface
     */
    public function getWorkflowDispatcher()
    {
        return $this->workflowDispatcher;
    }

    /**
     * @param DispatcherInterface $workflowDispatcher
     *
     * @return $this
     */
    public function setWorkflowDispatcher(DispatcherInterface $workflowDispatcher)
    {
        $this->workflowDispatcher = $workflowDispatcher;

        return $this;
    }
}
