# Guest House Management System

A comprehensive multi-branch Guest House Management System built with Laravel 12.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Bootstrap 5.3, jQuery 3.7
- **Tables**: Yajra DataTables (server-side) with fixed Bootstrap 5 pagination
- **Forms**: Tom Select (selects), Flatpickr (date/time pickers)
- **Notifications**: SweetAlert2 (delete confirms), PHPFlasher (success notices)
- **i18n**: Khmer / English with AJAX switching (no page refresh)
- **Database**: SQLite (default) — easily switched to MySQL/PostgreSQL via `.env`

## Modules (41 CRUDs)

**Core**: Branches, Staff, Users, Roles, Permissions, Staff Attendances
**Rooms**: Room Types, Rooms, Facilities
**Guests**: Guests, Guest Documents
**Bookings**: Bookings, Stays, Room Transfers
**Finance**: Payment Methods, Invoices, Payments, Receipts, Refunds
**Services**: Services, Service Charges
**Housekeeping**: Tasks, Checklist Items
**Maintenance**: Requests
**Inventory**: Suppliers, Stock Categories, Stock Items, Stock Movements
**Accounting**: Expense Categories, Expenses, Salaries
**Notifications**: Notifications, Templates
**Website**: Pages, Online Booking Requests
**Security**: Login Histories, Audit Logs (read-only)
**Settings**: Guest House, Code, System, Backups

## Quick Start

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file & generate key
cp .env.example .env
php artisan key:generate

# 3. Create SQLite database
touch database/database.sqlite

# 4. Run migrations and seeders
php artisan migrate --seed

# 5. Start dev server
php artisan serve
```

Open http://127.0.0.1:8000/admin/login

**Default admin credentials**:
- Username: `admin`
- Password: `password`

## Multi-Branch

The system seeds 3 branches by default (Main, Battambang, Siem Reap). The header
includes a branch switcher that scopes all DataTables to the selected branch
without a page refresh. A `branch_id` foreign key is added to all operational
tables (rooms, bookings, stays, invoices, payments, etc.).

## i18n (Khmer/English)

- Language switcher in the header (EN / KM).
- Switching is AJAX-only — translations are pushed back as JSON and applied to
  every element with `data-i18n="key"` or `data-i18n-placeholder="key"`.
- Translation files: `lang/en/messages.php` and `lang/km/messages.php`.

## Architecture

```
app/
  Http/
    Controllers/
      Admin/
        Auth/LoginController.php
        BaseCrudController.php   <- Generic CRUD parent
        BranchController.php
        ... 40 more
      BranchSwitchController.php
      LocaleController.php
    Middleware/
      AdminAuthenticate.php
      SetCurrentBranch.php
      SetLocale.php
  Models/
    Branch.php, User.php, ... 50 models
    Concerns/BelongsToBranch.php   <- Branch scope trait

resources/views/
  admin/
    auth/login.blade.php
    dashboard/index.blade.php
    layouts/
      admin_layout.blade.php
      admin_partials/
        head.blade.php
        header.blade.php
        left_sidebar.blade.php
        scripts.blade.php
    partials/
      crud_index.blade.php   <- Generic DataTables list
      crud_form.blade.php    <- Generic create/edit
      crud_show.blade.php    <- Generic detail
    branches/, staff/, users/, ...   <- 41 module folders, each with index/create/edit/show

routes/web.php   <- All admin routes via Route::resource()

public/assets/
  css/app.css   <- Custom CSS
  js/app.js     <- jQuery glue: AJAX, Flatpickr, Tom Select, DataTables, locale & branch switching

database/
  migrations/
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2026_05_06_000000_create_guest_house_management_system_tables.php  <- (user-provided)
    2026_05_06_000001_create_branches_and_link_tables.php              <- branches + branch_id FKs
  seeders/
    DatabaseSeeder.php
```

## How it works

### Generic CRUD

Each module has a thin controller that extends `BaseCrudController` and declares:

- `$modelClass`, `$route`, `$viewPath`, `$titleKey`
- `$hasBranchScope` (true for branch-scoped models)
- `$eagerLoad` (relations to eager load for DataTables)
- `rules(Request, ?Model)` — validation
- `tableColumns()` — Yajra DataTables column descriptors
- `formViewData(Model)` — fields for the auto-generated form

The shared partials (`crud_index`, `crud_form`, `crud_show`) consume those
arrays and render the entire UI — including:

- Yajra server-side DataTable (with action column rendered server-side)
- Flatpickr for any field with `type=date|datetime|time|month`
- Tom Select for any `type=select`
- SweetAlert2 confirm + AJAX DELETE for the row delete buttons
- PHPFlasher success notice on store/update
- Bootstrap 5 pagination

### Locale switch (no page refresh)

`POST /locale/switch` returns a JSON envelope:

```json
{ "status": "success", "locale": "km", "translations": { ... full messages.php ... } }
```

`public/assets/js/app.js` then iterates over `[data-i18n]` and
`[data-i18n-placeholder]` and rewrites their text without reloading the page.
DataTables are reloaded via `.ajax.reload(null, false)` so the action column
re-renders in the new language.

### Branch switch

`POST /branch/switch` stores the selected `branch_id` in the session.
`SetCurrentBranch` middleware shares `currentBranch` and `availableBranches`
with every view. `BelongsToBranch` trait + `BaseCrudController::applyBranchScope`
auto-filter any model query to the active branch.

## Running tests

```bash
php artisan test
```

## License

MIT
