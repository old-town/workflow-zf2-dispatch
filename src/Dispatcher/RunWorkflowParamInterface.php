<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

/**
 * Interface RunWorkflowParamInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
interface RunWorkflowParamInterface
{
    /**
     * @var string
     */
    const WORKFLOW_RUN_TYPE_DO_ACTION = 'doAction';

    /**
     * @var string
     */
    const WORKFLOW_RUN_INITIALIZE = 'initialize';

    /**
     * Возвращает тип запуска workflow (doAction или initialize)
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
     * Имя запускаемого действия
     *
     * @return string
     */
    public function getActionName();

    /**
     * Устанавливает имя запускаемого действия
     *
     * @param string $actionName
     *
     * @return $this
     */
    public function setActionName($actionName);

    /**
     * Имя workflow
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

    /**
     * id запускаемого процесса
     *
     * @return int
     */
    public function getEntryId();

    /**
     * Устанавливает id запускаемого процесса
     *
     * @param int $entryId
     *
     * @return $this
     */
    public function setEntryId($entryId);


    /**
     * Возвращает имя менеджера workflow
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
     * Проверка валидности параметров
     *
     */
    public function valid();
}
