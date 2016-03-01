<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;

use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams\MetadataInterface;

/**
 * Class RunWorkflowParamFromRoute
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
final class RunWorkflowParamFromRoute
{
    /**
     * @var string
     */
    public $managerName = MetadataInterface::WORKFLOW_MANAGER_NAME_ROUTER_PARAM;
    /**
     * @var string
     */
    public $managerAlias = MetadataInterface::WORKFLOW_MANAGER_ALIAS_ROUTER_PARAM;

    /**
     * @var string
     */
    public $actionName = MetadataInterface::WORKFLOW_ACTION_NAME_ROUTER_PARAM;

    /**
     * @var string
     */
    public $name = MetadataInterface::WORKFLOW_NAME_ROUTER_PARAM;

    /**
     * @var string
     */
    public $entryId = MetadataInterface::ENTRY_ID_ROUTER_PARAM;
}
