<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Reader;

use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader as DoctrineAnnotationsReaderInterface;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use OldTown\Workflow\ZF2\Dispatch\Metadata\ReaderInterface;

/**
 * Class AbstractReader
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Reader
 */
abstract class AbstractAnnotationReader implements ReaderInterface
{
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
