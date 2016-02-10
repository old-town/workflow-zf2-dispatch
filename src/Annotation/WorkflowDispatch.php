<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Action контроллера работает с workflow
 *
 * Class WorkflowDispatch
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
final class WorkflowDispatch
{
    /**
     * @var boolean
     */
    public $enabled = true;

    /**
     * @var string
     *
     * @Enum({"doAction", "initialize"})
     * @Required()
     */
    public $activity;
}
