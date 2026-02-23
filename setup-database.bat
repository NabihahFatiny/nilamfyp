@echo off
title NILAM Database Setup
cd /d "%~dp0"

set MYSQL="C:\xampp\mysql\bin\mysql.exe"
set PHP_EXE=php
where php >nul 2>nul
if errorlevel 1 set PHP_EXE=C:\xampp\php\php.exe

echo Creating database nilamfyp if not exists...
%MYSQL% -u root -e "CREATE DATABASE IF NOT EXISTS nilamfyp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 (
    echo.
    echo Could not create database. Make sure MySQL is running in XAMPP and root has no password.
    echo You can create the database manually in phpMyAdmin: http://localhost/phpmyadmin
    echo Create a database named: nilamfyp
    echo.
    pause
    exit /b 1
)

echo.
echo Running migrations...
%PHP_EXE% artisan migrate --force
if errorlevel 1 (
    echo Migrations failed.
    pause
    exit /b 1
)

echo.
echo Seeding default teacher account...
%PHP_EXE% artisan db:seed --force
if errorlevel 1 (
    echo Seeding failed.
    pause
    exit /b 1
)

echo.
echo Done. You can log in as teacher with:
echo   Email: teacher@nilam.test
echo   Password: password
echo.
pause
