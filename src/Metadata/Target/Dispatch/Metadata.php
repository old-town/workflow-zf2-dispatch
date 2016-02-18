<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch;

use SplObjectStorage;


/**
 * Class Metadata
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch
 */
class Metadata implements MetadataInterface
{
    /**
     * @var string
     */
    const PREPARE_DATA_RUN_TYPE_METHOD = 'method';

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
     */
    public function setWorkflowRunType($workflowRunType)
    {
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
     *
     * @throws Exception\InvalidMetadataException
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
    }
}
