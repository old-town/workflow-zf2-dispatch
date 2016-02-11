<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\Dispatcher;


/**
 * Class WorkflowDispatchListenerFactory
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Listener
 */
class WorkflowDispatchListenerFactory implements  FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return WorkflowDispatchListener
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $serviceLocator->get(Dispatcher::class);

        $options = [
            'dispatcher' => $dispatcher
        ];

        $service = new WorkflowDispatchListener($options);

        return $service;
    }
}
