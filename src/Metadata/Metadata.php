<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata;

use SplObjectStorage;

/**
 * Interface MetadataInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata
 */
class Metadata implements MetadataInterface
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
     * Флаг определят нужно ли запускать workflow
     *
     * @var boolean
     */
    protected $workflowDispatch = false;

    /**
     * Определяет что мы хотим сделать с workflow. Запустить новый процесс (initialize), или инициировать переход в уже
     * запущенном процессе (doAction)
     *
     * @var string
     */
    protected $workflowRunType;

    /**
     * Флаг определят нужно вызвать метод или сервис, с целью подготовки данных, которые в дальнейшем передаются в workflow
     *
     * @var bool
     */
    protected $flagRunPrepareData = false;

    /**
     * Значение определяет что является обработчиком подготавливающим данные (метод контроллера, сервис и т.д.)
     *
     * @var string
     */
    protected $prepareDataMethod;

    /**
     * Строка содержащая имя обработчика (имя метода контроллера или имя сервиса) в котором происходит подготовка данных
     *
     * @var string
     */
    protected $prepareDataHandler;

    /**
     * @var bool
     */
    protected $flagHasConditions = false;

    /**
     * @var DispatchConditionMetadata[]|SplObjectStorage
     */
    protected $conditions;

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
     *
     */
    public function __construct()
    {
        $this->conditions = new SplObjectStorage();
    }

    /**
     * Флаг определят нужно ли запускать workflow
     *
     * @return boolean
     */
    public function isWorkflowDispatch()
    {
        return $this->workflowDispatch;
    }

    /**
     * Устанавливает флаг определяющий нужно ли запускать workflow
     *
     * @param boolean $workflowDispatch
     *
     * @return $this
     */
    public function setWorkflowDispatch($workflowDispatch)
    {
        $this->workflowDispatch = (boolean)$workflowDispatch;

        return $this;
    }

    /**
     * Определяет что мы хотим сделать с workflow. Запустить новый процесс (initialize), или инициировать переход в уже
     * запущенном процессе (doAction)
     *
     * @return string
     */
    public function getWorkflowRunType()
    {
        return $this->workflowRunType;
    }

    /**
     *
     * Определяет что мы хотим сделать с workflow. Запустить новый процесс (initialize), или инициировать переход в уже
     * запущенном процессе (doAction)
     *
     * @param string $workflowRunType
     *
     * @return $this
     *
     * @throws Exception\InvalidMetadataException
     */
    public function setWorkflowRunType($workflowRunType)
    {
        if (!array_key_exists($workflowRunType, $this->allowWorkflowRunType)) {
            $errMsg = sprintf('Not allowed type %s', $workflowRunType);
            throw new Exception\InvalidMetadataException($errMsg);
        }
        $this->workflowRunType = (string)$workflowRunType;

        return $this;
    }

    /**
     *  Флаг определят нужно вызвать метод или сервис, с целью подготовки данных, которые в дальнейшем передаются в workflow
     *
     * @return boolean
     */
    public function isFlagRunPrepareData()
    {
        return $this->flagRunPrepareData;
    }

    /**
     * Устанавливает флаг определяющий нужно вызвать метод или сервис, с целью подготовки данных, которые в дальнейшем
     * передаются в workflow
     *
     * @param boolean $flagRunPrepareData
     *
     * @return $this
     */
    public function setFlagRunPrepareData($flagRunPrepareData)
    {
        $this->flagRunPrepareData = (boolean)$flagRunPrepareData;

        return $this;
    }

    /**
     * Значение определяет что является обработчиком подготавливающим данные (метод контроллера, сервис и т.д.)
     *
     * @return string
     */
    public function getPrepareDataMethod()
    {
        return $this->prepareDataMethod;
    }

    /**
     * Устанавливает значение определяющие что является обработчиком подготавливающим данные
     * (метод контроллера, сервис и т.д.)
     *
     * @param string $prepareDataMethod
     *
     * @return $this
     */
    public function setPrepareDataMethod($prepareDataMethod)
    {
        $this->prepareDataMethod = (string)$prepareDataMethod;

        return $this;
    }

    /**
     *  Строка содержащая имя обработчика (имя метода контроллера или имя сервиса) в котором происходит подготовка данных
     *
     * @return string
     */
    public function getPrepareDataHandler()
    {
        return $this->prepareDataHandler;
    }

    /**
     * Устанавлвивает строку содержащую имя обработчика (имя метода контроллера или имя сервиса)
     * в котором происходит подготовка данных
     *
     * @param string $prepareDataHandler
     *
     * @return $this
     */
    public function setPrepareDataHandler($prepareDataHandler)
    {
        $this->prepareDataHandler = (string)$prepareDataHandler;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getFlagHasConditions()
    {
        return $this->flagHasConditions;
    }

    /**
     * @param boolean $flagHasConditions
     *
     * @return $this
     */
    public function setFlagHasConditions($flagHasConditions)
    {
        $this->flagHasConditions = (boolean)$flagHasConditions;

        return $this;
    }

    /**
     * @return DispatchConditionMetadata[]|SplObjectStorage
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param DispatchConditionMetadata $condition
     *
     * @return $this
     */
    public function addConditions(DispatchConditionMetadata $condition)
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions->attach($condition);
        }
        return $this;
    }
}
