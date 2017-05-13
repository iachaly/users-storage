<?php

require 'classes/LDAPConnectionData.php';
require 'classes/LDAPStorage.php';

echo PHP_EOL;
echo PHP_EOL;

$ldapConnectionData = new LDAPConnectionData();
$ldapConnectionData->host = '192.168.99.100';
$ldapConnectionData->port = 32775;
$ldapConnectionData->baseDn = 'dc=example,dc=org';
// username
$ldapConnectionData->bindDn = 'cn=admin,dc=example,dc=org';
// password
$ldapConnectionData->bindPassword = 'admin';

$connection = new LDAPStorage($ldapConnectionData);


for($i = 0; $i < 10; ++$i) {
    $groupName = 'Group' . $i;
    $connection->addGroup($groupName);
    for ($j = 0; $j < 10; ++$j) {
        $connection->addUser("User{$i}_{$j}", 'password', $groupName);
    }
}


//$connection->addUser('snewer5', 'rd4bj78x', 'Managers');

$groups = $connection->getGroups();
$users = $connection->getUsers();
