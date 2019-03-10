#### Установка зависимостей

```bash
php composer.phar install
```

#### Настройки БД && RabbitMQ

```bash
vi config/parameters.yml
```

#### Инициализация БД

```bash
php bin/console doctrine:schema:update --force
```

#### Тестовые данные

```bash
php bin/console doctrine:fixtures:load -q
```
admin: admin@admin.ru : 123

user:  user@user.ru : 123

#### Запуск сервера

База обязательно должна существовать

```bash
php -S 127.0.0.1:8000 -t public/
```
