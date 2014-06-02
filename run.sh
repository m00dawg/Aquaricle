#!/bin/bash
alias php="/usr/local/bin/php"

if [ -a /Users/tim/opt/mysql/data/MooDawgPro.local.pid ]
then
    echo "MySQL Already Running"
else
    cd /Users/tim/opt/mysql
    ./bin/mysqld_safe --defaults-file=/Users/tim/opt/mysql/my.cnf &
fi
cd /Users/tim/git/aquaricle
php artisan serve
#python manage.py runserver
