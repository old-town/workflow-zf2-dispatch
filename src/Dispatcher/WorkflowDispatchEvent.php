<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use OldTown\Workflow\ZF2\ServiceEngine\Workflow\TransitionResultInterface;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch\MetadataInterface;



/**
 * Class WorkflowDispatchEvent
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
class WorkflowDispatchEvent extends Event implements WorkflowDispatchEventInterface
{
    /**
     * @var MvcEvent
     */
    protected $mvcEvent;

    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * Данные для workflow
     *
     * @var array
     */
    protected $prepareData = [];

    /**
     * Результаты работы workflow
     *
     * @var mixed
     */
    protected $workflowResult = [];

    /**
     * Параметры для запуска workflow
     *
     * @var RunWorkflowParamInterface
     */
    protected $runWorkflowParam;

    /**
     * @return MvcEvent
     *
     * @throws Exception\WorkflowDispatchEventException
     */
    public function getMvcEvent()
    {
        if (null === $this->mvcEvent) {
            $errMsg = 'mvcEvent not found';
            throw new Exception\WorkflowDispatchEventException($errMsg);
        }
        return $this->mvcEvent;
    }

    /**
     * @param MvcEvent $mvcEvent
     *
     * @return $this
     */
    public function setMvcEvent(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;

        return $this;
    }

    /**
     * @return MetadataInterface
     *
     * @throws Exception\WorkflowDispatchEventException
     */
    public function getMetadata()
    {
        if (null === $this->metadata) {
            $errMsg = 'metadata not found';
            throw new Exception\WorkflowDispatchEventException($errMsg);
        }

        return $this->metadata;
    }

    /**
     * @param MetadataInterface $metadata
     *
     * @return $this
     */
    public function setMetadata(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * анные подготовленные для workflow
     *
     * @return array
     */
    public function getPrepareData()
    {
        return $this->prepareData;
    }

    /**
     * Устанавливает данные для workflow
     *
     * @param array $prepareData
     *
     * @return $this
     */
    public function setPrepareData(array $prepareData = [])
    {
        $this->prepareData = $prepareData;

        return $this;
    }

    /**
     * Возвращает результаты работы workflow
     *
     *
     * @return TransitionResultInterface
     */
    public function getWorkflowResult()
    {
        return $this->workflowResult;
    }

    /**
     * Устанавливает результаты работы workflow
     *
     * @param TransitionResultInterface $workflowResult
     *
     * @return $this
     */
    public function setWorkflowResult(TransitionResultInterface $workflowResult)
    {
        $this->workflowResult = $workflowResult;

        return $this;
    }

    /**
     * Параметры для запуска workflow
     *
     * @return RunWorkflowParamInterface
     *
     * @throws Exception\WorkflowDispatchEventException
     */
    public function getRunWorkflowParam()
    {
        if (null === $this->runWorkflowParam) {
            $errMsg = 'runWorkflowParam not found';
            throw new Exception\WorkflowDispatchEventException($errMsg);
        }
        return $this->runWorkflowParam;
    }

    /**
     * Устанавливает параметры для запуска workflow
     *
     * @param RunWorkflowParamInterface $runWorkflowParam
     *
     * @return $this
     */
    public function setRunWorkflowParam(RunWorkflowParamInterface $runWorkflowParam)
    {
        $this->runWorkflowParam = $runWorkflowParam;

        return $this;
    }
}
