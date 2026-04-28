# Internal Academy

A workshop management platform for internal company training.
Admins create and manage workshops; employees browse, sign up, and join waitlists.

Built with **Laravel 13**, **Vue 3**, **Inertia.js**, and **SQLite**.

---

## Requirements

- PHP 8.3+
- Composer
- Node.js 20+ and npm
- SQLite

Or, if you prefer Docker: just Docker and Docker Compose (no other dependencies needed).

---

## Installation

### With Docker (recommended)

```bash
docker compose up --build
```

The app will be available at `http://localhost:8080`.

The container automatically runs migrations and seeds the database on startup — no manual setup required.

Two ports are exposed:
- `http://localhost:8080` — web app
- `ws://localhost:6001` — Reverb WebSocket server (real-time updates)

### Without Docker

```bash
# 1. Install PHP dependencies
composer install

# 2. Install JS dependencies
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create the database and run migrations
touch database/database.sqlite
php artisan migrate

# 5. Start the development servers (in two separate terminals)
php artisan serve
npm run dev
```

The app will be available at `http://localhost:8000`.

#### Real-time updates (WebSockets)

The admin dashboard shows a live registration counter powered by Laravel Reverb. To enable it locally, start Reverb in a separate terminal:

```bash
php artisan reverb:start
```

Without Reverb running, the counter will still show the correct value on page load — it just won't update in real-time.

---

## Test data

To populate the database with sample users, workshops, and registrations:

```bash
php artisan db:seed
```

This creates two ready-to-use accounts:

| Role     | Email                   | Password |
| -------- | ----------------------- | -------- |
| Admin    | `admin@academy.test`    | password |
| Employee | `employee@academy.test` | password |

A second employee account (`employee2@academy.test` / `password`) is also seeded for testing multi-user scenarios such as waitlists and overlap prevention.

To reset the database and reseed from scratch:

```bash
php artisan migrate:fresh --seed
```

---

## Running the tests

```bash
php artisan test
```

To run a specific test file:

```bash
php artisan test tests/Feature/RegistrationPolicyTest.php
php artisan test tests/Feature/WaitlistPromotionTest.php
```

Tests use an in-memory SQLite database and are fully isolated — no setup required beyond `composer install`.

---

## Artisan commands

### Workshop reminders

```bash
php artisan academy:remind
```

Sends a reminder email to all confirmed participants of workshops scheduled for the following day. Intended to be run as a daily scheduled task (e.g. via cron at 08:00).

By default the app uses the `log` mail driver — emails are written to `storage/logs/laravel.log` instead of being delivered. To verify the command locally:

```bash
php artisan academy:remind
tail -f storage/logs/laravel.log
```

---

## Architecture

See [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) for the key technical decisions made during development.
