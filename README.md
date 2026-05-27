# Wedding Event Management Web Application

A Laravel-based wedding venue and event management platform for managing inquiries, venue visits, proposals, contracts, invoices, payments, bookings, event execution, and reporting.

## Current System Flow

```text
Lead / Inquiry
→ Manager Follow-up / Venue Visit
→ Proposal / Quotation
→ Contract
→ Invoice + Installments
→ Advance Payment
→ Confirmed Booking
→ BEO / Banquet Event Order
→ Event Timeline + Tasks
→ Event Completion / Reporting
```

## Main Features

### Multi-Role System

- **Admin Dashboard**: system overview, users, venues, packages, bookings, workflow data, reports, and system monitoring.
- **Manager Dashboard**: visit approval, customer calls, payment confirmation, calendar, assigned workflow tasks, and booking operations.
- **Customer Portal**: venue browsing, package selection, booking customization, visit scheduling, and booking tracking.

### Venue and Booking Management

- Multiple wedding halls with capacity, pricing, features, and active/inactive status.
- Professional wedding packages with guest limits, seasonal pricing, and compatible halls.
- Wedding type selection for Kandyan, Low-Country, European, Indian, and Catholic wedding flows.
- Catering menus, catering items, decorations, and paid/optional/compulsory additional services.
- Manager approval flow for visits and advance payment confirmation.

### Professional Event Workflow

- Lead CRM records for website inquiries.
- Proposal/quotation tracking.
- Contract tracking with signature status.
- Invoice and installment tracking.
- Calendar holds for soft holds, confirmed bookings, blocked dates, and maintenance.
- Banquet Event Orders for operations.
- Event timeline items for execution planning.
- Event tasks assigned to admin/manager users.

### Dashboard/API Coverage

Professional workflow endpoints are available after admin login:

```text
/admin/workflow/stats
/admin/workflow/leads
/admin/workflow/proposals
/admin/workflow/contracts
/admin/workflow/invoices
/admin/workflow/calendar-holds
/admin/workflow/beos
/admin/workflow/tasks
```

## Technology Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade templates, Tailwind CSS, JavaScript, Vite
- **Database**: MySQL 8+ recommended; SQLite supported for CI/testing
- **Authentication**: Laravel authentication with role middleware
- **Build Tool**: Vite
- **CI**: GitHub Actions workflow for install, routes, migrations, tests, and frontend build

## Installation

### 1. Clone repository

```bash
git clone https://github.com/Nirwan-WSNj/Wedding-Event-Management-Web-Application.git
cd Wedding-Event-Management-Web-Application
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Set your database connection in `.env`.

Example for MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wmdemo
DB_USERNAME=root
DB_PASSWORD=
DEMO_USER_PASSWORD=change-this-local-password
```

### 4. Build database

For a fresh development/demo database:

```bash
php artisan migrate:fresh --seed
```

For an existing database:

```bash
php artisan migrate
php artisan db:seed
```

### 5. Link storage and clear caches

```bash
php artisan storage:link
php artisan optimize:clear
```

### 6. Build frontend assets

```bash
npm run build
```

### 7. Start local server

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Demo Users

Seeder creates these demo accounts:

| Role | Email | Password Source |
|---|---|---|
| Admin | sandesh.nirwan@wmdemo.com | `.env` value: `DEMO_USER_PASSWORD` |
| Manager | manager@wmdemo.com | `.env` value: `DEMO_USER_PASSWORD` |
| Customer | customer@wmdemo.test | `.env` value: `DEMO_USER_PASSWORD` |

Set this in `.env` before seeding:

```env
DEMO_USER_PASSWORD=your-local-demo-password
```

Do not use demo passwords in production.

## Useful Development Commands

```bash
composer dump-autoload
php artisan optimize:clear
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
npm run build
```

## Production Notes

Before production deployment:

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Use strong real admin passwords.
- Configure HTTPS, mail, queues, storage, backups, and database credentials.
- Run `npm run build`.
- Run migrations safely with a database backup.
- Review role permissions and file-upload validation.

## Project Status

Current status: **Professional workflow foundation added**.

Completed:

- Venue/package/booking foundation
- Manager visit and payment workflow
- Professional demo seed data
- Lead/proposal/contract/invoice/calendar/BEO/task schema
- Admin workflow API endpoints
- CI workflow file

Next recommended work:

- Admin dashboard UI tabs for workflow modules
- Manager workflow/task UI
- Calendar conflict checking in booking form
- Proposal/contract/invoice action buttons
- More automated feature tests
