<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;

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
     * Имя параметра роуетра, значение которого определяет имя используемого менеджера workflow
     *
     * @var string
     */
    const WORKFLOW_MANAGER_NAME = 'workflowManagerName';

    /**
     *  Имя параметра роуетра, значение которого определяет имя действия в workflow
     *
     * @var string
     */
    const WORKFLOW_ACTION_NAME = 'workflowActionName';

    /**
     *  Имя параметра роуетра, значение которого определяет имя workflow
     *
     * @var string
     */
    const WORKFLOW_NAME = 'workflowName';

    /**
     *  Имя параметра роуетра, значение которого определяет id процесса workflow
     *
     * @var string
     */
    const ENTRY_ID = 'entryId';

    /**
     * @var string
     */
    public $managerName = self::WORKFLOW_MANAGER_NAME;

    /**
     * @var string
     */
    public $actionName = self::WORKFLOW_ACTION_NAME;

    /**
     * @var string
     */
    public $name = self::WORKFLOW_NAME;

    /**
     * @var integer
     */
    public $entryId = self::ENTRY_ID;
}
