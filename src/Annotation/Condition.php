<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;


/**
 * Class Condition
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Annotation
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Condition
{
    /**
     * @var string
     *
     * @Enum({"service"})
     * @Required()
     */
    public $type;

    /**
     * @var string
     *
     * @Required()
     */
    public $handler;

    /**
     * @var array
     */
    public $params = [];
}
