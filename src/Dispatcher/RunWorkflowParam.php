<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

/**
 * Class RunWorkflowParam
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
class RunWorkflowParam implements RunWorkflowParamInterface
{
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
     * id запускаемого процесса
     *
     * @var integer
     */
    protected $entryId;

    /**
     * Разрешенные типы запуска workflow
     *
     * @var array
     */
    protected $allowWorkflowRunType = [
        self::WORKFLOW_RUN_TYPE_DO_ACTION => self::WORKFLOW_RUN_TYPE_DO_ACTION,
        self::WORKFLOW_RUN_INITIALIZE => self::WORKFLOW_RUN_INITIALIZE,
    ];

    /**
     * Возвращает тип запуска workflow (doAction или initialize)
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
     * Имя запускаемого действия
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Устанавливает имя запускаемого действия
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
     * Имя workflow
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

    /**
     * id запускаемого процесса
     *
     * @return int
     */
    public function getEntryId()
    {
        return $this->entryId;
    }

    /**
     * Устанавливает id запускаемого процесса
     *
     * @param int $entryId
     *
     * @return $this
     */
    public function setEntryId($entryId)
    {
        $this->entryId = $entryId;

        return $this;
    }

    /**
     * Возвращает имя менеджера workflow
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
     * Проверка валидности параметров
     *
     * @throws Exception\InvalidArgumentException
     */
    public function valid()
    {
        if (null === $this->getManagerName()) {
            $errMsg = 'No workflow manager';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        if (null === $this->getActionName()) {
            $errMsg = 'Not Specified action for workflow';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $workflowActivity = $this->getRunType();
        if (!array_key_exists($workflowActivity, $this->allowWorkflowRunType)) {
            $errMsg = sprintf('Invalid activity %s', $workflowActivity);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        if (static::WORKFLOW_RUN_INITIALIZE === $workflowActivity && null === $this->getWorkflowName()) {
            $errMsg = 'Not specified name workflow';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        if (static::WORKFLOW_RUN_TYPE_DO_ACTION === $workflowActivity && null === $this->getEntryId()) {
            $errMsg = 'Not specified entryId';
            throw new Exception\InvalidArgumentException($errMsg);
        }
    }
}
