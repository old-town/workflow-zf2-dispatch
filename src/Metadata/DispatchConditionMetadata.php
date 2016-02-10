<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata;

/**
 * Interface MetadataInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata
 */
class DispatchConditionMetadata
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     *
     */
    protected $handler;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param       $type
     * @param       $handler
     * @param array $params
     */
    public function __construct($type, $handler, array $params = [])
    {
        $this->setType($type);
        $this->setHandler($handler);
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = null !== $type ? (string)$type: $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param string $handler
     *
     * @return $this
     */
    public function setHandler($handler = null)
    {
        $this->handler = null !== $handler ? (string)$handler: $handler;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;

        return $this;
    }
}
