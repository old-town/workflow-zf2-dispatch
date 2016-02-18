<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch;

use SplObjectStorage;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataInterface as BaseMetadata;

/**
 * Interface MetadataInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch
 */
interface MetadataInterface extends BaseMetadata
{
    /**
     * Флаг определят нужно ли запускать workflow
     *
     * @return boolean
     */
    public function isWorkflowDispatch();

    /**
     * Устанавливает флаг определяющий нужно ли запускать workflow
     *
     * @param boolean $workflowDispatch
     *
     * @return $this
     */
    public function setWorkflowDispatch($workflowDispatch);

    /**
     * Определяет что мы хотим сделать с workflow. Запустить новый процесс (initialize), или инициировать переход в уже
     * запущенном процессе (doAction)
     *
     * @return string
     */
    public function getWorkflowRunType();

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
    public function setWorkflowRunType($workflowRunType);

    /**
     *  Флаг определят нужно вызвать метод или сервис, с целью подготовки данных, которые в дальнейшем передаются в workflow
     *
     * @return boolean
     */
    public function isFlagRunPrepareData();

    /**
     * Устанавливает флаг определяющий нужно вызвать метод или сервис, с целью подготовки данных, которые в дальнейшем
     * передаются в workflow
     *
     * @param boolean $flagRunPrepareData
     *
     * @return $this
     */
    public function setFlagRunPrepareData($flagRunPrepareData);

    /**
     * Значение определяет что является обработчиком подготавливающим данные (метод контроллера, сервис и т.д.)
     *
     * @return string
     */
    public function getPrepareDataMethod();

    /**
     * Устанавливает значение определяющие что является обработчиком подготавливающим данные
     * (метод контроллера, сервис и т.д.)
     *
     * @param string $prepareDataMethod
     *
     * @return $this
     */
    public function setPrepareDataMethod($prepareDataMethod);

    /**
     *  Строка содержащая имя обработчика (имя метода контроллера или имя сервиса) в котором происходит подготовка данных
     *
     * @return string
     */
    public function getPrepareDataHandler();

    /**
     * Устанавлвивает строку содержащую имя обработчика (имя метода контроллера или имя сервиса)
     * в котором происходит подготовка данных
     *
     * @param string $prepareDataHandler
     *
     * @return $this
     */
    public function setPrepareDataHandler($prepareDataHandler);

    /**
     * Флаг указывает на то что есть условия для запуска workflow
     *
     * @return boolean
     */
    public function getFlagHasConditions();

    /**
     * Устанавливает флаг указываеющий на то что есть условия для запуска workflow
     *
     * @param boolean $flagHasConditions
     *
     * @return $this
     */
    public function setFlagHasConditions($flagHasConditions);

    /**
     * Возвращает метаданные, содержащии информацию о том как вызвать условия, для проверка нужно ли запускать workflow
     *
     * @return DispatchConditionMetadata[]|SplObjectStorage
     */
    public function getConditions();

    /**
     * Добавляет метаданные, содержащии информацию о том как вызвать условия, для проверка нужно ли запускать workflow
     *
     * @param DispatchConditionMetadata $condition
     *
     * @return $this
     */
    public function addConditions(DispatchConditionMetadata $condition);

    /**
     * Проверка метаданных
     *
     * @throws Exception\InvalidMetadataException
     */
    public function validate();
}
