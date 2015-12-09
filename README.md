# workflow-zf2-serviceEngine

[![Build Status](https://secure.travis-ci.org/old-town/workflow-zf2-serviceEngine.svg?branch=dev)](https://secure.travis-ci.org/old-town/workflow-zf2-serviceEngine)
[![Coverage Status](https://coveralls.io/repos/old-town/workflow-zf2-serviceEngine/badge.svg?branch=dev&service=github)](https://coveralls.io/github/old-town/workflow-zf2-serviceEngine?branch=dev)

# Функционал модуля

Модуль добавляет возможность использовать сервисы ZF2 в workflow. 

Библиотека [old-town-workflow](https://github.com/old-town/old-town-workflow) является портом проекта [OSWorkflow](https://bitbucket.org/opensymphony/osworkflow/src/2d12ee26481f4ba6c9ff35fb3f118191f6d62035?at=default).
Библиотека является самодостаточной, и не нуждается в сторонних framework. При этом работа workflow построенна таким образом,
что функции(Function), условия(Condition), валидаторы(Validator), регистры(Register) - находятся вне библиотеки. 

Для взаимодействия workflow с приложением, предусмотренны TypeResolver. По своей сути TypeResolver это фабрика, которая
на основе типа и аргументов может создавать функции(Function), условия(Condition), валидаторы(Validator), регистры(Register).

В данном модуле реализованы дополнительные TypeResolver:

* [ChainTypeResolver](./src/TypeResolver/ChainTypeResolver.php) - резолвер позволяет построить цепочку из множества вложенных резолверов
* [ServiceTypeResolver](./src/TypeResolver/ServiceTypeResolver.php) - резолвер добавляет поддержку новго типа "service"(сервисы ZF2)

При создание нового объекта менеджера workflow, модулем [old-town/workflow-zf2](https://github.com/old-town/workflow-zf2)
бросается событие workflow.manager.create. Обработка этого события осуществляется [WorkflowDispatchListener](./src/Listener/WorkflowDispatchListener.php).
Обработчик:

* Создает [ChainTypeResolver](./src/TypeResolver/ChainTypeResolver.php)
* Добавляет в него TypeResolver установленный в менеджер workflow.
* [WorkflowDispatchListener](./src/Listener/WorkflowDispatchListener.php) с помощью своего EventManager'а бросает собыите inject.workflow.type.resolver,
обработчики данного события должны вернуть объект TypeResolver, который в последствие будет добавлен в ChainTypeResolver
* Обработчки сам подписан на событие inject.workflow.type.resolver, в обработчике возвращается [ServiceTypeResolver](./src/TypeResolver/ServiceTypeResolver.php)
* Устанавливает в workflow в качестве резолвера [ChainTypeResolver](./src/TypeResolver/ChainTypeResolver.php)

Таким образом, при попытки выполнить функцию(Function), валидатор(Validator), условие(Condition) или воспользоваться регистром(Register),
будет произведен обход ChainTypeResolver, где сначала будет отработает резовлер с наивысшим приоритетом(в данном случае ServiceTypeResolver),
и в самую поселеднию очередь резовлер по умолчанию.

# Сервисы
Для работы с сервисам использвется [Manager](./src/Service/Manager.php). Это стандартный PluginManager ZF2. Все сервисы
зарегестрированные в данном менеджере можно использовать в workflow





