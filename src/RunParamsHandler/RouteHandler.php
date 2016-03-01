<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\RunParamsHandler;

use OldTown\Workflow\ZF2\ServiceEngine\WorkflowServiceInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerInterface;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\Dispatcher;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\WorkflowDispatchEventInterface;
use OldTown\Workflow\ZF2\Dispatch\Dispatcher\RunWorkflowParam;
use OldTown\Workflow\ZF2\Dispatch\Metadata\ReaderInterface;
use Zend\Mvc\Controller\AbstractController;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams\MetadataInterface;
use OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler\ResolveEntryIdEvent;
use OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler\ResolveEntryIdEventInterface;
use ReflectionClass;
use Zend\Log\LoggerInterface;

/**
 * Class RouteHandler
 *
 * @package OldTown\Workflow\ZF2\Dispatch\RunParamsHandler
 */
class RouteHandler extends AbstractListenerAggregate
{
    use EventManagerAwareTrait;

    /**
     * Адаптер для чтения метаданных
     *
     * @var ReaderInterface
     */
    protected $metadataReader;

    /**
     * Имя класса событие, бросаемого когда требуется определить entryId
     *
     * @var string
     */
    protected $resolveEntryIdEventClassName = ResolveEntryIdEvent::class;

    /**
     * Сервис для работы с workflow
     *
     * @var WorkflowServiceInterface
     */
    protected $workflowService;

    /**
     * Логер
     *
     * @var LoggerInterface
     */
    protected $log;

    /**
     * RouteHandler constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $initOptions = [
            array_key_exists('metadataReader', $options) ? $options['metadataReader'] : null,
            array_key_exists('workflowService', $options) ? $options['workflowService'] : null,
            array_key_exists('log', $options) ? $options['log'] : null,
        ];
        call_user_func_array([$this, 'init'], $initOptions);
    }

    /**
     * @param ReaderInterface          $metadataReader
     * @param WorkflowServiceInterface $workflowService
     * @param LoggerInterface          $log
     */
    protected function init(ReaderInterface $metadataReader, WorkflowServiceInterface $workflowService, LoggerInterface $log)
    {
        $this->setMetadataReader($metadataReader);
        $this->setWorkflowService($workflowService);
        $this->setLog($log);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach(Dispatcher::class, WorkflowDispatchEventInterface::METADATA_WORKFLOW_TO_RUN_EVENT, [$this, 'onMetadataWorkflowToRun'], 80);
    }

