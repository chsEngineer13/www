<?php
namespace Engsurvey\Frontend;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Text;

class Module implements ModuleDefinitionInterface
{
    /**
     * Регистрирует автозагрузчик, связанный с модулем.
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $config = $di->get("config");

        $libraryDir = $config->app->libraryDir;

        $loader->registerNamespaces(
            [
                "Engsurvey" => $libraryDir,
                "Engsurvey\\Frontend" => __DIR__ . "/",
                "Engsurvey\\Frontend\\Controllers" => __DIR__ . "/controllers/",
                "Engsurvey\\Frontend\\Forms" => __DIR__ . "/forms/",
            ]
        );

        $loader->register();
    }


    /**
     * Регистрирует сервисы, связанные с модулем.
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        $di->set(
            "dispatcher", 
            function() {
                // Создание менеджера событий.
                $eventsManager = new EventsManager();
                
                // Camelize actions.
                $eventsManager->attach(
                    "dispatch:beforeDispatchLoop",
                    function (Event $event, $dispatcher) {
                        $dispatcher->setActionName(
                            Text::camelize($dispatcher->getActionName())
                        );
                    }
                );
            
                // Обработка исключений "Не найдено".
                $eventsManager->attach(
                    "dispatch:beforeException", 
                    function($event, $dispatcher, $exception) {
                        switch ($exception->getCode()) {
                            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                                $dispatcher->forward(
                                    [
                                        "module" => "frontend",
                                        "controller" => "errors",
                                        "action" => "error404",
                                    ]
                                );

                            return false;
                        }
                    }
                );

                $dispatcher = new Dispatcher();
                
                // Задание пространство имен по умолчанию для диспетчера.
                $dispatcher->setDefaultNamespace("Engsurvey\\Frontend\\Controllers");
                
                // Прикрепление менеджер событий к диспетчеру.
                $dispatcher->setEventsManager($eventsManager);
                
                return $dispatcher;
            }
        );

        
        /**
         * Настройка компонента просмотра.
         */
        $di->set(
            "view", 
            function () {
                $view = new View();
                $view->setDI($this);
                $view->setViewsDir(__DIR__ . "/views/");

                // Отключение генерации представления для главного макета.
                $view->disableLevel(
                    [
                        View::LEVEL_MAIN_LAYOUT => true,
                    ]
                );

                // Регистрация шаблонизаторов.
                $view->registerEngines(
                    [
                        ".volt"  => "voltShared",
                        ".phtml" => PhpEngine::class,
                    ]
                );

                return $view;
            }
        );
    }

}
