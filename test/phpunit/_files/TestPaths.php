<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-preDispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\PreDispatch\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package OldTown\Workflow\ZF2\PreDispatch\PhpUnit\TestData
 */
class TestPaths
{
    /**
     * Путь до директории где находится файл инициирующий приложение
     *
     * @return string
     */
    public static function getPathToModule()
    {
        return __DIR__ . '/../../../';
    }

    /**
     * Путь до дефалтового конфига приложения
     *
     * @return string
     */
    public static function getPathToDefaultAppConfig()
    {
        return __DIR__ . '/../_files/DefaultApp/application.config.php';
    }
}
