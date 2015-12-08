<?php
/**
 * @link https://github.com/old-town/workflow-zf2-preDispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace  OldTown\Workflow\ZF2\PreDispatch\Listener\Exception;

use OldTown\Workflow\ZF2\PreDispatch\Exception\InvalidArgumentException as Exception;

/**
 * Class InvalidArgumentException
 *
 * @package OldTown\Workflow\ZF2\PreDispatch\Listener\Exception
 */
class InvalidArgumentException extends Exception implements
    ExceptionInterface
{
}
