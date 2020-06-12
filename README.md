##Первый запуск

- composer install
- Создавть файл .env и скопировать параметры из .env.example

И в .env добавить 
- MAILCHIMP_APIKEY - api ключ mailchimp
- MAILCHIMP_LIST_ID - ключ листа подписчиков
 


##Ввод начальных данных 

- php artisan migrate
- php artisan db:seed


##Команды

- php artisan emailsender:send - отправляет все емейлы из таблицы subscribers в лист mailchimp 
- php artisan emailsender:sync - синхронизирует статусы всех подписчиков из mailchimp

Для запуска синхронизации каждые 30 минут написать 
php artisan shedule:run >> dev/null 2>$1

##Добавление нового подписчика

Логин и пароль для авторизации
admin@gmail.com/secret

После автоизации, можно перейти на страницу subscribers/index где и добавить данные нового подписчика

