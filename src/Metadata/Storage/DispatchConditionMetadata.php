<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Storage;

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
    const CONDITION_RUN_TYPE_SERVICE = 'service';

    /**
     * Разрешенные типы запуска workflow
     *
     * @var array
     */
    protected $allowConditionRunType = [
        self::CONDITION_RUN_TYPE_SERVICE => self::CONDITION_RUN_TYPE_SERVICE
    ];

    /**
     * Тип запуска обработчика проверки условий
     *
     * @var string
     */
    protected $type;

    /**
     * Определяет обработчик для провекри условий
     *
     * @var string
     *
     */
    protected $handler;

    /**
     * Параметры обработчика
     *
     * @var array
     */
    protected $params = [];

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
     * Тип запуска обработчика проверки условий
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Устанавливает тип запуска обработчика проверки условий
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        if (!array_key_exists($type, $this->allowConditionRunType)) {
            $errMsg = sprintf('Not allowed type %s', $type);
            throw new Exception\InvalidMetadataException($errMsg);
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Определяет обработчик для провекри условий
     *
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Устанавливает обработчик для провекри условий
     *
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

    /**
     *
     * @throws Exception\InvalidMetadataException
     */
    public function validate()
    {
        if (null === $this->getType()) {
            $errMsg = 'Condition run type not specified';
            throw new Exception\InvalidMetadataException($errMsg);
        }


        $handler = $this->getHandler();
        $handler = trim($handler);
        if (empty($handler) || null === $handler) {
            $errMsg = 'Condition handler not specified';
            throw new Exception\InvalidMetadataException($errMsg);
        }
    }
}
