<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch;


use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use OldTown\Workflow\ZF2\Dispatch\Listener\WorkflowDispatchListener;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManager;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderProviderInterface;
use OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler;

/**
 * Class Module
 *
 * @package OldTown\Workflow\ZF2\Dispatch
 */
class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    DependencyIndicatorInterface,
    InitProviderInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Имя секции в конфиги приложения
     *
     * @var string
     */
    const CONFIG_KEY = 'workflow_zf2_dispatch';

    /**
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'OldTown\\Workflow\\ZF2'
        ];
    }

    /**
     * @param EventInterface $e
     *
     * @return array|void
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sm = $e->getApplication()->getServiceManager();

        /** @var WorkflowDispatchListener $injectWorkflowListener */
        $injectWorkflowListener = $sm->get(WorkflowDispatchListener::class);
        $eventManager->attach($injectWorkflowListener);

        /** @var RouteHandler $routeHandler */
        $routeHandler = $sm->get(RouteHandler::class);
        $eventManager->attach($routeHandler);
    }


    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }


    /**
     *
     * @param ModuleManagerInterface $manager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws Exception\ErrorInitModuleException
     */
    public function init(ModuleManagerInterface $manager)
    {

        if (!$manager instanceof ModuleManager) {
            $errMsg =sprintf('Module manager not implement %s', ModuleManager::class);
            throw new Exception\ErrorInitModuleException($errMsg);
        }
        /** @var ModuleManager $manager */

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        $serviceListener->addServiceManager(
            MetadataReaderManager::class,
            'workflow_zf2_dispatch_metadata_reader',
            MetadataReaderProviderInterface::class,
            'getWorkflowDispatchMetadataReaderConfig'
        );
    }
} 