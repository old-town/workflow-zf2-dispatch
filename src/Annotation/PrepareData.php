<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class PrepareWorkflowData
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
final class PrepareData
{
    /**
     * @var boolean
     */
    public $enabled = true;

    /**
     * @var string
     *
     * @Enum({"method"})
     * @Required()
     */
    public $type;

    /**
     * @var string
     *
     * @Required()
     */
    public $handler;
}
