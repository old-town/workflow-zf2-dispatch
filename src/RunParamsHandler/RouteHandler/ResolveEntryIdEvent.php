<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler;

use OldTown\Workflow\ZF2\Dispatch\Dispatcher\WorkflowDispatchEventInterface;
use Zend\EventManager\Event;

/**
 * Class ResolveEntryIdEvent
 *
 * @package OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler
 */
class ResolveEntryIdEvent extends Event implements ResolveEntryIdEventInterface
{
    /**
     *
     * @var WorkflowDispatchEventInterface
     */
    protected $workflowDispatchEvent;

    /**
     * Тип запуска workflow (doAction или initialize)
     *
     * @var string
     */
    protected $runType;

    /**
     * Имя менеджера workflow
     *
     * @var string
     */
    protected $managerName;

    /**
     * Имя запускаемого действия
     *
     * @var string
     */
    protected $actionName;

    /**
     * Имя workflow
     *
     * @var string
     */
    protected $workflowName;

    /**
     * @return WorkflowDispatchEventInterface
     */
    public function getWorkflowDispatchEvent()
    {
        return $this->workflowDispatchEvent;
    }

    /**
     * @param WorkflowDispatchEventInterface $workflowDispatchEvent
     *
     * @return $this
     */
    public function setWorkflowDispatchEvent(WorkflowDispatchEventInterface $workflowDispatchEvent)
    {
        $this->workflowDispatchEvent = $workflowDispatchEvent;

        return $this;
    }

    /**
     * Тип запуска workflow (doAction или initialize)
     *
     * @return string
     */
    public function getRunType()
    {
        return $this->runType;
    }

    /**
     * Устанавливает тип запуска workflow (doAction или initialize)
     *
     * @param string $runType
     *
     * @return $this
     */
    public function setRunType($runType)
    {
        $this->runType = $runType;

        return $this;
    }

    /**
     * Имя менеджера workflow
     *
     * @return string
     */
    public function getManagerName()
    {
        return $this->managerName;
    }

    /**
     * Устанавливает имя менеджера workflow
     *
     * @param string $managerName
     *
     * @return $this
     */
    public function setManagerName($managerName)
    {
        $this->managerName = $managerName;

        return $this;
    }

    /**
     * Имя запускаемого действия wf
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Устанавливает имя запускаемого действия wf
     *
     * @param string $actionName
     *
     * @return $this
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;

        return $this;
    }

    /**
     * Возвращает имя workflow
     *
     * @return string
     */
    public function getWorkflowName()
    {
        return $this->workflowName;
    }

    /**
     * Устанавливает имя workflow
     *
     * @param string $workflowName
     *
     * @return $this
     */
    public function setWorkflowName($workflowName)
    {
        $this->workflowName = $workflowName;

        return $this;
    }
}
