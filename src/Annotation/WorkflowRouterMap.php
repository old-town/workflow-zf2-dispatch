<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;

use OldTown\Workflow\ZF2\Dispatch\Metadata\Storage\Metadata;

/**
 * Class WorkflowRouterMap
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
final class WorkflowRouterMap
{
    /**
     * @var string
     */
    public $managerName = Metadata::WORKFLOW_MANAGER_NAME_ROUTER_PARAM;

    /**
     * @var string
     */
    public $actionName = Metadata::WORKFLOW_ACTION_NAME_ROUTER_PARAM;

    /**
     * @var string
     */
    public $name = Metadata::WORKFLOW_NAME_ROUTER_PARAM;

    /**
     * @var integer
     */
    public $entryId = Metadata::ENTRY_ID_ROUTER_PARAM;
}
