<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata;

/**
 * Interface MetadataReaderProviderInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata
 */
interface MetadataReaderProviderInterface
{
    /**
     * @return array
     */
    public function getWorkflowDispatchMetadataReaderConfig();
}
