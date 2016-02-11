<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\PhpUnit\Test;

use OldTown\Workflow\ZF2\Dispatch\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ModuleTest
 *
 * @package OldTown\Workflow\ZF2\Dispatch\PhpUnit\Test
 */
class IntegrationTest extends AbstractHttpControllerTestCase
{
    /**
     *
     * @return void
     */
    public function testDispatch()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToIntegrationTest()
        );

        $this->dispatch('test');
    }
}