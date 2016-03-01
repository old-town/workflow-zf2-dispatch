<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use OldTown\Workflow\ZF2\ServiceEngine\Workflow as WorkflowService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManager;
use Zend\Log\Logger;
use Zend\Log\Writer\Noop;

/**
 * Class DispatcherFactory
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
class DispatcherFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Dispatcher
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\InvalidArgumentException
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\MetadataReaderManagerException
     * @throws Exception\MetadataReaderManagerException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $workflowService = $serviceLocator->get(WorkflowService::class);

        /** @var MetadataReaderManager $metadataReaderManager */
        $metadataReaderManager = $serviceLocator->get(MetadataReaderManager::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get(ModuleOptions::class);

        $metadataReader = $metadataReaderManager->get($moduleOptions->getDispatchMetadataReader());

        $validatorManager = $serviceLocator->get('ValidatorManager');

        $logName = $moduleOptions->getLogName();
        if (null === $logName) {
            $log = new Logger();
            $writer = new Noop();
            $log->addWriter($writer);
        } else {
            $log = $serviceLocator->get($logName);
        }

        $options = [
            'workflowService'  => $workflowService,
            'metadataReader'   => $metadataReader,
            'validatorManager' => $validatorManager,
            'log'              => $log
        ];

        return new Dispatcher($options);
    }
}
