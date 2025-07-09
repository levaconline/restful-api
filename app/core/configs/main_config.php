<?php

/**
 * Main configuration file.
 * @package core
 * @author Aleksandar Todorovic
 * @version 1.0
 * @since 1.0
 * @todo Change project name, controllers, models, views, 
 * storage, js, css, logs directories, and database prefix 
 * with your real paths. (This is just simplified fordemo.)
 */
$conf = [
    'projectName' => "Restfull API - No-Framework (demo)",
    'controllersDir' => '/controllers',
    'modelsDir' => '/models',
    'viewsDir' => '/views',
    'storageDir' => __DIR__ . '/../storage',
    'jsDir' => "/app/helpers/js/",
    'cssDir' => "/app/helpers/css/",
    'logsDir' => __DIR__ . "/../misc/logs",
    'databasePrefix' => 'my_prefix',
];
