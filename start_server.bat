@echo off
cd /d "%~dp0"
start http://localhost:3000/index.html
php -S localhost:3000
Pause