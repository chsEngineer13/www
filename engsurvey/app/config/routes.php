<?php
use Phalcon\Mvc\Router;

// Создания маршрутизатора без поддержки стандартной маршрутизации.
$router = new Router(false);

// Конечные слешы будут автоматически удалены из маршрута.
$router->removeExtraSlashes(true);

/**
 * Маршруты модуля Frontend.
 */

// Frontend является модулем по умолчанию.
$router->setDefaultModule('frontend');

// Маршруты по умолчанию.
$router->add(
    '/', 
    [
        'module' => 'frontend',
        'controller' => 'index',
        'action' => 'index',
    ]
);

$router->add(
    '/:controller',
    [
        'module' => 'frontend',
        'controller' => 1,
        'action' => 'index',
    ]
);

$router->add(
    '/:controller/:action',
    [
        'module' => 'frontend',
        'controller' => 1,
        'action' => 2,
    ]
);


// Аутентификация
$router->add(
    '/login',
    [
        'module' => 'frontend',
        'controller' => 'auth',
        'action' => 'login',
    ]
);

$router->add(
    '/verification',
    [
        'module' => 'frontend',
        'controller' => "auth",
        'action' => 'verification',
    ]
);

$router->add(
    '/change-password',
    [
        'module' => 'frontend',
        'controller' => 'auth',
        'action' => 'changePassword',
    ]
);

$router->add(
    '/verification-new-password',
    [
        'module' => 'frontend',
        'controller' => 'auth',
        'action' => 'verificationNewPassword'
    ]
);

$router->add(
    '/logout',
    [
        'module' => 'frontend',
        'controller' => 'auth',
        'action' => 'logout',
    ]
);

return $router;