    /**
     * Получение метаданных для запуска workflow
     *
     * @param WorkflowDispatchEventInterface $e
     *
     * @return RunWorkflowParam|null
     *
     * @throws Exception\InvalidMetadataException
     * @throws Exception\ResolveEntryIdEventException
     * @throws Exception\InvalidWorkflowManagerAliasException
     * @throws Exception\RuntimeException
     */
    public function onMetadataWorkflowToRun(WorkflowDispatchEventInterface $e)
    {
        $mvcEvent = $e->getMvcEvent();
        $controller = $mvcEvent->getTarget();
        if (!$controller instanceof AbstractController) {
            return null;
        }

        $routeMatch = $mvcEvent->getRouteMatch();
        if (!$routeMatch) {
            return null;
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $actionMethod = AbstractController::getMethodFromAction($action);

        if (!method_exists($controller, $actionMethod)) {
            return null;
        }

        $controllerClassName = get_class($controller);

        $metadata = $this->getMetadataReader()->loadMetadataForAction($controllerClassName, $actionMethod);

        if (!$metadata instanceof MetadataInterface) {
            $errMsg = sprintf('Metadata not implement %s', MetadataInterface::class);
            throw new Exception\InvalidMetadataException($errMsg);
        }

        $workflowManagerNameParam = $metadata->getWorkflowManagerNameRouterParam();
        $workflowManagerNameFromRoute = $routeMatch->getParam($workflowManagerNameParam, null);

        $workflowManagerAliasParam = $metadata->getWorkflowManagerAliasRouterParam();
        $workflowManagerAlias = $routeMatch->getParam($workflowManagerAliasParam, null);

        $workflowManagerName = $workflowManagerNameFromRoute;
        if (null !== $workflowManagerAlias) {
            $workflowService = $this->getWorkflowService();
            if (!$workflowService->hasWorkflowManagerAlias($workflowManagerAlias)) {
                $errMsg = sprintf('Invalid workflow manager alias: %s', $workflowManagerAlias);
                throw new Exception\InvalidWorkflowManagerAliasException($errMsg);
            }

            $workflowManagerNameByAlias = $workflowService->getManagerNameByAlias($workflowManagerAlias);

            if (null !== $workflowManagerNameFromRoute && $workflowManagerNameFromRoute !== $workflowManagerNameByAlias) {
                $errMsg = sprintf(
                    'Name collision workflow manager.The name of the alias: %s. The name of the router: %s',
                    $workflowManagerNameByAlias,
                    $workflowManagerNameFromRoute
                );
                throw new Exception\RuntimeException($errMsg);
            }
            $workflowManagerName = $workflowManagerNameByAlias;
        }

        $workflowActionNameParam = $metadata->getWorkflowActionNameRouterParam();
        $workflowActionName = $routeMatch->getParam($workflowActionNameParam, null);

        if (null === $workflowManagerName || null === $workflowActionName) {
            $this->getLog()->debug('Metadata Workflow to start not found', [
                'workflowManagerNameFromRoute' => $workflowManagerNameFromRoute,
                'workflowManagerAlias' => $workflowManagerAlias,
                'workflowManagerName' => $workflowManagerName,
                'workflowActionName' => $workflowActionName
            ]);
            return null;
        }

        $runType = $e->getMetadata()->getWorkflowRunType();

        $workflowNameParam = $metadata->getWorkflowNameRouterParam();
        $workflowName = $routeMatch->getParam($workflowNameParam, null);

        $runWorkflowParam = new RunWorkflowParam();
        $runWorkflowParam->setRunType($runType);
        $runWorkflowParam->setManagerName($workflowManagerName);
        $runWorkflowParam->setActionName($workflowActionName);

        if (RunWorkflowParam::WORKFLOW_RUN_INITIALIZE === $runType && null !== $workflowName) {
            $runWorkflowParam->setWorkflowName($workflowName);

            return $runWorkflowParam;
        }


        if (RunWorkflowParam::WORKFLOW_RUN_TYPE_DO_ACTION === $runType) {
            $event = $this->resolveEntryIdEventFactory();
            $event->setWorkflowDispatchEvent($e);
            $event->setRunType($runType);
            $event->setActionName($workflowActionName);
            $event->setManagerName($workflowManagerName);
            $event->setWorkflowName($workflowName);
            $event->setManagerAlias($workflowManagerAlias);


            $resolveEntryIdResults = $this->getEventManager()->trigger(ResolveEntryIdEventInterface::RESOLVE_ENTRY_ID_EVENT, $event, function ($item) {
                return is_numeric($item);
            });
            $resolveEntryIdResult = $resolveEntryIdResults->last();

            if (is_numeric($resolveEntryIdResult)) {
                $runWorkflowParam->setEntryId($resolveEntryIdResult);

                return $runWorkflowParam;
            }
        }


        return null;
    }

    /**
     * Подписчики по умолчанию
     *
     * @return void
     */
    public function attachDefaultListeners()
    {
        $this->getEventManager()->attach(ResolveEntryIdEventInterface::RESOLVE_ENTRY_ID_EVENT, [$this, 'onResolveEntryIdHandler']);
    }

    /**
     * Определение entryId на основе параметров роута
     *
     * @param ResolveEntryIdEventInterface $event
     *
     * @return integer|null
     *
     * @throws Exception\InvalidMetadataException
     */
    public function onResolveEntryIdHandler(ResolveEntryIdEventInterface $event)
    {
        $this->getLog()->info('Getting the value "entryId" to run the Workflow based on the router settings');

        $mvcEvent = $event->getWorkflowDispatchEvent()->getMvcEvent();
        $controller = $mvcEvent->getTarget();
        if (!$controller instanceof AbstractController) {
            $this->getLog()->notice(
                'Unable to get the value of "entryId" to start the workflow. No controller object in the property "target" MvcEvent.'
            );
            return null;
        }

        $routeMatch = $mvcEvent->getRouteMatch();
        if (!$routeMatch) {
            return null;
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $actionMethod = AbstractController::getMethodFromAction($action);

        if (!method_exists($controller, $actionMethod)) {
            $this->getLog()->notice(
                'Unable to get the value of "entryId" to start the workflow. Do not set RouteMatch'
            );
            return null;
        }

        $controllerClassName = get_class($controller);
        $metadata = $this->getMetadataReader()->loadMetadataForAction($controllerClassName, $actionMethod);

        if (!$metadata instanceof MetadataInterface) {
            $errMsg = sprintf('Metadata not implement %s', MetadataInterface::class);
            throw new Exception\InvalidMetadataException($errMsg);
        }

        $entryIdParam = $metadata->getEntryIdRouterParam();
        $entryId = $routeMatch->getParam($entryIdParam, null);

        $this->getLog()->info(
            'Meaning "entryId" to run the Workflow based on the router settings',
            [
                'entryId' => $entryId
            ]
        );

        return $entryId;
    }


    /**
     * @return ReaderInterface
     */
    public function getMetadataReader()
    {
        return $this->metadataReader;
    }

    /**
     * @param ReaderInterface $metadataReader
     *
     * @return $this
     */
    public function setMetadataReader(ReaderInterface $metadataReader)
    {
        $this->metadataReader = $metadataReader;

        return $this;
    }

    /**
     * Имя класса событие, бросаемого когда требуется определить entryId
     *
     * @return string
     */
    public function getResolveEntryIdEventClassName()
    {
        return $this->resolveEntryIdEventClassName;
    }

    /**
     * Устанавливает имя класса событие, бросаемого когда требуется определить entryId
     *
     * @param string $resolveEntryIdEventClassName
     *
     * @return $this
     */
    public function setResolveEntryIdEventClassName($resolveEntryIdEventClassName)
    {
        $this->resolveEntryIdEventClassName = $resolveEntryIdEventClassName;

        return $this;
    }

    /**
     * Фабрика для создания объекта события бросаемого когда нужно определить значение entryId
     *
     * @return ResolveEntryIdEventInterface
     *
     * @throws Exception\ResolveEntryIdEventException
     */
    public function resolveEntryIdEventFactory()
    {
        $className = $this->getResolveEntryIdEventClassName();

        $r = new ReflectionClass($className);
        $event = $r->newInstance();

        if (!$event instanceof ResolveEntryIdEventInterface) {
            $errMsg = sprintf('ResolveEntryIdEvent not implement %s', ResolveEntryIdEventInterface::class);
            throw new Exception\ResolveEntryIdEventException($errMsg);
        }

        return $event;
    }

    /**
     * Устанавливает логер
     *
     * @return LoggerInterface
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Возвращает логер
     *
     * @param LoggerInterface $log
     *
     * @return $this
     */
    public function setLog(LoggerInterface $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Сервис для работы с workflow
     *
     * @return WorkflowServiceInterface
     */
    public function getWorkflowService()
    {
        return $this->workflowService;
    }

    /**
     * Устанавливает сервис для работы с workflow
     *
     * @param WorkflowServiceInterface $workflowService
     *
     * @return $this
     */
    public function setWorkflowService(WorkflowServiceInterface $workflowService)
    {
        $this->workflowService = $workflowService;

        return $this;
    }
}
