# Жизненный цикл диспетчирезации

## Обработка события DISPATCH контроллера

Модуль реагирует на любое событие DISPATCH контроллера. В случае если в приложение произошел запуск диспетчирезации контроллера,
не важно в результате http запроса, запуска из консоли или через внутренние механизмы zf,  инициируется процесс 
проверяющий нужно ли использовать workflow.

Диспетчирезация workflow реализована в сервисе \OldTown\Workflow\ZF2\Dispatch\Dispatcher\Dispatcher.

## Передача данных между слоями
Диспетчер устанавливат в объект \Zend\Mvc\MvcEvent, параметр workflowDispatchEvent. Значеним этого параметра является
объект имплементирующий \OldTown\Workflow\ZF2\Dispatch\Dispatcher\WorkflowDispatchEventInterface.

Из объекта реализующего  WorkflowDispatchEventInterface можно получить:

Метод                             |Описание      
----------------------------------|------------------
getMetadata                       | Метаданные необходимые для запуска wf
getPrepareData                    | Результаты работы слоя, подготавливающие данные для wf
getRunWorkflowParam               | Параметры для запуска wf
getWorkflowResult                 | Результаты работы wf


## Событийная модель сервиса

Имя события                            |Описание      
---------------------------------------|------------------
workflow.dispatch.metadata             | Получение метаданных для вызываемого action контроллера
workflow.dispatch.prepareData          | Запуск слоя, подготавливающего данные для wf
workflow.dispatch.checkRunWorkflow     | Проверка нужно ли запускать wf
workflow.dispatch.metadataWorkflowToRun| Получить данные для запуска wf(managerName,actionName, а также entryId или workflowName)
workflow.dispatch.run                  | Запуск wf

### Событие workflow.dispatch.metadata

Обработчик реализующий обработку данного события, должен возвращать объект имплементирующий \OldTown\Workflow\ZF2\Dispatch\Metadata\Storage\MetadataInterface.
Модуль содержит поддержку метаданных на основе анотаций. При необходимости, добавить новый тип метаданных, нужно
подписаться на данное событие, реализовать получение метаданных на основе своего адаптера и вернуть соответствующий результат.

### Событие workflow.dispatch.prepareData

Подготовка данных для бизне слогики. Результатом работы должен быть массив, либо объект реализующий Traversable.
Обработчиков может быть любое число. Результаты объеденяются.

###  Событие  workflow.dispatch.checkRunWorkflow

Если необходимо контролировать процесс запуска workflow, то можно подписаться на данное событие. В случае если хотя бы
один обработчик события возвращает false, то wf запущенна не будет.

### Собыите workflow.dispatch.metadataWorkflowToRun

Для запуска нового процесса workflow либо для изменения состояния уже запущенного процесса необходимо знать:

Параметр    |Назначение               |Описание
------------|-------------------------|--------
runType     |Тип действия             | Тип действия initialize(новый процесс) или doAction (выполнить действие в существующем процессе)
managerName |Имя менеджера wf         | Имя менеджера workflow
actionName  |Имя выполняемого действия| Имя действия доступное на текущем шаге. Действие осуществляет переход в другой шаг.
workflowName|Имя workflow             | Ссылка на xml файл workflow. Парамет нужен если мы инициализируем новый процесс (Тип действия initialize)
entryId     |id процесса wf           | Идентификатор уже запущенного процесса wf (Тип действия doAction)

При бросание события workflow.dispatch.metadataWorkflowToRun ожидается что один из обработчиков вернет объект имплементирующий
\OldTown\Workflow\ZF2\Dispatch\Dispatcher\RunWorkflowParamInterface. Данный объект содержит все необходимы данные для запуска wf.

Модуль реализует поддержку получения метаданных для запуска workflow:
* На основе данных из роутера - \OldTown\Workflow\ZF2\Dispatch\RunParamsHandler\RouteHandler

###  Событие  workflow.dispatch.run

Обработчику делегируется запуск wf. Результатом работы обработчика должен быть объект имплементирующий \OldTown\Workflow\ZF2\ServiceEngine\Workflow\TransitionResultInterface.


