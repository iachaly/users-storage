<?php

/**
 * Class MySQLStorage
 * Хранилище для хранения пользователей в бд MySQL
 */
class MySQLStorage extends AbstractStorage
{

    private $connection;

    public function __construct($data)
    {
        $dsn = "mysql:dbname={$data['dbname']};host={$data['host']}";
        $this->connection = new PDO($dsn, $data['user'], $data['password']);
    }

    public function addUser($username, $password = '')
    {
        $password = $password ? $this->passwordHash($password) : '';
        $sql = 'INSERT INTO `users` (`username`, `password`) VALUES (:username, :password)';
        $sth = $this->connection->prepare($sql);
        $sth->bindParam(':username', $username);
        $sth->bindParam(':password', $password);
        return $sth->execute();
    }

    public function getUsers()
    {
        $users = $this->connection->query('SELECT * FROM `users` ORDER BY `username` ASC')->fetchAll();
        $result = [];
        foreach ($users as $user) {
            $result[] = [
                'username' => $user['username'],
                'password' => $user['password']
            ];
        }
        return $result;
    }

}