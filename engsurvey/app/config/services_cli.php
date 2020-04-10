<?php

use Phalcon\Cli\Dispatcher;

/**
 * Задание пространство имен по умолчанию для диспетчера.
 */
$di->setShared(
    'dispatcher', 
    function() {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('Engsurvey\Cli\Tasks');
        
        return $dispatcher;
    }
);
