<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch;

use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\Dispatch\AnnotationReader as DispatchAnnotationReader;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams\AnnotationReader as RunParamsAnnotationReader;


$config = [
    'workflow_zf2_dispatch'                 => [
        /** Имя адапетра для чтения метданных необходимых для начали цикла диспетчиризация wf */
        ModuleOptions::DISPATCH_METADATA_READER            => DispatchAnnotationReader::READER_NAME,
        /** Имя адаптера для чтения метаданных необходимых для запуска wf */
        ModuleOptions::RUN_WORKFLOW_PARAMS_METADATA_READER => RunParamsAnnotationReader::READER_NAME,
        /** Имя используемого логгера */
        ModuleOptions::LOG_NAME => null
    ],
    'workflow_zf2_dispatch_metadata_reader' => [
        'invokables' => [
            DispatchAnnotationReader::class  => DispatchAnnotationReader::class,
            RunParamsAnnotationReader::class => RunParamsAnnotationReader::class
        ],
        'aliases'    => [
            DispatchAnnotationReader::READER_NAME  => DispatchAnnotationReader::class,
            RunParamsAnnotationReader::READER_NAME => RunParamsAnnotationReader::class
        ]
    ]
];

return array_merge_recursive(
    include __DIR__ . '/serviceManager.config.php',
    include __DIR__ . '/router.config.php',
    include __DIR__ . '/validator.config.php',
    $config
);