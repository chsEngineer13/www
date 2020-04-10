<?php
use Phalcon\Loader;

$loader = new Loader();

/**
 * Регистрация пространств имен.
 */
$loader->registerNamespaces(
    [
        'Engsurvey' => APP_PATH . '/library/',
        'Engsurvey\Models' => APP_PATH . '/models/',
        
    ]
);

/**
 * Регистрация классов модулей.
 */
$loader->registerClasses(
    [
        'Engsurvey\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
        'Engsurvey\Cli\Module'      => APP_PATH . '/modules/cli/Module.php',
    ]
);

$loader->register();
