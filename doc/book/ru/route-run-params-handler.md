#Получение параметров запуска wf из роутера

В модуле реализован обработчик события [workflow.dispatch.metadataWorkflowToRun](./life-cycle.md#workflowdispatchmetadataworkflowtorun).

Это обработчик позволяет получить параметры для запуска wf из роуетера.

#Описание работы RouteHandler

Для запуска workflow необходимы следующие данные:

* managerName - имя менеджера workflow
* actionName - имя выполняемого действия (тег action в xml файле workflow)

Имя менеджера workflow может быть получено на основе псевдонима (managerAlias).

Для запуска нового процесса workflow:
* name - имя зарегестрированного workflow (по сути ссылка на xml файл workflow, регистрируется в менеджере workflow)

Для изменения состояния уже запущенного процесса:
* entryId - id запущенного процесса workflow

Все эти данные должны передоваться в качестве параметров роутера. По умолчанию, ожидается что параметры роутера будут
иметь следующие значения

Параметр          |Имя параметра роутера|Описание
------------------|---------------------|----------------------------------
managerName       |workflowManagerName  |имя менеджера workflow
managerAlias      |workflowManagerAlias |псевдоним менеджера workflow
actionName        |workflowActionName   |имя выполняемого действия
name              |workflowName         |имя зарегестрированного workflow
entryId           |entryId              |id запущенного процесса

Если возникает потребность, в других именах параметров роутера, то можно использовать анотацию.

```php
@WFD\RunWorkflowParamFromRoute(managerName="workflowManagerCustomName", actionName="workflowActionCustomName", name="workflowCustomName", entryId="customEntryId")
```
Для использования анотации необходимо убидеться что подключено в use соответствующее пространство имен:

```php
use OldTown\Workflow\ZF2\Dispatch\Annotation as WFD;
```

Алгоритм работы \OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler

* Проверяется что удалось получить workflowManagerName и workflowActionName. Если нет то прекращаем работу
* Если тип действия workflow - initialize и удалось из значения параметров роуета получить workflowName.  Отдаем данные для запуска wf
* Если тип действия workflow - doAction - то бросаем событие workflow.dispatch.resolveEntryId(\OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler\ResolveEntryIdEvent) 
* В модуле реализован обработчик workflow.dispatch.resolveEntryId. Если удалось получить из значения параметра роутера entryId, возвращаем его
* Как только хотя бы один из обработчиков вернул entryId, отдаем данные для запуска wf, в противном случае завершаем работу.

В случае если необходимо получить значение entryId, не на основе явно указанного значения через роутера, а иным способом.
Например на основе id сущности привязанной к процессу, необходимо реализовать обработку собтыия workflow.dispatch.resolveEntryId,
для сервиса \OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler

