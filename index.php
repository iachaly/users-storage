<?php

spl_autoload_register(function ($className) {
    require "classes/$className.php";
});

$LDAPStorage = new LDAPStorage([
    'host' => '192.168.99.100',
    'port' => 32777,
    // базовый DN, относительно которого работаем
    'baseDn' => 'dc=example,dc=org',
    // DN пользователя, под которым аутентифицируемся
    'bindDn' => 'cn=admin,dc=example,dc=org',
    // пароль этого пользователя
    'bindPassword' => 'admin'
]);

$MySQLStorage = new MySQLStorage([
    'host' => '127.0.0.1',
    'dbname' => 'users_test',
    'user' => 'root',
    'password' => ''
]);

// пример миграции:
$log = Manager::migrate($MySQLStorage, $LDAPStorage);
echo implode('<br>' . PHP_EOL, $log);