<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams;

use OldTown\Workflow\ZF2\Dispatch\Annotation;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Reader\AbstractAnnotationReader;
use ReflectionClass;

/**
 * Class AnnotationReader
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams
 */
class AnnotationReader extends AbstractAnnotationReader
{
    /**
     * @var string
     */
    const READER_NAME = 'runWorkflowParamsMetadataReader';

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

        $r = new ReflectionClass($controllerClassName);
        $rMethod = $r->getMethod($actionMethod);

        $metadata = new Metadata();


        /** @var Annotation\WorkflowRouterMap|null $workflowRouterMapAnnotation */
        $workflowRouterMapAnnotation = $this->getReader()->getMethodAnnotation($rMethod, Annotation\WorkflowRouterMap::class);
        if ($workflowRouterMapAnnotation) {
            $metadata->setWorkflowManagerNameRouterParam($workflowRouterMapAnnotation->managerName);
            $metadata->setWorkflowActionNameRouterParam($workflowRouterMapAnnotation->actionName);
            $metadata->setWorkflowNameRouterParam($workflowRouterMapAnnotation->name);
            $metadata->setEntryIdRouterParam($workflowRouterMapAnnotation->entryId);
        }

        return $metadata;
    }
}
