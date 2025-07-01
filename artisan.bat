@echo off
REM This batch file runs Laravel Artisan commands with the custom PHP config
php -c . artisan %*
