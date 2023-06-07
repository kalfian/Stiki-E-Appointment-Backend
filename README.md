# Admin Backend
Backend admin

## Presequete

-   Composer
-   PHP Version 8.1^

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

4. Create laravel key

```bash
php artisan key:generate
```

5. Create JWT Key

```bash
php artisan jwt:secret
```

6. Dump Database

```bash
php artisan migrate
```

7. Dump Dummy data

```bash
php artisan db:seed
```


8. Start Server

```bash
php artisan serve
```
## Clear cache
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
php artisan view:clear
php artisan route:clear
```

## Task

Superadmin
- [ ]  Create base superadmin
- [ ]  Crud master user student
- [ ]  crud master user lecture
- [ ]  Crud activity
- [ ]  Api login user
- [ ]  Api update fcm

API
- Core
    - [ ] Core FCM
    - [ ] Core realtime chat

- Student
  - Activity
    - [ ]  Get Activity
    - [ ]  Get Detail Activity
    - [ ]  Notification activity
  - Activity Appointment
    - [ ]  Get Appointment
    - [ ]  Get Detail Appointment
    - [ ]  Create appointment
    - [ ]  Notification appointment
  - Appointment Chat
    - [ ] Get Detail Chat
    - [ ] Publish Chat    
  - Logbook
    - [ ] Get Logbook
    - [ ] Create Logbook
  - Other
    - [ ] Get Profile
    - [ ]  Get Notifications
    - [ ]  Get Notification Detail
    
- Lecture
  - Activity
    - [ ]  Get Activity
    - [ ]  Get Detail Activity
    - [ ]  Notification activity
  - Activity Appointment
    - [ ]  Get Appointment
    - [ ]  Get Detail Appointment
    - [ ]  Create appointment
    - [ ]  Notification appointment
  - Appointment Chat
    - [ ] Get Detail Chat
    - [ ] Publish Chat    
  - Logbook
    - [ ] Get Logbook
    - [ ] Create Logbook
  - Other
    - [ ] Get Profile
    - [ ]  Get Notifications
    - [ ]  Get Notification Detail
