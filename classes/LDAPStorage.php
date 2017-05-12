<?php

class LDAPStorage
{

    private $connection;

    public function __construct(LdapConnectionData $data)
    {
        $connection = ldap_connect($data->host, $data->port);
        if (!$connection) {
            throw new Exception('Не удалось подключиться к LDAP серверу.');
        }
        // используем LDAPv3
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        $binding = ldap_bind($connection, $data->bindDn, $data->bindPassword);
        if (!$binding) {
            throw new Exception('Не удалось подключиться к LDAP серверу.');
        }
        $this->connection = $connection;
    }

    public function test()
    {
        $baseDn = 'dc=example,dc=org';
        $result = ldap_search($this->connection, $baseDn, '(objectClass=*)');
        $data = ldap_get_entries($this->connection, $result);
        for ($i = 0; $i < $data['count']; ++$i) {
            $node = $data[$i];
            echo "dn: {$node['dn']}" . PHP_EOL;
            for ($j = 0; $j < $node['count']; ++$j) {
                $key = $node[$j];
                $value = $node[$key];
                if (is_array($value)) {
                    for ($k = 0; $k < $value['count']; ++$k) {
                        echo "$key: {$value[$k]}" . PHP_EOL;
                    }
                } else {
                    echo "$key: $value" . PHP_EOL;
                }
            }
            echo PHP_EOL;
            echo PHP_EOL;
        }
        //print_r($data);
    }

}