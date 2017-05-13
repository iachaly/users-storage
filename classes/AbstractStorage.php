<?php

abstract class AbstractStorage
{

    abstract public function addUser($username, $password = '');
    abstract public function getUsers();

    /**
     * @see http://blog.michael.kuron-germany.de/2012/07/hashing-and-verifying-ldap-passwords-in-php/
     * @param $password
     * @return string
     */
    final protected function passwordHash($password)
    {
        return '{MD5}' . base64_encode(md5($password, true));
    }

}