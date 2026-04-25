**laravel email**<br>

环境<br>
php8.1+<br>
laravel10.0+<br>
mysql5.7+<br>

安装<br>
`composer require aphly/laravel-email` <br>
`php artisan vendor:publish --provider="Aphly\LaravelEmail\EmailServiceProvider"` <br>

初始化<br>
`php artisan laravel-email:init` <br>

队列<br>
`php artisan queue:table`
`php artisan queue:failed-table`
`php artisan migrate`

队列守护中命令<br>
`php artisan queue:work --queue=email_vip,email`
