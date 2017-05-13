<?php

/**
 * Class AbstractStorage
 * Абстрактный класс для реализации хранилища
 */
abstract class AbstractStorage
{

    /**
     * Добавление нового пользователя в хранилище
     * @param string $username
     * @param string $password
     * @return boolean
     */
    abstract public function addUser($username, $password = '');

    /**
     * Получение списка пользователей из хранилища
     * @return array
     */
    abstract public function getUsers();

    /**
     * Генерация хэша пароля
     * Такой формат связан с требованиями LDAP каталога
     * @see http://blog.michael.kuron-germany.de/2012/07/hashing-and-verifying-ldap-passwords-in-php/
     * @param $password
     * @return string
     */
    final protected function passwordHash($password)
    {
        return '{MD5}' . base64_encode(md5($password, true));
    }

}