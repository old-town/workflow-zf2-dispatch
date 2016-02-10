<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Reader;

use OldTown\Workflow\ZF2\Dispatch\Metadata\DispatchConditionMetadata;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader as DoctrineAnnotationsReaderInterface;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Metadata;
use OldTown\Workflow\ZF2\Dispatch\Annotation as Annotation;
use OldTown\Workflow\ZF2\Dispatch\Annotation\Condition;


/**
 * Class AnnotationReader
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Reader
 */
class AnnotationReader implements ReaderInterface
{
    /**
     * @var string
     */
    const READER_NAME = 'annotation';

    /**
     * @var DoctrineAnnotationsReaderInterface
     */
    protected $reader;

    /**
     * Кеш загруженных метаданных
     *
     * @var MetadataInterface[]
     */
    protected $classAnnotations = [];

    /**
     *
     * @throws Exception\AnnotationReaderException
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Иницилазия
     *
     * @return void
     * @throws Exception\AnnotationReaderException
     */
    protected function init()
    {
        try {
            AnnotationRegistry::registerLoader(function ($class) {
                return (bool) class_exists($class);
            });
        } catch (\Exception $e) {
            throw new Exception\AnnotationReaderException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Получение метаданных для action контроллера
     *
     * @param string  $controllerClassName
     * @param  string $actionMethod
     *
     * @return MetadataInterface
     *
     * @throws \OldTown\Workflow\ZF2\Dispatch\Metadata\Exception\InvalidMetadataException
     * @throws Exception\AnnotationReaderException
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










        return $metadata;
    }

    /**
     * @return DoctrineAnnotationsReaderInterface
     */
    public function getReader()
    {
        if ($this->reader) {
            return $this->reader;
        }

        $this->reader = new DoctrineAnnotationReader();

        return $this->reader;
    }

    /**
     * @param DoctrineAnnotationsReaderInterface $reader
     *
     * @return $this
     */
    public function setReader(DoctrineAnnotationsReaderInterface $reader)
    {
        $this->reader = $reader;

        return $this;
    }
}
