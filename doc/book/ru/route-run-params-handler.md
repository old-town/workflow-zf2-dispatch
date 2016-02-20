#Получение параметров запуска wf из роутера

В модуле реализован обработчик события [workflow.dispatch.metadataWorkflowToRun](./life-cycle.md#workflow.dispatch.metadataWorkflowToRun)


Для запуска workflow необходимы следующие данные:

* managerName - имя менеджера workflow
* actionName - имя выполняемого действия (тег action в xml файле workflow)

Для запуска нового процесса workflow:
* name - имя зарегестрированного workflow (по сути ссылка на xml файл workflow, регистрируется в менеджере workflow)

Для изменения состояния уже запущенного процесса:
* entryId - id запущенного процесса workflow

Все эти данные должны передоваться в качестве параметров роутера. По умолчанию, ожидается что параметры роутера будут
иметь следующие значения

Параметр          |Имя параметра роутера|Описание
------------------|---------------------|----------------------------------
managerName       |workflowManagerName  |имя менеджера workflow
actionName        |workflowActionName   |имя выполняемого действия
name              |workflowName         |имя зарегестрированного workflow
entryId           |entryId              |id запущенного процесса

Если возникает потребность, в других именах параметров роутера, то можно использовать анотацию.

```php
@WFD\RunWorkflowParamFromRoute(managerName="workflowManagerCustomName", actionName="workflowActionCustomName", name="workflowCustomName", entryId="customEntryId")
```
