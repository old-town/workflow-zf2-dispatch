<?php
/**
 * @link https://github.com/old-town/workflow-zf2-preDispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\PreDispatch;


use OldTown\Workflow\ZF2\Service\Workflow;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use OldTown\Workflow\ZF2\PreDispatch\Listener\InjectTypeResolver;


/**
 * Class Module
 *
 * @package OldTown\Workflow\ZF2
 */
class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    DependencyIndicatorInterface
{

    /**
     * Имя секции в конфиги приложения
     *
     * @var string
     */
    const CONFIG_KEY = 'workflow_zf2_preDispatch';

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

        /** @var Workflow $workflowService */
        $workflowService = $e->getApplication()->getServiceManager()->get(Workflow::class);
        $listener = $e->getApplication()->getServiceManager()->get(InjectTypeResolver::class);
        $workflowService->getEventManager()->attach($listener);
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

} 