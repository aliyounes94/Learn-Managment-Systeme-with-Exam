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
|  Admin | admin | admin@admin.com | adminadmin |
|  Teacher | teacher | teacher@teacher.com | adminadmin |
|  Parent | parent | parent@parent.com | adminadmin |
|  Accountant | accountant | accountant@accountant.com | adminadmin |
|  Student | student | student@student.com | adminadmin |
#### **FUNCTIONS OF ACCOUNTS** 

**-- SUPER ADMIN**
- Only Super Admin can delete any record
- Create any user account and students
- Reset Users passwords

**-- Administrators (Super Admin & Admin)**

- Manage students class/sections
- View marksheet of students
- Create, Edit and manage all user accounts & profiles
- Create, Edit and manage Exams & Grades
- Create, Edit and manage Subjects
- Manage noticeboard of school
- Notices are visible in calendar in dashboard
- Edit system settings
- Manage Payments & fees
- make exam questions
- view the best students
- show teacher Evaluation
-Delete Exams

**-- ACCOUNTANT**
- Manage Payments & fees
- Print Payment Receipts


**-- TEACHER**
- Manage Own Class/Section
- Manage Exam Records for own Subjects
- Manage Timetable if Assigned as Class Teacher
- Manage own profile
- Upload Study Materials
- Create new Exam questions
- add marks for student
- Browse Student profile
- add student evaluations

**-- STUDENT**
- View teacher profile
- View own class subjects
- View own marks and class timetable
- View Payments
- View library and book status
- View noticeboard and school events in calendar
- Manage own profile
- Evaluate teacher

**-- PARENT**
- View teacher profile
- View own child's marksheet (Download/Print PDF)
- View own child's Timetable
- View own child's payments
- View noticeboard and school events in calendar
- Manage own profile
