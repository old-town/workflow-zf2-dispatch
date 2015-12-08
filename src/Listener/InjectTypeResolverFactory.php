<?php
/**
 * @link https://github.com/old-town/workflow-zf2-preDispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\PreDispatch\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class InjectTypeResolverFactory
 *
 * @package OldTown\Workflow\ZF2\PreDispatch\Listener
 */
class InjectTypeResolverFactory implements  FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return InjectTypeResolver
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\Workflow\ZF2\PreDispatch\Listener\Exception\InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $options = [

        ];

        $service = new InjectTypeResolver($options);

        return $service;
    }
}
