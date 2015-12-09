<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\Workflow\ZF2\Service\Workflow as WorkflowService;

/**
 * Class InjectTypeResolverFactory
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
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $workflowService = $serviceLocator->get(WorkflowService::class);
        $options = [
            'workflowService' => $workflowService
        ];

        $service = new WorkflowDispatchListener($options);

        return $service;
    }
}
