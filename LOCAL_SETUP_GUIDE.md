# AgroTransit Local Setup Guide

Use this guide to run the AgroTransit Laravel website on a laptop for local demo/class work.

## Requirements

Install these first:

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite support for PHP

## 1. Open The Project Folder

Open a terminal inside the project folder:

```bash
cd AgroTransit-main
```

## 2. Install Dependencies

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

## 3. Create Environment File

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the Laravel app key:

```bash
php artisan key:generate
```

## 4. Create SQLite Database

Create the local database file:

```bash
touch database/database.sqlite
```

Make sure `.env` has:

```env
DB_CONNECTION=sqlite
```

## 5. Email OTP Setup

The app uses Gmail SMTP for real OTP emails.

In `.env`, set:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tech2edge01@gmail.com
MAIL_PASSWORD=your_gmail_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tech2edge01@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Important: `MAIL_PASSWORD` must be a Google App Password, not the normal Gmail password.

If you do not need real OTP emails on another laptop, you can temporarily use:

```env
MAIL_MAILER=log
```

Then OTP emails will be written to Laravel logs instead of being sent.

## 6. Prepare The Database

Run migrations and seed demo data:

```bash
php artisan migrate:fresh --seed
```

## 7. Build Frontend Assets

```bash
npm run build
```

## 8. Clear Cache

```bash
php artisan optimize:clear
```

## 9. Start Local Server

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open this URL in a browser:

```text
http://127.0.0.1:8000
```

## Demo Logins

Use these demo accounts:

```text
Admin:
Email: admin@agro.test
Password: password

Farmer:
Email: amandeep@agro.test
Password: password

Driver:
Email: driver@agro.test
Password: password

Transport Owner:
Email: owner@agro.test
Password: password
```

When switching roles, logout first before logging in as another role.

## Useful Commands

Run tests:

```bash
php artisan test
```

Rebuild frontend:

```bash
npm run build
```

Reset demo database:

```bash
php artisan migrate:fresh --seed
```

Clear cache:

```bash
php artisan optimize:clear
```

## Common Fixes

If you see `419 Page Expired`, run:

```bash
php artisan optimize:clear
```

Then refresh the browser.

If login redirects to the wrong dashboard, logout first and login again with the correct role selected.

If OTP email is not received:

1. Confirm `MAIL_PASSWORD` is a Gmail App Password.
2. Confirm 2-Step Verification is enabled on the Gmail account.
3. Run:

```bash
php artisan config:clear
```

4. Try signup again with a real email address.

