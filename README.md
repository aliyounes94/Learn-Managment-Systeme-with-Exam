# Laravel Learn Management System
LAVSMS is developed for Learning institutions like schools, and colleges built on Laravel 8

-Dashboard dashboard

-Login login

-Student Marksheet marksheet

-System Settings system-settings

-Print Marksheet

-Print Tabulation Sheet & Marksheet tabulation-sheet

## Types of user accounts:

Administrators (Super Admin & Admin)

Accountant

Teacher

Student

Parent
## Requirements

Check Laravel 8 Requirements https://laravel.com/docs/8.x

Check php version Requirements php 7.4 (use command php -version)

Composer

Git

## Installation

Install dependencies (composer install)

Set Database Credentials & App Settings in dotenv file (.env)

Migrate Database (php artisan migrate)

Database seed (php artisan db:seed)
### Login Credentials After seeding. Login details as follows:
| Account Type  | Username | Email | Password |
| ------------- | -------- | ----- | -------- |
| Super Admin | LMS | superadmin@gmail.com | adminadmin |
|  Admin | admin | admin@admin.com | admin |
|  Teacher | teacher | teacher@teacher.com | admin |
|  Parent | parent | parent@parent.com | admin |
|  Accountant | accountant | accountant@accountant.com | admin |
|  Student | student | student@student.com | admin |
