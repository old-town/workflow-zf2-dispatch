<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class MetadataReaderManager
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata
 */
class MetadataReaderManager extends AbstractPluginManager implements MetadataReaderManagerInterface
{
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
