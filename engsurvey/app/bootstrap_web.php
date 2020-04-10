<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    /**
     * Создание FactoryDefault.
     * FactoryDefault обеспечивает автоматическую регистрацию
     * полного набора сервисов, необходимых приложению.
     */
    $di = new FactoryDefault();

    /**
     * Загрузка общих сервисов приложения.
     */
    require APP_PATH . '/config/services.php';

    /**
     * Загрузка веб-сервисов.
     */
    require APP_PATH . '/config/services_web.php';

    /**
     * Подключение автозагрузчика, общего для всех модулей.
     */
    require APP_PATH . '/config/loader.php';

    /**
     * Создание приложения.
     */
    $application = new Application($di);

    /**
     * Регистрация модулей приложения.
     */
    $application->registerModules(
        [
            'frontend' => [
                'className' => 'Engsurvey\Frontend\Module',
                'path'      => APP_PATH . '/modules/frontend/Module.php',
            ],
            //"admin" => [
            //    "className" => "Engsurvey\\Admin\\Module",
            //    "path"      => APP_PATH . "/modules/admin/Module.php",
            //],
        ]
    );

    /**
     * Обработка запроса.
     */
    // TODO: Решить вопрос с необходимостью удаления "\n", "\r", "\t".
    //echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
    echo $application->handle()->getContent();

} catch (\Engsurvey\Exception $e) {
    echo '<strong>Engsurvey Exception</strong><br>';
    echo 'Сообщение: ' . $e->getMessage() . '<br>';
    echo 'Файл: ' . $e->getFile() . '<br>';
    echo 'Строка: ' . $e->getLine();
    echo '<pre>' . $e->getTraceAsString() . '</pre>';

} catch (\Phalcon\Exception $e) {
    echo '<strong>Phalcon Exception</strong><br>';
    echo 'Сообщение: ' . $e->getMessage() . '<br>';
    echo 'Файл: ' . $e->getFile() . '<br>';
    echo 'Строка: ' . $e->getLine() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';

} catch (\PDOException $e) {
    echo '<strong>PDOException</strong><br>';
    echo 'Сообщение: ' . $e->getMessage() . '<br>';
    echo 'Файл: ' . $e->getFile() . '<br>';
    echo 'Строка: ' . $e->getLine() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
    
} catch (\Exception $e) {
    echo '<strong>Exception</strong><br>';
    echo 'Сообщение: ' . $e->getMessage() . '<br>';
    echo 'Файл: ' . $e->getFile() . '<br>';
    echo 'Строка: ' . $e->getLine() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
