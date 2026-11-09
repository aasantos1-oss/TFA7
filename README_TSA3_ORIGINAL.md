# Tasks for Today Management System

A Laravel application for IT0049 Technical Summative Assessment 3. The Welcome page shows only today's database tasks, the Task List shows all saved tasks ordered by date, Profile displays the application's single user, and About identifies the developer.

Developer: Adrian Santos.

## Submission links

- GitHub repository: add the URL of your complete project repository.
- Hosted application: add your working deployment URL.

## Package and project requirements

This ZIP contains the complete application-specific files for a separate TSA3 Laravel project. It is an overlay, not a standalone framework installation. Use a clean Laravel 12/13 project with the standard framework skeleton, default migrations, `artisan`, Composer files, default `tests/TestCase.php`, and a private `.env`. Install its Composer dependencies normally. Keep the original Laravel `.gitignore`.

This is a separate assessment from the TFA6 POS application. Do not merge its seeders or migrations into the POS project. TSA3 needs exactly one user; TFA6 needs at least five. Use a separate project folder and database so both submissions remain valid.

If you need a fresh skeleton, run the following from the directory where you keep your projects, such as `C:\xampp\htdocs`:

```powershell
composer create-project laravel/laravel TSA3 "^13.0"
```

Then copy this ZIP's contents into `C:\xampp\htdocs\TSA3`, where its `artisan` file is located. Merge folders and replace matching application files. Keep the rest of Laravel. Back up matching files before replacing anything in an existing project. If using an existing skeleton, it must not already have the POS `username` and `full_name` migrations; these files target Laravel's default users table.

No npm commands are needed. The Blade layout loads `public/css/tasks.css` directly and does not call Vite.

## Database setup

Choose a dedicated TSA3 database. The PHP executable used by Artisan needs the PDO driver for your chosen database. Do not point this project at the POS database.

### MySQL with XAMPP

Start MySQL in XAMPP and create an empty database named `tsa3_tasks` through phpMyAdmin. Merge the following into your project's existing `.env`, retaining its existing `APP_KEY` and other framework settings:

```dotenv
APP_NAME="Tasks for Today"
APP_URL=http://127.0.0.1:8001
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tsa3_tasks
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
TASKS_TIMEZONE=Asia/Manila
TASKS_DEVELOPER_NAME="Adrian Santos"
TASKS_PROFILE_USERNAME=adrian.santos
TASKS_PROFILE_EMAIL=adrian.santos@example.com
```

The blank password applies only if your local XAMPP root account has no password. Use the actual configured credentials. Enable `pdo_mysql` for the PHP command you use.

### SQLite alternative

