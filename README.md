Stiki E-Appointment Backend
==========

### Requirement
- PHP 8.0 or higher
- MySQL 5.5 or higher
- Laravel 9

## Installation

1. Clone Project
2. Install laravel vendor

```bash
composer install
``` 

3. Create .env and edit the credential

```bash
cp .env.example .env
```

4. Link Storage
```bash
php artisan storage:link
```

or (if you use shared hosting)
```bash
ln -s storage-project-dir/storage/app/public public-dir/storage
``` 

5. Create laravel key

```bash
php artisan key:generate
```

6. Dump Database

```bash
php artisan migrate
```

## Host on Shared Hosting
1. Upload all file to root folder
2. Copy public folder to public_html
3. change index.php in public_html
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```
to
```php
require __DIR__.'/../{host-folder}/vendor/autoload.php';
$app = require_once __DIR__.'/../{host-folder}/bootstrap/app.php';
```

## Patch
1. Pull from master on this repositories
2. Run migration to makesure all table already created
``` bash
php artisan migrate
```
if there is alert about migrate in production just answer yes


## Clear cache
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
php artisan view:clear
php artisan route:clear
```

## Task

Superadmin for admin stiki
- Core
  - [x] Admin Bootstrap
  - [x] Login Page
- Setting Page
  - [x] View Setting
  - [x] Edit Setting
- Superadmin (aka Kaprodi)
  - [ ] View Superadmin
  - [ ] Create Superadmin
  - [ ] Detail Superadmin
- Student
  - [x] View Student
  - [x] Create Student
  - [x] View Detail Student
  - [x] Edit Student
  - [x] Reset password student
  - [x] Import Student from CSV
- Lecture 
  - [x] View Lecture
  - [x] Create Lecture
  - [x] View Detail Lecture
  - [x] Create Lecture
  - [x] Edit Lecture
  - [x] Reset password lecture
  - [x] Import lecture from csv
- Activity (Role: Admin)
  - [x] Create Activity
  - [x] Get Detail Activity
  - [x] Edit Activity
  - [ ] Delete Activity
  - [ ] Notification Activity Student
  - [ ] Notification Activity Lecture
- Logbook
  - [ ] Get Activity Logbook

API
- Core
    - [x] Core FCM
    - [x] Core realtime chat
    - [ ] Realtime FCM
  
- Notification
  - [ ] Get Notification
  - [ ] Get Notification Detail
  - [ ] Read Notification
  - [ ] Create Notification

- Student
  - Activity
    - [x] Get Activity
    - [x] Get Detail Activity
    - [ ] Notification activity
  - Activity Appointment
    - [x] Get Appointment
    - [x] Get Detail Appointment
    - [x] Create appointment
    - [x] Last 5 Nearest Appointment
    - [ ] Notification appointment
  - Appointment Chat
    - [X] Get Detail Chat
    - [X] Publish Chat
    - [ ] Notification Chat
  - Logbook
    - [x] Get Logbook
    - [x] Get Logbook Detail
    - [X] Edit Logbook
    - [x] Create Logbook
  - Other
    - [ ] Get Profile
    
- Lecture
  - Activity
    - [X] Get Activity
    - [X] Get Detail Activity
    - [ ] Notification activity
  - Activity Appointment
    - [X] Get Appointment
    - [X] Get Detail Appointment
    - [ ] Notification appointment
  - Appointment Chat
    - [X] Get Detail Chat
    - [X] Publish Chat    
  - Logbook
    - [X] Get Logbook Detail By Participant ID
    - [X] Update Logbook (comment)
  - Other
    - [ ] Get Profile
    - [ ] Get Notifications
    - [ ] Get Notification Detail
