<?php
/**
 * @link https://github.com/old-town/workflow-zf2-dispatch
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams;

/**
 * Class Metadata
 *
 * @package OldTown\Workflow\ZF2\Dispatch\Metadata\Target\RunParams
 */
class Metadata implements MetadataInterface
{
    /**
     * Имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @var string
     */
    protected $workflowManagerNameRouterParam = self::WORKFLOW_MANAGER_NAME_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого имя вызываемого действия
     *
     * @var string
     */
    protected $workflowActionNameRouterParam = self::WORKFLOW_ACTION_NAME_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого имя workflow
     *
     * @var string
     */
    protected $workflowNameRouterParam = self::WORKFLOW_NAME_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @var string
     */
    protected $entryIdRouterParam = self::ENTRY_ID_ROUTER_PARAM;

    /**
     * Имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @return string
     */
    public function getWorkflowManagerNameRouterParam()
    {
        return $this->workflowManagerNameRouterParam;
    }

    /**
     * Устаналвивает имя параметра в роуетере, значение которого - имя менеджера workflow
     *
     * @param string $workflowManagerNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowManagerNameRouterParam($workflowManagerNameRouterParam)
    {
        $this->workflowManagerNameRouterParam = $workflowManagerNameRouterParam;

        return $this;
    }

    /**
     * Имя параметра в роуетере, значение которого имя вызываемого действия
     *
     * @return string
     */
    public function getWorkflowActionNameRouterParam()
    {
        return $this->workflowActionNameRouterParam;
    }

    /**
     * Устанавливает имя параметра в роуетере, значение которого - имя вызываемого действия
     *
     * @param string $workflowActionNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowActionNameRouterParam($workflowActionNameRouterParam)
    {
        $this->workflowActionNameRouterParam = $workflowActionNameRouterParam;

        return $this;
    }

    /**
     * Имя параметра в роуетере, значение которого имя workflow
     *
     * @return string
     */
    public function getWorkflowNameRouterParam()
    {
        return $this->workflowNameRouterParam;
    }

    /**
     * Устанавливает имя параметра в роуетере, значение которого имя workflow
     *
     * @param string $workflowNameRouterParam
     *
     * @return $this
     */
    public function setWorkflowNameRouterParam($workflowNameRouterParam)
    {
        $this->workflowNameRouterParam = $workflowNameRouterParam;

        return $this;
    }

    /**
     * Имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @return string
     */
    public function getEntryIdRouterParam()
    {
        return $this->entryIdRouterParam;
    }

    /**
     * Устанавливает имя параметра в роуетере, значение которого id запущенного процесса
     *
     * @param string $entryIdRouterParam
     *
     * @return $this
     */
    public function setEntryIdRouterParam($entryIdRouterParam)
    {
        $this->entryIdRouterParam = (string)$entryIdRouterParam;

        return $this;
    }
}
