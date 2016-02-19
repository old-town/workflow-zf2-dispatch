<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler;

use OldTown\Workflow\ZF2\Dispatch\Dispatcher\WorkflowDispatchEventInterface;
use Zend\EventManager\EventInterface;

/**
 * Interface ResolveEntryIdEventInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler
 */
interface ResolveEntryIdEventInterface extends EventInterface
{
    /**
     * @var string
     */
    const RESOLVE_ENTRY_ID_EVENT = 'workflow.dispatch.resolveEntryId';

    /**
     * @return WorkflowDispatchEventInterface
     */
    public function getWorkflowDispatchEvent();

    /**
     * @param WorkflowDispatchEventInterface $workflowDispatchEvent
     *
     * @return $this
     */
    public function setWorkflowDispatchEvent(WorkflowDispatchEventInterface $workflowDispatchEvent);
}
