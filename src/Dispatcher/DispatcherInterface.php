<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use Zend\EventManager\EventsCapableInterface;

/**
 * Interface DispatcherInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
interface DispatcherInterface extends EventsCapableInterface
{
    /**
     * Диспетчирезация работы с workflow
     *
     * @param WorkflowDispatchEventInterface $event
     *
     * @return
     */
    public function dispatch(WorkflowDispatchEventInterface $event);

    /**
     * Фабрика для создания событий
     *
     * @return WorkflowDispatchEventInterface
     */
    public function workflowDispatchEventFactory();
}
