<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use OldTown\Workflow\ZF2\ServiceEngine\Workflow\TransitionResultInterface;
use Zend\Mvc\MvcEvent;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Storage\MetadataInterface;
use Zend\EventManager\EventInterface;


/**
 * Interface WorkflowDispatchEventInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
interface WorkflowDispatchEventInterface extends EventInterface
{
    /**
     * Получение метаданных
     *
     * @var string
     */
    const LOAD_METADATA_EVENT = 'workflow.dispatch.metadata';

    /**
     * Получение данных для wf
     *
     * @var string
     */
    const PREPARE_DATA_EVENT = 'workflow.dispatch.prepareData';

    /**
     * Проверка, нужно ли запускать workflow
     *
     * @var string
     */
    const CHECK_RUN_WORKFLOW_EVENT = 'workflow.dispatch.checkRunWorkflow';

    /**
     * Запуск workflow
     *
     * @var string
     */
    const RUN_WORKFLOW_EVENT = 'workflow.dispatch.run';

    /**
     * @return MvcEvent
     */
    public function getMvcEvent();

    /**
     * @param MvcEvent $mvcEvent
     *
     * @return $this
     */
    public function setMvcEvent(MvcEvent $mvcEvent);

    /**
     * @return MetadataInterface
     */
    public function getMetadata();

    /**
     * @param MetadataInterface $metadata
     *
     * @return $this
     */
    public function setMetadata(MetadataInterface $metadata);


    /**
     * Данные подготовленные для workflow
     *
     * @return array
     */
    public function getPrepareData();

    /**
     * Устанавливает данные для workflow
     *
     * @param array $prepareData
     *
     * @return $this
     */
    public function setPrepareData(array $prepareData = []);

    /**
     * Возвращает результаты работы workflow
     *
     * @return TransitionResultInterface
     */
    public function getWorkflowResult();

    /**
     * Устанавливает результаты работы workflow
     *
     * @param TransitionResultInterface $workflowResult
     *
     * @return $this
     */
    public function setWorkflowResult(TransitionResultInterface $workflowResult);
}
