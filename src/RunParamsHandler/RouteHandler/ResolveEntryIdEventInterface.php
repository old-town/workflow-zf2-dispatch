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

    /**
     * Тип запуска workflow (doAction или initialize)
     *
     * @return string
     */
    public function getRunType();

    /**
     * Устанавливает тип запуска workflow (doAction или initialize)
     *
     * @param string $runType
     *
     * @return $this
     */
    public function setRunType($runType);

    /**
     * Имя менеджера workflow
     *
     * @return string
     */
    public function getManagerName();

    /**
     * Устанавливает имя менеджера workflow
     *
     * @param string $managerName
     *
     * @return $this
     */
    public function setManagerName($managerName);

    /**
     * Имя запускаемого действия wf
     *
     * @return string
     */
    public function getActionName();

    /**
     * Устанавливает имя запускаемого действия wf
     *
     * @param string $actionName
     *
     * @return $this
     */
    public function setActionName($actionName);

    /**
     * Возвращает имя workflow
     *
     * @return string
     */
    public function getWorkflowName();

    /**
     * Устанавливает имя workflow
     *
     * @param string $workflowName
     *
     * @return $this
     */
    public function setWorkflowName($workflowName);
}
