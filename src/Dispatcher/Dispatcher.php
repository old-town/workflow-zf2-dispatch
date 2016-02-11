<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use OldTown\Workflow\TransientVars\TransientVarsInterface;
use Zend\EventManager\EventManagerAwareTrait;
use OldTown\Workflow\ZF2\ServiceEngine\Workflow as WorkflowService;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Reader\ReaderInterface;
use ReflectionClass;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Storage\MetadataInterface;
use Zend\Mvc\Controller\AbstractController;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\ValidatorPluginManager;
use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorInterface;
use OldTown\Workflow\TransientVars\BaseTransientVars;
use OldTown\Workflow\ZF2\ServiceEngine\Workflow\TransitionResultInterface;

/**
 * Class Dispatcher
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Dispatcher
 */
class Dispatcher implements DispatcherInterface
{
    use EventManagerAwareTrait;

    /**
     * @var string
     */
    const WORKFLOW_DISPATCH_EVENT = 'workflowDispatchEvent';

    /**
     * Имя класса события
     *
     * @var string
     */
    protected $workflowDispatchEventClassName = WorkflowDispatchEvent::class;

    /**
     * Имя класса события
     *
     * @var string
     */
    protected $transientVarsClassName = BaseTransientVars::class;

    /**
     * @var WorkflowService
     */
    protected $workflowService;

    /**
     * @var ReaderInterface
     */
    protected $metadataReader;

