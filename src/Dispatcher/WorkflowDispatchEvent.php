<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use OldTown\Workflow\ZF2\ServiceEngine\Workflow\TransitionResultInterface;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Storage\MetadataInterface;


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
     * @return MvcEvent
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
}
