<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\RunParamsHandler;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManager;

/**
 * Class RouteHandlerFactory
 *
 * @package OldTown\Workflow\ZF2\Dispatch\RunParamsHandler
 */
class RouteHandlerFactory implements  FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RouteHandler
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get(ModuleOptions::class);

        $readerName = $moduleOptions->getRunWorkflowParamsMetadataReader();
        /** @var MetadataReaderManager $readerManager */
        $readerManager = $serviceLocator->get(MetadataReaderManager::class);

        $reader = $readerManager->get($readerName);


        $options = [
            'metadataReader' => $reader
        ];

        return new RouteHandler($options);
    }
}
