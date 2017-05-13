<?php

/*
 * Информация по LDAP:
 * @see http://www.thegeekstuff.com/2015/02/openldap-add-users-groups
 *
 * @see http://www.zytrax.com/books/ldap/ape/
 * */

class LDAPStorage extends AbstractStorage
{

    private $connection;
    private $baseDn;

    public function __construct($data)
    {
        $connection = ldap_connect($data['host'], $data['port']);
        if ($connection) {
            // используем LDAPv3
            ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $binding = ldap_bind($connection, $data['bindDn'], $data['bindPassword']);
            if ($binding) {
                $this->connection = $connection;
                $this->baseDn = $data['baseDn'];
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

    public function lastError()
    {
        return ldap_error($this->connection);
    }

    public function addUser($username, $password = '')
    {
        $data = [];
        $data['sn'] = $username;
        $data['objectclass'] = ['person', 'organizationalPerson'];
        if ($password) {
            $data['userpassword'] = $this->passwordHash($password);
        }
        return @ ldap_add($this->connection, "cn=$username,{$this->baseDn}", $data);
    }

    public function getUsers()
    {
        $searchResults = ldap_search($this->connection, $this->baseDn, '(objectclass=organizationalPerson)');
        $users = ldap_get_entries($this->connection, $searchResults);
        $result = [];
        foreach ($users as $user) {
            $username = $user['cn'][0];
            $password = isset($user['userpassword']) ? $user['userpassword'][0] : '';
            if ($username) {
                $result[] = [
                    'username' => $username,
                    'password' => $password
                ];
            }
        }
        return $result;
    }

    public function dump()
    {
        $result = ldap_search($this->connection, $this->baseDn, '(objectClass=*)');
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
    }

}