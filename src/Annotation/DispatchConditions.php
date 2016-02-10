<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class DispatchCondition
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
final class DispatchConditions
{
    /**
     * @var array<\OldTown\Workflow\ZF2\Dispatch\Annotation\Condition>
     *
     * @Required()
     */
    public $conditions = [];
}
