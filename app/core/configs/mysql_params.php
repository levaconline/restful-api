<?php

/**
 * MySQL connection parameters
 * @package core
 * @author Aleksandar Todorovic
 * @version 1.0
 * @since 1.0
 * @todo Change setup by royr real DB server.
 * @todo This part is not obligatory, but recommended for production. (you can use .env hidden out of http access)
 */
/************************* MARIA DB PARAMS *********************/
$mysqlConnParams = [
    'host' => "localhost",
    'user' => "db_username",
    'password' => "db_user_password",
    'database' => "demo",
    'db_prefix' => "db_prefix_",
    'table_prefix' => "",
    'charset' => "utf8mb4",
    'collation' => "utf8mb4_unicode_ci",
    'engine' => "InnoDB",
    'log_file' => __DIR__ . '/logs/mysql.log',
    'log_level' => 3, // 0 - off, 1 - error, 2 - warning, 3 - info, 4 - debug
    'log_format' => 'json', // 'json', 'txt', 'csv'
    'log_max_size' => 1000000, // 1MB
    'log_max_files' => 10, // 10 files
    'log_max_age' => 30, // 30 days
    'log_rotate' => true, // true/false
    'log_rotate_interval' => 86400, // 1 day
    'log_rotate_max_size' => 1000000, // 1MB
];
/********************** end MARIA db params *******************/