    /**
     * @var ValidatorPluginManager
     */
    protected $validatorManager;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        call_user_func_array([$this, 'init'], $options);
    }

    /**
     * @param WorkflowService        $workflowService
     * @param ReaderInterface        $metadataReader
     * @param ValidatorPluginManager $validatorManager
     */
    protected function init(WorkflowService $workflowService, ReaderInterface $metadataReader, ValidatorPluginManager $validatorManager)
    {
        $this->setWorkflowService($workflowService);
        $this->setMetadataReader($metadataReader);
        $this->setValidatorManager($validatorManager);
    }

    /**
     * Фабрика для создания событий
     *
     * @return WorkflowDispatchEventInterface
     */
    public function workflowDispatchEventFactory()
    {
        $className = $this->getWorkflowDispatchEventClassName();
        $r = new ReflectionClass($className);

        $instance = $r->newInstance();

        if (!$instance instanceof WorkflowDispatchEventInterface) {
            $errMsg = sprintf('Class %s not implement %s', $className, WorkflowDispatchEventInterface::class);
            throw new Exception\WorkflowDispatchEventException($errMsg);
        }

        return $instance;
    }

    /**
     * Диспетчирезация работы с workflow
     *
     * @param WorkflowDispatchEventInterface $event
     *
     * @return void
     */
    public function dispatch(WorkflowDispatchEventInterface $event)
    {
        $event->getMvcEvent()->setParam(static::WORKFLOW_DISPATCH_EVENT, $event);
        $event->setTarget($this);

        $metadataResult = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::LOAD_METADATA_EVENT, $event, function ($test) {
            return ($test instanceof MetadataInterface);
        });
        $metadata = $metadataResult->last();

        if (!$metadata instanceof MetadataInterface) {
            return;
        }

        $event->setMetadata($metadata);

        $prepareData = [];
        if ($metadata->isFlagRunPrepareData()) {
            $prepareDataResults = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::PREPARE_DATA_EVENT, $event);
            foreach ($prepareDataResults as $prepareDataResult) {
                if (is_array($prepareDataResult) || $prepareDataResult instanceof Traversable) {
                    $prepareData = ArrayUtils::merge($prepareData, $prepareDataResult);
                }
            }
        }
        $event->setPrepareData($prepareData);


        $flagRunWorkflow = $metadata->isWorkflowDispatch();
        if (true === $flagRunWorkflow) {
            $dispatchConditionsResults = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::CHECK_RUN_WORKFLOW_EVENT, $event);
            foreach ($dispatchConditionsResults as $dispatchConditionsResult) {
                if (false === $dispatchConditionsResult) {
                    $flagRunWorkflow = false;
                    break;
                }
            }
        }

        if (true === $flagRunWorkflow) {
            $runWorkflowResults = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::RUN_WORKFLOW_EVENT, $event, function ($result) {
                return ($result instanceof TransitionResultInterface);
            });

            $workflowResult = $runWorkflowResults->last();
            if ($workflowResult instanceof TransitionResultInterface) {
                $event->setWorkflowResult($workflowResult);
            }
        }
    }

    /**
     * Добавление подписчиков по умолчанию
     *
     */
    public function attachDefaultListeners()
    {
        $em = $this->getEventManager();
        $em->attach(WorkflowDispatchEventInterface::LOAD_METADATA_EVENT, [$this, 'onLoadMetadataHandler']);
        $em->attach(WorkflowDispatchEventInterface::PREPARE_DATA_EVENT, [$this, 'onPrepareDataHandler']);
        $em->attach(WorkflowDispatchEventInterface::CHECK_RUN_WORKFLOW_EVENT, [$this, 'onCheckRunWorkflowHandler']);
        $em->attach(WorkflowDispatchEventInterface::RUN_WORKFLOW_EVENT, [$this, 'onRunWorkflowHandler']);
    }

    /**
     * Получение метаданных
     *
     * @param WorkflowDispatchEventInterface $e
     *
     * @return MetadataInterface|null
     */
    public function onLoadMetadataHandler(WorkflowDispatchEventInterface $e)
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

        return $metadata;
    }

    /**
     * @param WorkflowDispatchEventInterface $e
     *
     * @return mixed|null
     */
    public function onPrepareDataHandler(WorkflowDispatchEventInterface $e)
    {
        $metadata = $e->getMetadata();

        $type = $metadata->getPrepareDataMethod();
        $handler = $metadata->getPrepareDataHandler();

        $prepareDataResult = null;
        switch ($type) {
            case 'method': {
                $mvcEvent = $e->getMvcEvent();
                $controller = $mvcEvent->getTarget();
                if (!$controller instanceof AbstractController) {
                    $errMsg = sprintf('Controller not implement %s', AbstractController::class);
                    throw new Exception\PrepareDataException($errMsg);
                }
                $callback = [$controller, $handler];
                if (!is_callable($callback)) {
                    $errMsg = sprintf('Invalid handler"%s"', $handler);
                    throw new Exception\PrepareDataException($errMsg);
                }
                $prepareDataResult = call_user_func($callback, $e);
                if (null === $prepareDataResult) {
                    $prepareDataResult = [];
                }

                break;
            }
            default: {
                $errMsg = sprintf('Preparing data for Workflow will fail. Unknown handler type %s.', $type);
                throw new Exception\PrepareDataException($errMsg);
            }
        }

        if (!is_array($prepareDataResult) && !$prepareDataResult instanceof Traversable) {
            $errMsg = 'Data preparation The results should be an array or Traversable';
            throw new Exception\PrepareDataException($errMsg);
        }


        return $prepareDataResult;
    }

    /**
     * Проверка, на то нужно ли запускать workflow
     *
     * @param WorkflowDispatchEventInterface $e
     *
     * @return boolean|null
     */
    public function onCheckRunWorkflowHandler(WorkflowDispatchEventInterface $e)
    {
        $metadata = $e->getMetadata();
        if (!$metadata->getFlagHasConditions()) {
            return null;
        }

        $conditions = $metadata->getConditions();

        $validatorManager = $this->getValidatorManager();

        /** @var ValidatorChain $validatorChains */
        $validatorChains = $validatorManager->get(ValidatorChain::class);

        $mvcEvent = $e->getMvcEvent();
        $controller = $mvcEvent->getTarget();
        if (!$controller instanceof AbstractController) {
            $controller = null;
        }


        foreach ($conditions as $condition) {
            $type = $condition->getType();
            $handler = $condition->getHandler();
            switch ($type) {
                case 'method': {
                    if (null === $controller) {
                        $errMsg = 'Controller not specified';
                        throw new Exception\CheckRunWorkflowEventException($errMsg);
                    }
                    $callback = [$controller, $handler];

                    /** @var ValidatorInterface $callbackValidator */
                    $callbackValidator = $validatorManager->get('callback', $callback);

                    $validatorChains->attach($callbackValidator);

                    break;
                }
                case 'service': {
                    $validatorParams = $condition->getParams();
                    /** @var ValidatorInterface $validator */
                    $validator = $validatorManager->get($handler, $validatorParams);

                    $validatorChains->attach($validator);

                    break;
                }
                default: {
                    $errMsg = sprintf('Preparing data for Workflow will fail. Unknown handler type %s.', $type);
                    throw new Exception\PrepareDataException($errMsg);
                }
            }
        }

        $flagRunWorkflow = $validatorChains->isValid($e);

        return $flagRunWorkflow;
    }

    /**
     * Запуск workflow
     *
     * @param WorkflowDispatchEventInterface $e
     *
     * @return TransitionResultInterface
     */
    public function onRunWorkflowHandler(WorkflowDispatchEventInterface $e)
    {
        $mvcEvent = $e->getMvcEvent();

        $routeMatch = $mvcEvent->getRouteMatch();
        if (!$routeMatch) {
            return null;
        }

        $metadata = $e->getMetadata();

        $workflowManagerNameParam = $metadata->getWorkflowManagerNameRouterParam();
        $workflowManagerName = $routeMatch->getParam($workflowManagerNameParam, null);
        if (null === $workflowManagerName) {
            $errMsg = sprintf('Param "%s" not found', $workflowManagerNameParam);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $workflowActionNameParam = $metadata->getWorkflowActionNameRouterParam();
        $workflowActionName = $routeMatch->getParam($workflowActionNameParam, null);
        if (null === $workflowActionName) {
            $errMsg = sprintf('Param "%s" not found', $workflowActionNameParam);
            throw new Exception\InvalidArgumentException($errMsg);
        }


        $workflowActivity = $metadata->getWorkflowRunType();
        $transientVars = $this->factoryTransientVars();

        $prepareData = $e->getPrepareData();
        foreach ($prepareData as $key => $value) {
            $transientVars[$key] = $value;
        }

        $result = null;
        switch ($workflowActivity) {
            case 'initialize': {
                $workflowNameParam = $metadata->getWorkflowNameRouterParam();
                $workflowName = $routeMatch->getParam($workflowNameParam, null);
                if (null === $workflowName) {
                    $errMsg = sprintf('Param "%s" not found', $workflowNameParam);
                    throw new Exception\InvalidArgumentException($errMsg);
                }

                $result = $this->getWorkflowService()->initialize($workflowManagerName, $workflowName, $workflowActionName, $transientVars);
                break;
            }
            case 'doAction': {
                $entryIdParam = $metadata->getEntryIdRouterParam();
                $entryId = $routeMatch->getParam($entryIdParam, null);
                if (null === $entryId) {
                    $errMsg = sprintf('Param "%s" not found', $entryIdParam);
                    throw new Exception\InvalidArgumentException($errMsg);
                }

                $result = $this->getWorkflowService()->doAction($workflowManagerName, $entryId, $workflowActionName, $transientVars);
                break;
            }
            default: {
                $errMsg = sprintf('Invalid activity %s', $workflowActivity);
                throw new Exception\InvalidArgumentException($errMsg);
            }
        }

        return $result;
    }

    /**
     *
     * @return TransientVarsInterface
     */
    public function factoryTransientVars()
    {
        $className = $this->getTransientVarsClassName();
        $r = new ReflectionClass($className);

        $instance = $r->newInstance();

        if (!$instance instanceof TransientVarsInterface) {
            $errMsg = sprintf('Class %s not implement %s', $className, TransientVarsInterface::class);
            throw new Exception\WorkflowDispatchEventException($errMsg);
        }

        return $instance;
    }


    /**
     * @return WorkflowService
     */
    public function getWorkflowService()
    {
        return $this->workflowService;
    }

    /**
     * @param WorkflowService $workflowService
     *
     * @return $this
     */
    public function setWorkflowService(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;

        return $this;
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
     * @return ValidatorPluginManager
     */
    public function getValidatorManager()
    {
        return $this->validatorManager;
    }

    /**
     * @param ValidatorPluginManager $validatorManager
     *
     * @return $this
     */
    public function setValidatorManager(ValidatorPluginManager $validatorManager)
    {
        $this->validatorManager = $validatorManager;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorkflowDispatchEventClassName()
    {
        return $this->workflowDispatchEventClassName;
    }

    /**
     * @param string $workflowDispatchEventClassName
     *
     * @return $this
     */
    public function setWorkflowDispatchEventClassName($workflowDispatchEventClassName)
    {
        $this->workflowDispatchEventClassName = (string)$workflowDispatchEventClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransientVarsClassName()
    {
        return $this->transientVarsClassName;
    }

    /**
     * @param string $transientVarsClassName
     *
     * @return $this
     */
    public function setTransientVarsClassName($transientVarsClassName)
    {
        $this->transientVarsClassName = (string)$transientVarsClassName;

        return $this;
    }
}
