<?php

require 'classes/LDAPConnectionData.php';
require 'classes/LDAPStorage.php';

$ldapConnectionData = new LDAPConnectionData();
$ldapConnectionData->host = '192.168.99.100';
$ldapConnectionData->port = 32769;
$ldapConnectionData->bindDn = 'cn=admin,dc=example,dc=org';
$ldapConnectionData->bindPassword = 'admin';

$connection = new LDAPStorage($ldapConnectionData);
$connection->test();