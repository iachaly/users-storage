<?php

class Manager
{

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