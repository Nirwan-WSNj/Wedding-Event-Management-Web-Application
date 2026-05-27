<div align="center">

# 💍 Wedding Event Management Web Application

**A professional Laravel-based platform for wedding venues, event bookings, customer visits, proposals, contracts, invoices, payments, and event operations.**

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-Build-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

</div>

---

## 📌 Project Overview

This project is a full wedding/event venue management system designed for venues and event planners who need to manage the full customer journey from first inquiry to final event execution.

It supports customer bookings, manager approvals, admin monitoring, payment workflows, professional proposals, contracts, invoices, calendar holds, Banquet Event Orders, timelines, and operational tasks.

---

## 🔄 Current System Flow

```text
Lead / Inquiry
   ↓
Manager Follow-up / Venue Visit
   ↓
Proposal / Quotation
   ↓
Contract
   ↓
Invoice + Installments
   ↓
Advance Payment
   ↓
Confirmed Booking
   ↓
BEO / Banquet Event Order
   ↓
Event Timeline + Tasks
   ↓
Event Completion / Reporting
```

---

## 👥 User Roles

| Role | Main Purpose | Key Features |
|---|---|---|
| **Admin** | Full system control | Dashboard, users, venues, packages, reports, workflow monitoring |
| **Manager** | Venue operation handling | Visit approval, customer calls, payment confirmation, calendar, tasks |
| **Customer** | Booking and event planning | Browse venues, choose packages, customize booking, track status |

---

## ✨ Main Features

### 🏛 Venue and Package Management

- Multiple wedding halls with price, capacity, images, features, and active status.
- Professional package setup with guest limits, seasonal pricing, and compatible halls.
- Dynamic booking form data loaded from database.
- Admin-side hall and package management APIs.

### 💒 Wedding Booking Flow

- Hall selection.
- Package selection.
- Wedding type selection.
- Catering, decoration, and additional service selection.
- Visit scheduling.
- Manager approval.
- Advance payment confirmation.
- Booking tracking.

### 📋 Professional Event Workflow

- Lead CRM records for website inquiries.
- Proposal and quotation tracking.
- Contract tracking with signed/unsigned status.
- Invoice and installment tracking.
- Calendar holds for soft holds, confirmed bookings, blocked dates, and maintenance.
- Banquet Event Orders for operational teams.
- Event timeline planning.
- Admin/manager task management.

### 📊 Dashboard and Reporting

- Admin dashboard statistics.
- Booking and visit monitoring.
- Revenue indicators.
- Workflow API endpoints.
- System health and reporting routes.

---

## 🧩 Professional Workflow API

Available after admin login:

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

---

## 🏗 System Architecture

```text
Customer Portal
   ├── Venue browsing
   ├── Package selection
   ├── Booking customization
   └── Booking tracking

Manager Portal
   ├── Visit approval
   ├── Call confirmation
   ├── Payment confirmation
   ├── Calendar review
   └── Task handling

Admin Portal
   ├── Dashboard analytics
   ├── User management
   ├── Venue/package management
   ├── Workflow monitoring
   └── Reports/system tools
```

---

## 🛠 Technology Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Tailwind CSS, JavaScript |
| Build Tool | Vite |
| Database | MySQL 8+ recommended, SQLite supported for testing |
| Authentication | Laravel Auth + role middleware |
| CI | GitHub Actions |

---

## 🚀 Installation

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

### 3. Create environment file

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Example MySQL configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wmdemo
DB_USERNAME=root
DB_PASSWORD=
DEMO_USER_PASSWORD=change-this-local-password
```

### 5. Build fresh demo database

```bash
php artisan migrate:fresh --seed
```

For an existing database:

```bash
php artisan migrate
php artisan db:seed
```

### 6. Link storage and clear cache

```bash
php artisan storage:link
php artisan optimize:clear
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start local server

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

---

## 🔐 Demo Accounts

Set this in `.env` before running seeders:

```env
DEMO_USER_PASSWORD=your-local-demo-password
```

Seeder creates:

| Role | Email | Password |
|---|---|---|
| Admin | `sandesh.nirwan@wmdemo.com` | Value from `DEMO_USER_PASSWORD` |
| Manager | `manager@wmdemo.com` | Value from `DEMO_USER_PASSWORD` |
| Customer | `customer@wmdemo.test` | Value from `DEMO_USER_PASSWORD` |

> Do not use demo credentials in production.

---

## 🧪 Development Commands

```bash
composer dump-autoload
php artisan optimize:clear
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
npm run build
```

---

## ✅ Recent Fixes

- Fixed old route names used by Blade views.
- Fixed booking form data variables.
- Improved migration safety when tables are missing.
- Added professional workflow database tables.
- Added workflow seed data.
- Added admin workflow API endpoints.
- Added GitHub Actions CI file.
- Rebuilt README structure.

---

## 🚧 Next Recommended Improvements

- Add admin dashboard UI tabs for leads, proposals, contracts, invoices, BEOs, and tasks.
- Add manager workflow/task screens.
- Add calendar conflict checking in booking form.
- Add proposal, contract, invoice action buttons.
- Add more feature tests.
- Add production backup and deployment guide.

---

## ⚙ Production Notes

Before production deployment:

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Use strong real admin passwords.
- Configure HTTPS, mail, queues, storage, backups, and database credentials.
- Run `npm run build`.
- Run migrations only after database backup.
- Review file upload validation and admin permissions.

---

<div align="center">

**Status:** Professional workflow foundation completed  
**Project Type:** Laravel Wedding/Event Management System  
**Maintainer:** Sandesh Nirwan

</div>
