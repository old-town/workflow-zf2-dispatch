<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Options
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    const DISPATCH_METADATA_READER = 'dispatchMetadataReader';

    /**
     * @var string
     */
    const RUN_WORKFLOW_PARAMS_METADATA_READER = 'runWorkflowParamsMetadataReader';

    /**
     * @var string
     */
    const LOG_NAME = 'logName';

    /**
     * Имя адаптера для получения метаданных необходимых для начали цикла диспетчирезации workflow
     *
     * @var string
     */
    protected $dispatchMetadataReader;

    /**
     * Имя адаптера для получения метаданных необходимых для  запуска workflow
     *
     * @var string
     */
    protected $runWorkflowParamsMetadataReader;

    /**
     * Имя используемого логера
     *
     * @var string|null
     */
    protected $logName;

    /**
     * @return string
     */
    public function getDispatchMetadataReader()
    {
        return $this->dispatchMetadataReader;
    }

    /**
     * @param string $dispatchMetadataReader
     *
     * @return $this
     */
    public function setDispatchMetadataReader($dispatchMetadataReader)
    {
        $this->dispatchMetadataReader = $dispatchMetadataReader;

        return $this;
    }

    /**
     * @return string
     */
    public function getRunWorkflowParamsMetadataReader()
    {
        return $this->runWorkflowParamsMetadataReader;
    }

    /**
     * @param string $runWorkflowParamsMetadataReader
     *
     * @return $this
     */
    public function setRunWorkflowParamsMetadataReader($runWorkflowParamsMetadataReader)
    {
        $this->runWorkflowParamsMetadataReader = $runWorkflowParamsMetadataReader;

        return $this;
    }

    /**
     * Имя используемого логера
     *
     * @return null|string
     */
    public function getLogName()
    {
        return $this->logName;
    }

    /**
     * Имя используемого логера
     *
     * @param null|string $logName
     *
     * @return $this
     */
    public function setLogName($logName)
    {
        $this->logName = $logName;

        return $this;
    }
}
