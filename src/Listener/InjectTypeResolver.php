<?php
/**
 * @link https://github.com/old-town/workflow-zf2-preDispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\PreDispatch\Listener;


use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerInterface;


/**
 * Class InjectTypeResolver
 *
 * @package OldTown\Workflow\ZF2\PreDispatch\Listener
 */
class InjectTypeResolver extends AbstractListenerAggregate
{
    use EventManagerAwareTrait;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        call_user_func_array([$this, 'init'], $options);
    }

    /**
     *
     */
    protected function init()
    {

    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        //$this->listeners[] = $events->attach(WorkflowManagerEvent::EVENT_CREATE, [$this, 'createWorkflowManager'], -80);
    }

}
