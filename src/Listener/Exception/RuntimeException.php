<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\Dispatch\Listener\Exception;

use OldTown\Workflow\ZF2\Dispatch\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Listener\Exception
 */
class RuntimeException extends Exception implements
    ExceptionInterface
{
}
