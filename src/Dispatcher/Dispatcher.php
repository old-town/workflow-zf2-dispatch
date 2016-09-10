<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Dispatcher;

use OldTown\Workflow\TransientVars\TransientVarsInterface;
use Zend\EventManager\EventManagerAwareTrait;
use OldTown\Workflow\ZF2\ServiceEngine\Workflow as WorkflowService;
use OldTown\Workflow\ZF2\Dispatch\Metadata\ReaderInterface;
use ReflectionClass;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch\MetadataInterface;
use Zend\Mvc\Controller\AbstractController;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\ValidatorPluginManager;
use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorInterface;
use OldTown\Workflow\TransientVars\BaseTransientVars;
use OldTown\Workflow\ZF2\ServiceEngine\Workflow\TransitionResultInterface;
use Zend\Log\LoggerInterface;


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
     * Логер
     *
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $initOptions = [
            array_key_exists('workflowService', $options) ? $options['workflowService'] : null,
            array_key_exists('metadataReader', $options) ? $options['metadataReader'] : null,
            array_key_exists('validatorManager', $options) ? $options['validatorManager'] : null,
            array_key_exists('log', $options) ? $options['log'] : null,
        ];
        call_user_func_array([$this, 'init'], $initOptions);
    }

    /**
     * @param WorkflowService        $workflowService
     * @param ReaderInterface        $metadataReader
     * @param ValidatorPluginManager $validatorManager
     * @param LoggerInterface        $log
     */
    protected function init(WorkflowService $workflowService, ReaderInterface $metadataReader, ValidatorPluginManager $validatorManager, LoggerInterface $log)
    {
        $this->setWorkflowService($workflowService);
        $this->setMetadataReader($metadataReader);
        $this->setValidatorManager($validatorManager);
        $this->setLog($log);
    }

    /**
     * Диспетчирезация работы с workflow
     *
     * @param WorkflowDispatchEventInterface $event
     *
     * @return void
     *
     * @throws Exception\RunWorkflowParamException
     */
    public function dispatch(WorkflowDispatchEventInterface $event)
    {
        $event->getMvcEvent()->setParam(static::WORKFLOW_DISPATCH_EVENT, $event);
        $event->setTarget($this);


        $this->getLog()->debug(
            sprintf(
                'Event: %s. Getting metadata to start scheduling cycle workflow',
                WorkflowDispatchEventInterface::LOAD_METADATA_EVENT
            )
        );
        $metadataResult = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::LOAD_METADATA_EVENT, $event, function ($test) {
            return ($test instanceof MetadataInterface);
        });
        $metadata = $metadataResult->last();

        if (!$metadata instanceof MetadataInterface) {
            $this->getLog()->info('No metadata to start scheduling workflow.');
            return;
        }

        $event->setMetadata($metadata);

        $prepareData = [];
        if ($metadata->isFlagRunPrepareData()) {
            $this->getLog()->info(
                sprintf(
                    'Event: %s. Preparing data to run Workflow',
                    WorkflowDispatchEventInterface::PREPARE_DATA_EVENT
                )
            );
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
            $this->getLog()->info(
                sprintf(
                    'Event: %s. Checking the workflow start conditions',
                    WorkflowDispatchEventInterface::CHECK_RUN_WORKFLOW_EVENT
                )
            );
            $dispatchConditionsResults = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::CHECK_RUN_WORKFLOW_EVENT, $event);
            foreach ($dispatchConditionsResults as $dispatchConditionsResult) {
                if (false === $dispatchConditionsResult) {
                    $this->getLog()->info('Launch Workflow canceled.');
                    $flagRunWorkflow = false;
                    break;
                }
            }
        }

        if (true === $flagRunWorkflow) {
            $this->getLog()->info(
                sprintf(
                    'Event: %s. Getting metadata workflow to run',
                    WorkflowDispatchEventInterface::METADATA_WORKFLOW_TO_RUN_EVENT
                )
            );
            $runWorkflowParamResults = $this->getEventManager()->trigger(WorkflowDispatchEventInterface::METADATA_WORKFLOW_TO_RUN_EVENT, $event, function ($result) {
                return ($result instanceof RunWorkflowParamInterface);
            });
            $runWorkflowParamResult = $runWorkflowParamResults->last();

            if (!$runWorkflowParamResult instanceof RunWorkflowParamInterface) {
                $errMsg = 'There is no evidence to launch workflow';
                throw new Exception\RunWorkflowParamException($errMsg);
            }
            $event->setRunWorkflowParam($runWorkflowParamResult);

            $this->getLog()->info(
                sprintf(
                    'Event: %s. Starting the workflow',
                    WorkflowDispatchEventInterface::RUN_WORKFLOW_EVENT
                )
            );
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
            $this->getLog()->notice(
                'Unable to retrieve the metadata for scheduling workflow. No controller object in the property "target" MvcEvent.'
            );
            return null;
        }

        $routeMatch = $mvcEvent->getRouteMatch();
        if (!$routeMatch) {
            $this->getLog()->notice(
                'Unable to retrieve the metadata for scheduling workflow. Do not set RouteMatch'
            );
            return null;
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $actionMethod = AbstractController::getMethodFromAction($action);

        if (!method_exists($controller, $actionMethod)) {
            $this->getLog()->notice(
                sprintf(
                    'Unable to retrieve the metadata for scheduling workflow. There is no action(%s) in controller(%s)',
                    $actionMethod,
                    get_class($controller)
                )
            );
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
     *
     * @throws Exception\PrepareDataException
     */
    public function onPrepareDataHandler(WorkflowDispatchEventInterface $e)
    {
        $metadata = $e->getMetadata();

        $type = $metadata->getPrepareDataMethod();
        $handler = $metadata->getPrepareDataHandler();

        $prepareDataResult = null;

        if ('method' === $type) {
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
        } else {
            $errMsg = sprintf('Preparing data for Workflow will fail. Unknown handler type %s.', $type);
            throw new Exception\PrepareDataException($errMsg);
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
     *
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     * @throws  Exception\PrepareDataException
     */
    public function onCheckRunWorkflowHandler(WorkflowDispatchEventInterface $e)
    {
        $metadata = $e->getMetadata();
        if (!$metadata->getFlagHasConditions()) {
            return null;
        }

        try {
            $conditions = $metadata->getConditions();

            $validatorManager = $this->getValidatorManager();

            /** @var ValidatorChain $validatorChains */
            $validatorChains = $validatorManager->get(ValidatorChain::class);
        } catch (\Exception $e) {
            throw new Exception\PrepareDataException($e->getMessage(), $e->getCode(), $e);
        }



        $mvcEvent = $e->getMvcEvent();
        $controller = $mvcEvent->getTarget();
        if (!$controller instanceof AbstractController) {
            $controller = null;
        }


        foreach ($conditions as $condition) {
            try {
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
            } catch (\Exception $e) {
                throw new Exception\PrepareDataException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $validatorChains->isValid($e);
    }

    /**
     * Запуск workflow
     *
     * @param WorkflowDispatchEventInterface $e
     *
     * @return TransitionResultInterface
     *
     *
     * @throws Exception\InvalidArgumentException
     * @throws  Exception\WorkflowDispatchEventException
     * @throws \OldTown\Workflow\ZF2\ServiceEngine\Exception\InvalidInitializeWorkflowEntryException
     * @throws \OldTown\Workflow\ZF2\ServiceEngine\Exception\DoActionException
     */
    public function onRunWorkflowHandler(WorkflowDispatchEventInterface $e)
    {
        $runWorkflowParam = $e->getRunWorkflowParam();
        $runWorkflowParam->valid();

        $transientVars = $this->factoryTransientVars();

        $prepareData = $e->getPrepareData();
        foreach ($prepareData as $key => $value) {
            $transientVars[$key] = $value;
        }

        $runWorkflowParam = $e->getRunWorkflowParam();
        $runWorkflowParam->valid();

        $workflowManagerName = $runWorkflowParam->getManagerName();
        $workflowActionName = $runWorkflowParam->getActionName();

        $workflowActivity = $runWorkflowParam->getRunType();
        $result = null;
        switch ($runWorkflowParam->getRunType()) {
            case RunWorkflowParamInterface::WORKFLOW_RUN_INITIALIZE: {
                $workflowName = $runWorkflowParam->getWorkflowName();
                $result = $this->getWorkflowService()->initialize($workflowManagerName, $workflowName, $workflowActionName, $transientVars);
                break;
            }
            case RunWorkflowParamInterface::WORKFLOW_RUN_TYPE_DO_ACTION: {
                $entryId = $runWorkflowParam->getEntryId();

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
     *
     * @throws Exception\WorkflowDispatchEventException
     */
    public function factoryTransientVars()
    {
        $className = $this->getTransientVarsClassName();

        return $this->factoryClassName($className, TransientVarsInterface::class);
    }

    /**
     * Фабрика для создания событий
     *
     * @return WorkflowDispatchEventInterface
     *
     * @throws Exception\WorkflowDispatchEventException
     */
    public function workflowDispatchEventFactory()
    {
        $className = $this->getWorkflowDispatchEventClassName();

        return $this->factoryClassName($className, WorkflowDispatchEventInterface::class);
    }

    /**
     * Создает экземпляр класса и проверяет то что созданный объект имплементирует заданный интерфейс
     *
     * @param $className
     * @param $interface
     *
     * @return mixed
     *
     * @throws  Exception\WorkflowDispatchEventException
     */
    protected function factoryClassName($className, $interface)
    {
        $r = new ReflectionClass($className);

        $instance = $r->newInstance();

        if (!$instance instanceof $interface) {
            $errMsg = sprintf('Class %s not implement %s', $className, $interface);
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
}
