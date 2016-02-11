<?php
/**
 * @link    https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch;

use OldTown\Workflow\ZF2\Dispatch\Options\ModuleOptions;
use OldTown\Workflow\ZF2\Dispatch\Metadata\Reader\AnnotationReader;
use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataReaderManager;

$config = [
    'workflow_zf2_dispatch' => [
        /** Имя адапетра для чтения метданных */
        ModuleOptions::METADATA_READER                    => AnnotationReader::READER_NAME,
        /** Имя менеджера для работы с адаптерами чтения метаданных */
        ModuleOptions::METADATA_READER_MANAGER_CLASS_NAME => MetadataReaderManager::class
    ]
];

return array_merge_recursive(
    include __DIR__ . '/serviceManager.config.php',
    include __DIR__ . '/router.config.php',
    include __DIR__ . '/validator.config.php',
    $config
);