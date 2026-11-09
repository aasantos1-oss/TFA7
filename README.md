# TFA7 accounts project adapted from TSA3

This is a separate source project based on the supplied TSA3 Laravel 13 application (framework v13.39.0 in the supplied lock file). It adds the create/edit customer and user workflows required by the attached TFA7 activity. The original task pages are retained. This is not a reconstruction of the missing TFA6 POS application: any other TFA6 sales, product, or POS requirements were not supplied and have not been added.

## Installation

Follow START_HERE.txt in this folder. Extract into a new folder and open it in VS Code. Use PHP 8.5, Composer, GD and PDO SQLite. The original project instructions specify PHP 8.5. Dependencies are downloaded by Composer; vendor and node_modules are intentionally excluded. No npm build is needed for the task and account pages, which use static CSS.

Run these commands in PowerShell from the folder containing artisan:

```powershell
Copy-Item .env.example .env
composer install
composer require "intervention/image:^3.11"
php artisan key:generate
php artisan config:clear
php artisan migrate
php artisan db:seed --class=Tfa7Seeder
php artisan storage:link
php artisan serve
```

The first Copy-Item command is for first installation only. The template selects a fresh local SQLite database, included as an empty file. Do not copy the old project's environment or populated database. No database server or phpMyAdmin is required for this SQLite setup. If PHP reports a missing driver, enable pdo_sqlite and sqlite3 in the active php.ini. To use MySQL instead, first create a separate empty database and configure its DB_* settings before migrating.

The Composer manifests and lock file from TSA3 are kept together. The explicit composer require step installs the additional Intervention Image v3 package and updates both manifests on your computer. Include those updates in the final repository. Your instructor must approve the image package, as required by the worksheet.

Run `php -m` to check for GD. Run `php --ini` to locate the active configuration. Enable `extension=gd` if needed and restart the server. Allow `upload_max_filesize=4M` and `post_max_size=8M`; the app still enforces the 2 MB image limit. For symbolic-link failures on Windows, enable Developer Mode or use a terminal with permission to create symbolic links.

## Pages and use

With the default local server, visit:

- http://127.0.0.1:8000/ for the retained task homepage
- http://127.0.0.1:8000/customers for the customer list
- http://127.0.0.1:8000/customers/create for customer creation
- http://127.0.0.1:8000/users for user accounts
- http://127.0.0.1:8000/users/create for user creation

Both lists have Edit links. User avatars are uploaded from Edit, after creating an account. User email and password are included because the actual TSA3 users schema requires them. Blank passwords on edit preserve the existing hash. The sample profile has a random password and is a display record, not a supplied login credential.

## Integrated changes

- Added customers table, Customer model and factory.
- Reused the existing User model, username unique index, and password cast.
- Added nullable users.avatar through a migration.
- Connected routes and navigation; no manual route editing is required.
- Added required/email/unique validation, error messages, old input and prefilled edit forms.
- Added JPG/PNG avatar validation up to 2048 KB, with a 4096-pixel maximum on either side to bound decoding cost.
- Converted accepted uploads to 256 by 256 JPEGs, using generated filenames on the public disk; stored only relative paths in the database.
- Added a placeholder, retained the current avatar when no replacement is selected, and cleaned up replaced generated avatars after successful database updates.
- Changed the profile page to select the configured developer account, falling back to the oldest account if that username has changed. The original sole-user query would throw after creating a second account.
- Added Tfa7Seeder, which supports multiple accounts and does not reset existing accounts. Use `php artisan db:seed --class=Tfa7Seeder`. The original DatabaseSeeder/ProfileSeeder remain for the retained TSA3 tests; their one-user constraint is intentionally unchanged. Do not use plain db:seed or migrate --seed for TFA7 after adding accounts.

The TFA7 pages have their own simple layout and link back to the original task homepage. The original task navigation now links to Customers and User Accounts.

## Validation and test checklist

Run:

```powershell
php artisan test --compact tests/Feature/Tfa7AccountsTest.php
php artisan test --compact tests/Feature/TaskManagementTest.php
php vendor/bin/pint --dirty --format agent
```

If not using Git, format the changed PHP paths explicitly rather than relying on --dirty. Tests use an in-memory SQLite database through phpunit.xml. Do not point test configuration at real data.

The new feature tests cover customer create/edit/validation, user uniqueness and password preservation, image conversion and replacement, invalid uploads, escaped names, and multi-user profile/seeding behavior. GD and the installed image package are required for image tests.

Also check manually:

1. Submit invalid required fields and an invalid email. Temporarily disable HTML form validation in developer tools if necessary to exercise server errors. Confirm text fields retain input.
2. Create and edit a customer and two users. Duplicate usernames must be rejected; an unchanged username on edit must pass.
3. Upload a valid JPG/PNG, verify the avatar on the list and the generated file under storage/app/public/avatars/tfa7.
4. Try an image larger than 2 MB, a nonimage, and an image with a side over 4096 pixels. Each must fail without changing the saved avatar.
5. Edit a user without selecting a new image or password. Both existing values must be preserved.
6. Confirm the original task and profile pages still load with multiple accounts.

No PHP executable or Composer is installed in the authoring environment. Runtime tests, PHP lint, Artisan commands and Pint could not be executed there. Static checks cover route/view references, archive contents, and migration ordering. Tests are supplied for execution on your machine, not claimed as passing.

## Troubleshooting

- Could not open artisan: the terminal is in the wrong folder.
- Missing vendor/autoload.php: run composer install.
- Missing Intervention class: run the exact composer require command above.
- Image cannot be processed: verify GD and inspect storage/logs/laravel.log.
- Avatar URL 404: check storage:link and APP_URL. The local template uses http://127.0.0.1:8000.
- Storage link already exists: keep it if it targets this project's storage/app/public.
- Database table missing: run migrate in this new project with its new .env.
- Seed complains about one user: use --class=Tfa7Seeder, not the old default seeder.
- Original TFA7 add-on ZIP: do not apply it again. This project already contains the integrated files.

## Submission and hosting

Submit the full integrated project, original and new migrations/seeders, dependency manifests updated by Composer, and a README with your actual hosted URL. Do not commit .env, personal databases, dependency directories or runtime logs. This source archive excludes the original populated database, environment, caches, dependencies and nested old ZIP.

The supplied TSA3 app has no login or role system. These coursework account routes are public, so any visitor can modify accounts if you host them as supplied. Add appropriate staff authentication and authorization or host in an access-restricted demonstration environment before exposing real accounts. Login functionality was outside the supplied activity scope.

Hosting has not been performed. The host needs compatible PHP, GD, Composer dependencies, a configured separate database, writable storage, a working public storage link, APP_DEBUG=false and an HTTPS APP_URL. The document asks to extend a TFA6 POS app; check with your instructor whether using TSA3 as the base is acceptable, since the missing POS modules cannot be verified here.

Original TSA3 instructions are retained as README_TSA3_ORIGINAL.md for reference. Use this README and START_HERE.txt for the adapted TFA7 project.