SQLite also provides a real persistent database and satisfies the activity. Create a new empty `database/database.sqlite` file in the TSA3 project if one does not exist. Do not overwrite an existing database file. Use these database values instead of the MySQL values:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=C:/xampp/htdocs/TSA3/database/database.sqlite
```

Adjust the absolute path for your actual project folder and enable `pdo_sqlite`. The standard Laravel installer may already have created this database and run the default migrations; the new migrations in this package add the task schema and required profile fields afterward.

### Migrate, seed, and run

From the TSA3 project root, run each command separately:

```powershell
php artisan config:clear
php artisan migrate
php artisan db:seed
php artisan serve --port=8001
```

Visit http://127.0.0.1:8001. Check `/tasks`, `/profile`, and `/about` as well as `/`.

The profile seeder creates one user. If unrelated users exist in the selected database, it stops with a message rather than deleting them. In that case, configure a new empty TSA3 database and run the migration and seeding commands there. No database-reset command is necessary.

For a fresh repository clone, run `composer install`, copy the skeleton's `.env.example` to `.env`, configure its database and the `TASKS_*` values above, and run `php artisan key:generate` before migrating. Do not regenerate an existing application's key.

`.env.tsa3.example` is a configuration snippet for this activity. Merge it into the real `.env`; it deliberately does not supply an application key or replace all Laravel environment settings.

## Activity requirements

| Requirement | Implementation |
| --- | --- |
| `tasks` schema | Migration creates `title`, `status` defaulting to `pending`, `task_date`, timestamps, and an ID |
| `users` schema | Existing Laravel users table retains email and gains required `username` with a unique index and `full_name` |
| At least eight tasks spanning three dates | `TaskSeeder` provides eight tasks across yesterday, today, and tomorrow |
| Exactly one user | `ProfileSeeder` creates or updates one configured profile and rejects unrelated user rows |
| Task and User Eloquent models | `app/Models/Task.php` and `app/Models/User.php` |
| Welcome `/` | Eloquent date scope filters the database to today's `task_date` |
| Task List `/tasks` | Retrieves every task ordered by `task_date`, then ID |
| Profile `/profile` | Retrieves the single user with Eloquent `sole()` |
| Static About `/about` | Identifies Adrian Santos as the developer; does not query a user record |
| Multiple Blade views | Four pages share a layout; both task pages share the same task table partial |
| Submission source | Migrations, seeders, models, controllers, views, CSS, configuration, tests, and documentation are included |

Laravel's default users migration is kept. A new migration adds `username` and `full_name` rather than replacing that table or removing its original name, email, password, verification, token, and timestamp columns. Those extra framework columns are not additional profile records. The profile seeder populates the required fields and retained non-null columns. Any default accounts that already exist retain their data when the fields are added; afterward, the one-user seeding rule requires a clean dedicated database.

## Date behavior and sample data

The calendar date is calculated using `TASKS_TIMEZONE`, which defaults to `Asia/Manila`. Filtering, the Welcome date heading, and seeding use that same timezone. `TaskController::today` computes the date once and passes it to the model's reusable `forDate` scope, which calls `whereDate` on the Eloquent query. Both listing pages read actual database records on each request.

The sample tasks are:

| Scheduled date relative to seeding | Task | Status |
| --- | --- | --- |
| Yesterday | Review project requirements | Completed |
| Yesterday | Prepare the database schema | Completed |
| Yesterday | Organize Laravel project files | Pending |
| Today | Review today's task list | Pending |
| Today | Implement Eloquent task queries | In progress |
| Today | Verify profile information | Completed |
| Tomorrow | Test task date filtering | Pending |
| Tomorrow | Prepare repository submission | Pending |

Dates are relative to when you run the seeder, not hardcoded to the day this ZIP was generated. Tasks stay on their saved dates as real time passes. If you demonstrate the application on a later day and need fresh sample tasks for that date, rerun `php artisan db:seed`. Reseeding updates the eight sample tasks' dates and statuses by their stable titles without creating another set. Other task titles are left alone. The profile remains one row, and its password is not reset. No task updates happen automatically when a page is viewed.

The original TSA1 task titles and actual profile email were not supplied with this activity. This version therefore uses the sample task text above, developer name Adrian Santos, username `adrian.santos`, and placeholder email `adrian.santos@example.com`. Replace the seed task text if your lecturer requires the exact original TSA1 records. Set your actual profile values through `TASKS_DEVELOPER_NAME`, `TASKS_PROFILE_USERNAME`, and `TASKS_PROFILE_EMAIL` before the first seed. After changing environment values, run `php artisan config:clear`. Changing the username after creating the profile requires reconciling that existing profile; the seeder does not silently create a second user.

The random profile password is hashed through the User model. No login screen or shared login password is part of this assessment. Task creation, editing, status changes, and deletion screens are also outside the stated activity requirements; the supplied application provides read-only pages.

## MVC file map

| URL | Controller method | View | Data |
| --- | --- | --- | --- |
| `/` | `TaskController::today` | `pages/welcome.blade.php` | Eloquent collection filtered to today |
| `/tasks` | `TaskController::index` | `tasks/index.blade.php` | Complete Eloquent collection in date order |
| `/profile` | `ProfileController::show` | `pages/profile.blade.php` | Single User model |
| `/about` | `PageController::about` | `pages/about.blade.php` | Static information and configured developer name |

Routes are in `routes/web.php`. Date filtering is shared through `Task::scopeForDate`. The task table partial is `resources/views/tasks/partials/table.blade.php`, which uses `@foreach` and escaped Blade property output. The shared navigation lives in `resources/views/layouts/app.blade.php`. Configuration is in `config/tasks.php`.

## Verification and feature tests

Confirm there are at least eight tasks across at least three distinct dates, with tasks dated today, and exactly one row in `users`. The Welcome page should show only today's tasks, including completed tasks scheduled today. The Task List should include every date in ascending order. Profile should show the single saved username, full name, and email. About should identify the developer. Edit a task title in your database tool and reload the page to verify that it reads the saved row.

Fourteen feature tests are included. They check navigation, seed counts and dates, the database's default status, today's filtering, the Philippine date across the UTC midnight boundary, full-list ordering, profile retrieval, the static About page, idempotent seeding, reseeding on a later date, preservation of unrelated users, unique usernames, an empty daily list, and escaped task text.

Run:

```powershell
php artisan config:clear
php artisan test --configuration=phpunit.tsa3.xml
```

The dedicated PHPUnit configuration forces an in-memory SQLite test database. It does not use the application database. It requires your Laravel PHPUnit development dependencies, the default `tests/TestCase.php`, and `pdo_sqlite`.

PHP and Composer were unavailable in the preparation environment. Structural and package checks were performed, but PHP linting, Laravel execution, actual migration execution, and the included feature tests still need to be run locally. No live deployment was performed.

## GitHub and hosted submission

Upload your full merged Laravel project to GitHub, not only this overlay. Include the Laravel skeleton, `artisan`, `composer.json`, `composer.lock`, configuration, migrations, seeders, models, controllers, Blade views, public CSS, tests, and this README. Keep the normal `.gitignore` and exclude `.env`, real credentials, `vendor`, `node_modules`, runtime caches, and logs. Keep an `.env.example` with placeholder settings for a fresh clone.

Deploy to a PHP host compatible with the Laravel version in your project's Composer lockfile and its required extensions. Use a dedicated database, apply migrations and the seeder, set `APP_ENV=production`, `APP_DEBUG=false`, and the actual hosted `APP_URL`, and supply the application key and credentials privately. The web document root must be Laravel's `public` directory; `storage` and `bootstrap/cache` must be writable. GitHub Pages does not execute Laravel/PHP. Add the real repository and hosted URLs at the beginning of this README before submitting.

## Official references

- Eloquent and query scopes: https://laravel.com/docs/13.x/eloquent
- Migrations: https://laravel.com/docs/13.x/migrations
- Seeding: https://laravel.com/docs/13.x/seeding
- Blade: https://laravel.com/docs/13.x/blade
