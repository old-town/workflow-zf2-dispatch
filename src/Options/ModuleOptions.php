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
    const METADATA_READER = 'metadataReader';

    /**
     * @var string
     */
    const METADATA_READER_MANAGER_CLASS_NAME = 'metadataReaderManagerClassName';

    /**
     * Имя адаптера для получения метаданных
     *
     * @var string
     */
    protected $metadataReader;

    /**
     * Класс менеджера для получения адаптеров метаданных
     *
     * @var string
     */
    protected $metadataReaderManagerClassName;

    /**
     * @return string
     */
    public function getMetadataReader()
    {
        return $this->metadataReader;
    }

    /**
     * @param string $metadataReader
     *
     * @return $this
     */
    public function setMetadataReader($metadataReader)
    {
        $this->metadataReader = $metadataReader;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetadataReaderManagerClassName()
    {
        return $this->metadataReaderManagerClassName;
    }

    /**
     * @param string $metadataReaderManagerClassName
     *
     * @return $this
     */
    public function setMetadataReaderManagerClassName($metadataReaderManagerClassName)
    {
        $this->metadataReaderManagerClassName = $metadataReaderManagerClassName;

        return $this;
    }
}
