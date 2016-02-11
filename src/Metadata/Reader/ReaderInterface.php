<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Reader;

use \OldTown\Workflow\ZF2\Dispatch\Metadata\Storage\MetadataInterface;

/**
 * Interface ReaderInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Reader
 */
interface ReaderInterface
{
    /**
     * Получение метаданных для action контроллера
     *
     * @param string  $controllerClassName
     * @param  string $actionMethod
     *
     * @return MetadataInterface
     */
    public function loadMetadataForAction($controllerClassName, $actionMethod);
}
