<?php

/*
 * @see http://www.thegeekstuff.com/2015/02/openldap-add-users-groups
 * @see http://blog.michael.kuron-germany.de/2012/07/hashing-and-verifying-ldap-passwords-in-php/
 * @see http://www.zytrax.com/books/ldap/ape/
 * */

class LDAPStorage
{

    private $connection;
    private $baseDn = 'dc=example,dc=org';

    public function __construct(LdapConnectionData $data)
    {
        $connection = ldap_connect($data->host, $data->port);
        if ($connection) {
            // используем LDAPv3
            ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $binding = ldap_bind($connection, $data->bindDn, $data->bindPassword);
            if ($binding) {
                $this->connection = $connection;
                $this->baseDn = $data->baseDn;
            } else {
                throw new Exception('Не удалось аутентифицироваться на LDAP сервере.');
            }
        } else {
            throw new Exception('Не удалось подключиться к LDAP серверу.');
        }
    }

    public function __destruct()
    {
        ldap_close($this->connection);
    }

    public function addGroup($groupName)
    {
        return ldap_add($this->connection, "cn=$groupName,{$this->baseDn}", [
            'objectclass' => ['organizationalRole']
        ]);
    }

    public function addUser($username, $password = '', $groupName = '')
    {
        $data = [];
        $data['cn'] = $username;
        $data['sn'] = $username;
        $data['objectClass'] = ['person', 'organizationalPerson'];
        if ($password) {
            $data['userPassword'] = '{MD5}' . base64_encode(md5($password, true));
        }
        if ($groupName) {
            $dn = "cn=$username,cn=$groupName,{$this->baseDn}";
        } else {
            $dn = "cn=$username,{$this->baseDn}";
        }
        return ldap_add($this->connection, $dn, $data);
    }

    public function lastError()
    {
        return ldap_error($this->connection);
    }

    public function export()
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