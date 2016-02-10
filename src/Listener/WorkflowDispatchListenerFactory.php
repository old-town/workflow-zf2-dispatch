<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Listener;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\Workflow\ZF2\Service\Workflow as WorkflowService;
use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManagerInterface;
use ReflectionClass;

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
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\MetadataReaderManagerException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $workflowService = $serviceLocator->get(WorkflowService::class);

        $metadataReaderManager = $this->metadataReaderFactory($serviceLocator);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get(ModuleOptions::class);

        $metadataReader = $metadataReaderManager->get($moduleOptions->getMetadataReader());

        $options = [
            'workflowService' => $workflowService,
            'metadataReader' => $metadataReader
        ];

        $service = new WorkflowDispatchListener($options);

        return $service;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return MetadataReaderManagerInterface
     * @throws \OldTown\Workflow\ZF2\Dispatch\Listener\Exception\MetadataReaderManagerException
     *
     */
    public function metadataReaderFactory(ServiceLocatorInterface $serviceLocator)
    {
        try {
            /** @var ModuleOptions $moduleOptions */
            $moduleOptions = $serviceLocator->get(ModuleOptions::class);

            $managerClassName = $moduleOptions->getMetadataReaderManagerClassName();

            if (!class_exists($managerClassName)) {
                $errMsg = sprintf('Class %s not found. Error create metadata reader manager', $managerClassName);
                throw new Exception\MetadataReaderManagerException($errMsg);
            }

            $r = new ReflectionClass($managerClassName);
            $manager = $r->newInstance();

            if (!$manager instanceof MetadataReaderManagerInterface) {
                $errMsg = sprintf('Metadata reader manager not implements %s', MetadataReaderManagerInterface::class);
                throw new Exception\MetadataReaderManagerException($errMsg);
            }

            if ($manager instanceof AbstractPluginManager) {
                $manager->setServiceLocator($serviceLocator);
            }
        } catch (\Exception $e) {
            throw new Exception\MetadataReaderManagerException($e->getMessage(), $e->getCode(), $e);
        }


        return $manager;
    }
}
