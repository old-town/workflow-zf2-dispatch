<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch;

use OldTown\Workflow\ZF2\Dispatch\Annotation;
use OldTown\Workflow\ZF2\Dispatch\Annotation\Condition;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Reader\AbstractAnnotationReader;

/**
 * Class AnnotationReader
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Reader
 */
class AnnotationReader extends AbstractAnnotationReader
{
    /**
     * @var string
     */
    const READER_NAME = 'dispatchAnnotation';

    /**
     * Получение метаданных для action контроллера
     *
     * @param string  $controllerClassName
     * @param  string $actionMethod
     *
     * @return MetadataInterface
     * @throws Exception\AnnotationReaderException
     *
     * @throws Exception\InvalidMetadataException
     *
     */
    public function loadMetadataForAction($controllerClassName, $actionMethod)
    {
        $key = $controllerClassName . '_' . $actionMethod;
        if (array_key_exists($key, $this->classAnnotations)) {
            return $this->classAnnotations[$key];
        }

        $r = new \ReflectionClass($controllerClassName);
        $rMethod = $r->getMethod($actionMethod);

        $metadata = new Metadata();


        /** @var Annotation\WorkflowDispatch|null $workflowDispatchAnnotation */
        $workflowDispatchAnnotation = $this->getReader()->getMethodAnnotation($rMethod, Annotation\WorkflowDispatch::class);
        if (null !== $workflowDispatchAnnotation) {
            $metadata->setWorkflowDispatch($workflowDispatchAnnotation->enabled);
            $metadata->setWorkflowRunType($workflowDispatchAnnotation->activity);
        }

        /** @var Annotation\PrepareData|null $prepareDataAnnotation */
        $prepareDataAnnotation = $this->getReader()->getMethodAnnotation($rMethod, Annotation\PrepareData::class);
        if ($prepareDataAnnotation) {
            $metadata->setFlagRunPrepareData($prepareDataAnnotation->enabled);
            $metadata->setPrepareDataMethod($prepareDataAnnotation->type);
            $metadata->setPrepareDataHandler($prepareDataAnnotation->handler);
        }


        /** @var Annotation\DispatchConditions|null $dispatchConditionsAnnotation */
        $dispatchConditionsAnnotation = $this->getReader()->getMethodAnnotation($rMethod, Annotation\DispatchConditions::class);
        if ($dispatchConditionsAnnotation && is_array($dispatchConditionsAnnotation->conditions) && count($dispatchConditionsAnnotation->conditions) > 0) {
            $metadata->setFlagHasConditions(true);
            foreach ($dispatchConditionsAnnotation->conditions as $condition) {
                if (!$condition instanceof Condition) {
                    $errMsg = 'Invalid condition annotation';
                    throw new Exception\AnnotationReaderException($errMsg);
                }
                $conditionMetadata = new DispatchConditionMetadata($condition->type, $condition->handler, $condition->params);

                $metadata->addConditions($conditionMetadata);
            }
        }
//
//        /** @var Annotation\WorkflowRouterMap|null $workflowRouterMapAnnotation */
//        $workflowRouterMapAnnotation = $this->getReader()->getMethodAnnotation($rMethod, Annotation\WorkflowRouterMap::class);
//        if ($workflowRouterMapAnnotation) {
//            $metadata->setWorkflowManagerNameRouterParam($workflowRouterMapAnnotation->managerName);
//            $metadata->setWorkflowActionNameRouterParam($workflowRouterMapAnnotation->actionName);
//            $metadata->setWorkflowNameRouterParam($workflowRouterMapAnnotation->name);
//            $metadata->setEntryIdRouterParam($workflowRouterMapAnnotation->entryId);
//        }
//
//        $metadata->validate();

        return $metadata;
    }
}
