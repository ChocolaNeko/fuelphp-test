<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
    'active' => 'production',
    'development' => array(
        'type'           => 'PDO',
        'connection'     => array(
            'hostname'       => 'phpmyadmin',
            'port'           => '3306',
            'database'       => 'bbin',
            'username'       => 'root',
            'password'       => 'ayaya123',
            'persistent'     => false,
            'compress'       => false,
        ),
        'identifier'     => '`',
        'table_prefix'   => '',
        'charset'        => 'utf8',
        'enable_cache'   => true,
        'profiling'      => false,
        'readonly'       => false,
    ),
    'production' => array(
        'type'           => 'pdo',
        'connection'     => array(
            'dsn'            => 'mysql:host=phpmyadmin;dbname=bbin',
            'username'       => 'root',
            'password'       => 'ayaya123',
            'persistent'     => false,
            'compress'       => false,
        ),
        'identifier'     => '`',
        'table_prefix'   => '',
        'charset'        => 'utf8',
        'enable_cache'   => true,
        'profiling'      => false,
        // 'readonly'       => array('slave1', 'slave2', 'slave3'),
    ),
);
