<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * Class MetadataReaderManagerFactory
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata
 */
class MetadataReaderManagerFactory extends AbstractPluginManagerFactory
{
    /**
     * @var string
     */
    const PLUGIN_MANAGER_CLASS = MetadataReaderManager::class;
}
