<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Storage;

use SplObjectStorage;

/**
 * Class Metadata
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Storage
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
     * @var string
     */
    const PREPARE_DATA_RUN_TYPE_METHOD = 'method';

    /**
     * Имя параметра роуетра, значение которого определяет имя используемого менеджера workflow
     *
     * @var string
     */
    const WORKFLOW_MANAGER_NAME_ROUTER_PARAM = 'workflowManagerName';

    /**
     *  Имя параметра роуетра, значение которого определяет имя действия в workflow
     *
     * @var string
     */
    const WORKFLOW_ACTION_NAME_ROUTER_PARAM = 'workflowActionName';

    /**
     *  Имя параметра роуетра, значение которого определяет имя workflow
     *
     * @var string
     */
    const WORKFLOW_NAME_ROUTER_PARAM = 'workflowName';

    /**
     *  Имя параметра роуетра, значение которого определяет id процесса workflow
     *
     * @var string
     */
    const ENTRY_ID_ROUTER_PARAM = 'entryId';

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
     * Флаг указывает на то что есть условия для запуска workflow
     *
     * @var bool
     */
    protected $flagHasConditions = false;

    /**
     * Метаданные, содержащии информацию о том как вызвать условия, для проверка нужно ли запускать workflow
     *
     * @var DispatchConditionMetadata[]|SplObjectStorage
     */
    protected $conditions;

    /**
     * Имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @var string
     */
    protected $workflowManagerNameRouterParam = self::WORKFLOW_MANAGER_NAME_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого имя вызываемого действия
     *
     * @var string
     */
    protected $workflowActionNameRouterParam = self::WORKFLOW_ACTION_NAME_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого имя workflow
     *
     * @var string
     */
    protected $workflowNameRouterParam = self::WORKFLOW_NAME_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @var string
     */
    protected $entryIdRouterParam = self::ENTRY_ID_ROUTER_PARAM;

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
     * Разрешенные способы запука обработчика отвечающего за подготовку данных для wf
     *
     * @var array
     */
    protected $allowPrepareDataMethod = [
        self::PREPARE_DATA_RUN_TYPE_METHOD => self::PREPARE_DATA_RUN_TYPE_METHOD
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
        if (!array_key_exists($prepareDataMethod, $this->allowPrepareDataMethod)) {
            $errMsg = sprintf('Not allowed prepare data method %s', $prepareDataMethod);
            throw new Exception\InvalidMetadataException($errMsg);
        }
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
     * Флаг указывает на то что есть условия для запуска workflow
     *
     * @return boolean
     */
    public function getFlagHasConditions()
    {
        return $this->flagHasConditions;
    }

    /**
     * Устанавливает флаг указываеющий на то что есть условия для запуска workflow
     *
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
     * Возвращает метаданные, содержащии информацию о том как вызвать условия, для проверка нужно ли запускать workflow
     *
     * @return DispatchConditionMetadata[]|SplObjectStorage
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Добавляет метаданные, содержащии информацию о том как вызвать условия, для проверка нужно ли запускать workflow
     *
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

    /**
     * Имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @return string
     */
    public function getWorkflowManagerNameRouterParam()
    {
        return $this->workflowManagerNameRouterParam;
    }

    /**
     * Устаналвивает имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @param string $workflowManagerNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowManagerNameRouterParam($workflowManagerNameRouterParam)
    {
        $this->workflowManagerNameRouterParam = $workflowManagerNameRouterParam;

        return $this;
    }

    /**
     * Имя параметра в роуетере, значение которого имя вызываемого действия
     *
     * @return string
     */
    public function getWorkflowActionNameRouterParam()
    {
        return $this->workflowActionNameRouterParam;
    }

    /**
     * Устанавливает имя параметра в роуетере, значение которого - имя вызываемого действия
     *
     * @param string $workflowActionNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowActionNameRouterParam($workflowActionNameRouterParam)
    {
        $this->workflowActionNameRouterParam = $workflowActionNameRouterParam;

        return $this;
    }

    /**
     * Имя параметра в роуетере, значение которого имя workflow
     *
     * @return string
     */
    public function getWorkflowNameRouterParam()
    {
        return $this->workflowNameRouterParam;
    }

    /**
     * Устанавливает имя параметра в роуетере, значение которого имя workflow
     *
     * @param string $workflowNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowNameRouterParam($workflowNameRouterParam)
    {
        $this->workflowNameRouterParam = $workflowNameRouterParam;

        return $this;
    }

    /**
     * Имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @return string
     */
    public function getEntryIdRouterParam()
    {
        return $this->entryIdRouterParam;
    }

    /**
     * Устанавливает имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @param string $entryIdRouterParam
     *
     * @return $this
     */
    public function setEntryIdRouterParam($entryIdRouterParam)
    {
        $this->entryIdRouterParam = (string)$entryIdRouterParam;

        return $this;
    }


    /**
     * Проверка метаданных
     *
     * @throws Exception\InvalidMetadataException
     */
    public function validate()
    {
        if ($this->isWorkflowDispatch() && null === $this->getWorkflowRunType()) {
            $errMsg = 'workflowRunType not specified';
            throw new Exception\InvalidMetadataException($errMsg);
        }

        if ($this->isFlagRunPrepareData()) {
            if (null === $this->getPrepareDataMethod()) {
                $errMsg = 'prepareDataMethod not specified';
                throw new Exception\InvalidMetadataException($errMsg);
            }
            if (null === $this->getPrepareDataMethod()) {
                $errMsg = 'prepareDataMethod not specified';
                throw new Exception\InvalidMetadataException($errMsg);
            }
        }

        if ($this->getFlagHasConditions()) {
            if (0 === count($this->getConditions())) {
                $errMsg = 'empty conditions';
                throw new Exception\InvalidMetadataException($errMsg);
            }

            foreach ($this->getConditions() as $condition) {
                $condition->validate();
            }
        }

        $workflowManagerNameRouterParam = $this->getWorkflowManagerNameRouterParam();
        $workflowManagerNameRouterParam = trim($workflowManagerNameRouterParam);
        if (empty($workflowManagerNameRouterParam) || null === $workflowManagerNameRouterParam) {
            $errMsg = 'Invalid workflowManagerNameRouterParam';
            throw new Exception\InvalidMetadataException($errMsg);
        }

        $workflowActionNameRouterParam = $this->getWorkflowActionNameRouterParam();
        $workflowActionNameRouterParam = trim($workflowActionNameRouterParam);
        if (empty($workflowActionNameRouterParam) || null === $workflowActionNameRouterParam) {
            $errMsg = 'Invalid workflowActionNameRouterParam';
            throw new Exception\InvalidMetadataException($errMsg);
        }

        if (static::WORKFLOW_RUN_INITIALIZE === $this->getWorkflowRunType()) {
            $workflowNameRouterParam = $this->getWorkflowNameRouterParam();
            $workflowNameRouterParam = trim($workflowNameRouterParam);
            if (empty($workflowNameRouterParam) || null === $workflowNameRouterParam) {
                $errMsg = 'Invalid workflowNameRouterParam';
                throw new Exception\InvalidMetadataException($errMsg);
            }
        }

        if (static::WORKFLOW_RUN_TYPE_DO_ACTION === $this->getWorkflowRunType()) {
            $entryIdRouterParam = $this->getEntryIdRouterParam();
            $entryIdRouterParam = trim($entryIdRouterParam);
            if (empty($entryIdRouterParam) || null === $entryIdRouterParam) {
                $errMsg = 'Invalid entryIdRouterParam';
                throw new Exception\InvalidMetadataException($errMsg);
            }
        }
    }
}
