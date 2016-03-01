<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams;

use OldTown\Workflow\ZF2\Dispatch\Metadata\MetadataInterface as BaseMetadata;

/**
 * Interface MetadataInterface
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams
 */
interface MetadataInterface extends BaseMetadata
{
    /**
     * Имя параметра роуетра, значение которого определяет имя используемого менеджера workflow
     *
     * @var string
     */
    const WORKFLOW_MANAGER_NAME_ROUTER_PARAM = 'workflowManagerName';

    /**
     * Имя параметра роуетра, значение которого определяет псевдоним используемого менеджера workflow
     *
     * @var string
     */
    const WORKFLOW_MANAGER_ALIAS_ROUTER_PARAM = 'workflowManagerAlias';

    /**
     *  Имя параметра роуетра, значение которого определяет имя действия в workflow
     *
     * @var string
     */
    const WORKFLOW_ACTION_NAME_ROUTER_PARAM = 'workflowActionName';

    /**
     *  Имя параметра роуетра, значение которого определяет имя workflow
     *
     * @var string
     */
    const WORKFLOW_NAME_ROUTER_PARAM = 'workflowName';

    /**
     *  Имя параметра роуетра, значение которого определяет id процесса workflow
     *
     * @var string
     */
    const ENTRY_ID_ROUTER_PARAM = 'entryId';


    /**
     * Имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @return string
     */
    public function getWorkflowManagerNameRouterParam();

    /**
     * Устаналвивает имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @param string $workflowManagerNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowManagerNameRouterParam($workflowManagerNameRouterParam);

    /**
     * Имя параметра в роуетере, значение которого - имя псевданима менеджера workflow
     *
     * @return string
     */
    public function getWorkflowManagerAliasRouterParam();

    /**
     * Устаналвивает имя параметра в роуетере, значение которого - имя псевданима менеджера workflow
     *
     * @param string $workflowManagerNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowManagerAliasRouterParam($workflowManagerNameRouterParam);

    /**
     * Имя параметра в роуетере, значение которого имя вызываемого действия
     *
     * @return string
     */
    public function getWorkflowActionNameRouterParam();

    /**
     * Устанавливает имя параметра в роуетере, значение которого - имя вызываемого действия
     *
     * @param string $workflowActionNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowActionNameRouterParam($workflowActionNameRouterParam);

    /**
     * Имя параметра в роуетере, значение которого имя workflow
     *
     * @return string
     */
    public function getWorkflowNameRouterParam();

    /**
     * Устанавливает имя параметра в роуетере, значение которого имя workflow
     *
     * @param string $workflowNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowNameRouterParam($workflowNameRouterParam);

    /**
     * Имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @return string
     */
    public function getEntryIdRouterParam();

    /**
     * Устанавливает имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @param string $entryIdRouterParam
     *
     * @return $this
     */
    public function setEntryIdRouterParam($entryIdRouterParam);
}
