Frog
====
This is a repo which provide a sample application is required to support the following workflow:
* User 1 authentication
* User 1 uploads a zip file
* Contents of the zip file are extracted and stored
* Contents of the file become publicly available to all users (contents limmited to html, js, css, images)

Requirements
------------
* [LAMPP](https://www.apachefriends.org/download.html)
* [Composer](https://getcomposer.org/)
* [Laravel](https://laravel.com/docs/5.2)
* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

Set up on Mac
-------------

### Clone & change permission
    $ cd ~
    $ git clone git@github.com:tailehuu/laravel-frog.git
    $ cd laravel-frog
    $ chmod -R 777 storage/
    $ chmod -R 777 bootstrap/cache

### Update database account in __.env__ file
```
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-frog
DB_USERNAME=root
DB_PASSWORD=
...
```

### Continue...
    $ composer install
    $ php artisan migrate
    $ php artisan serve

The development server started on http://localhost:8000. You can use it now. 

Enjoy!