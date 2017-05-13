<?php

spl_autoload_register(function ($className) {
    require "classes/$className.php";
});

$LDAPStorage = new LDAPStorage([
    'host' => '192.168.99.100',
    'port' => 32777,
    'baseDn' => 'dc=example,dc=org',
    'bindDn' => 'cn=admin,dc=example,dc=org',
    'bindPassword' => 'admin'
]);

$MySQLStorage = new MySQLStorage([
    'host' => '127.0.0.1',
    'dbname' => 'users_test',
    'user' => 'root',
    'password' => ''
]);

//$log = Manager::migrate($LDAPStorage, $MySQLStorage);
$log = Manager::migrate($MySQLStorage, $LDAPStorage);
echo implode('<br>' . PHP_EOL, $log);