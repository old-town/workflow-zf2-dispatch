<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata;

use OldTown\Workflow\ZF2\Dispatch\Metadata\Reader\ReaderInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use \OldTown\Workflow\ZF2\Dispatch\Metadata\Reader\AnnotationReader;

/**
 * Class MetadataReaderManager
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata
 */
class MetadataReaderManager extends AbstractPluginManager implements MetadataReaderManagerInterface
{
    /**
     * @param ConfigInterface|null $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        $this->init();
        parent::__construct($configuration);
    }

    /**
     * Инициализация
     *
     * @return void
     */
    protected function init()
    {
        $this->invokableClasses = [
            'annotation' => AnnotationReader::class
        ];
    }

    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidMetadataReaderException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof ReaderInterface) {
            $errMsg = sprintf('MetadataReader not implement %s', ReaderInterface::class);
            throw new Exception\InvalidMetadataReaderException($errMsg);
        }
    }
}
