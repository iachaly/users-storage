<?php

require 'classes/LDAPConnectionData.php';
require 'classes/LDAPStorage.php';

echo PHP_EOL;
echo PHP_EOL;

$ldapConnectionData = new LDAPConnectionData();
$ldapConnectionData->host = '192.168.99.100';
$ldapConnectionData->port = 32769;
$ldapConnectionData->baseDn = 'dc=example,dc=org';
// username
$ldapConnectionData->bindDn = 'cn=admin,dc=example,dc=org';
// password
$ldapConnectionData->bindPassword = 'admin';

$connection = new LDAPStorage($ldapConnectionData);
//$connection->addGroup('Managers');
$connection->addUser('snewer0', 'rd4bj78x', 'Managers');
$connection->export();
