<?php

/**
 * Class Manager
 * Набор методов для удобной работы с хранилищами
 */
class Manager
{

    /**
     * Метод для миграции пользователей из одного хранилища в другое
     * @param AbstractStorage $from
     * @param AbstractStorage $to
     * @return array
     */
    public static function migrate(AbstractStorage $from, AbstractStorage $to)
    {
        $log = [];
        $users = $from->getUsers();
        foreach ($users as $user) {
            $success = $to->addUser($user['username'], $user['password']);
            if ($success) {
                $log[] = "Пользователь \"{$user['username']}\" успешно мигрирован.";
            } else {
                $log[] = "Не удалось мигрировать пользователя \"{$user['username']}\". Возможно, пользователь уже существует.";
            }
        }
        return $log;
    }

}