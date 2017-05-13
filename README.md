## Задача
Организация хранения польователях в различных 
хранилищах с возможностью синхронизации между ними.

### Решение
Для организации хранения пользователей в различных хранилищах
необходимо определить такой набор полей для пользователя,
на основе которого любое хранилище однозначно может идентифицировать
пользователя.

Для простоты будем иметь следующую структуру:
```
[
    'username' => 'Username',  // Имя пользователя
    'password' => 'PasswordHash' // Хэш пароля
]
```

То есть любое хранилище должно возвращать набор пользователей
в таком формате, и принимать такой формат для добавления новго
пользователя.

### Хранилище

За хранилище будем считать класс, который наследует 
класс Абстрактного хранилища `AbstractStorage`, который
физически находится по адресу:
```
/classes/AbstractStorage
```
Абстрактный класс требует реализации двух методов:
`AddUser` для добавления нового пользователя и 
`GetUsers` для получения списка существующих.

Также класс предоставляет метод `passwordHash` для
получения хэша пароля (связано с тем, что для LDAP
сервера хэш пароль хранится в 
[специальном виде](http://blog.michael.kuron-germany.de/2012/07/hashing-and-verifying-ldap-passwords-in-php/))

#### Хранилище OpenLDAP
Реализация хранилища для каталога OpenLDAP физически находится
по адресу:
```
/classes/LDAPStorage
```

#### Хранилище MySQL
Реализация хранилища для базы данных MySQL физически находится
по адресу:
```
/classes/MySQLStorage
```
Структура таблицы пользователей:
```
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);
```

### Менеджер хранилищ
Для удобной работы с хранилищами создан класс `Manager`,
который физически находится по адресу:
```
/classes/Manager
```
На данный момент менеджер содержит только один публичный статичный
метод `migrate`, слежущий для миграции пользователей из одного хранилища
в другое.

### Пример
```
// хранилище LDAP
$LDAPStorage = new LDAPStorage([
    'host' => '192.168.99.100',
    'port' => 32777,
    'baseDn' => 'dc=example,dc=org',
    'bindDn' => 'cn=admin,dc=example,dc=org',
    'bindPassword' => 'admin'
]);
// хранилище MySQL
$MySQLStorage = new MySQLStorage([
    'host' => '127.0.0.1',
    'dbname' => 'dbname',
    'user' => 'root',
    'password' => ''
]);
// Мигрируем пользователей из MySQL в LDAP
$log = Manager::migrate($MySQLStorage, $LDAPStorage);
echo implode('<br>' . PHP_EOL, $log);
```