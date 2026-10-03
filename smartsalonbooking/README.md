# Smart Salon - Beauty Salon Booking System

A Laravel booking management application for a beauty salon: customers browse
services, pick a specialist and an open time slot, and book online. Admins
and receptionists manage services, employees, customers and appointments
from a glassmorphism-styled dashboard.

Built with **PHP 8.2+, Laravel, Blade, plain CSS3 and vanilla JavaScript only**
- no React/Vue/Livewire/Inertia, no Tailwind/Bootstrap, no npm build step.
All styling lives in `public/css/app.css` and is loaded with a plain
`<link>` tag, so there is nothing to compile.

## Design

The brand palette was refreshed from the original brief into a deeper,
more boutique rose/plum system (see `:root` in `public/css/app.css`):
primary `#9d1b56`, primary-dark `#6e1039`, accent `#e0577e`, a warm gold
`#c9a24b` for highlights, on a soft blush-white background. The
glassmorphism language (blurred glass cards, soft shadows, 18px radius,
rounded pills, hover lift, button ripple, scroll-reveal) from the brief is
kept throughout.

## What's included (Phase 1 - fully working)

- Auth: register, login, "remember me", forgot/reset password, profile,
  logout. Roles: `admin`, `receptionist`, `customer`.
- Public site: Home, Services (search + category filter), Service detail,
  Gallery, Pricing, Team, Testimonials, Contact form, custom 404 page.
- Booking engine: 5-step guided form (service → specialist → date & time →
  your details → confirm), live AJAX slot lookup, automatic
  `SBS-2026-0001`-style booking numbers, and **real double-booking
  prevention** - a duration-aware availability check plus a database unique
  constraint as a last-resort safety net.
- Customer dashboard: upcoming appointments, full booking history with
  filters, cancel, reschedule (with its own live slot picker), profile +
  avatar upload, notifications.
- Admin/receptionist dashboard: stat cards, 7-day revenue & bookings
  Chart.js charts, recent bookings, upcoming schedule, recent customers.
- Admin CRUD: Services, Categories, Employees (with working days/hours and
  which services they perform), Customers, Appointments (status workflow:
  pending → confirmed → completed / cancelled / rescheduled), Gallery,
  Testimonials. Search/filter on every list, JS confirm dialogs on delete.
- Validation: Form Request classes for every write action (uniqueness,
  future-date-only booking, image mime/size limits, etc).
- Seed data: 5 categories, 10 services, 5 employees, 30 customers, ~50
  appointments (generated through the same availability rules real bookings
  use, so nothing overlaps), 12 gallery images, 6 testimonials. Images are
  generated locally with PHP's GD extension as colored placeholders (see
  "About the demo images" below) so the app looks complete out of the box.

## Not built yet (documented, not started)

These were in the original brief but intentionally left out of this pass so
the core above could be built to a high standard - see the code for natural
extension points:

- PDF/Excel report exports (Daily/Weekly/Monthly/Revenue reports)
- Real outgoing email (password reset currently logs to
  `storage/logs/laravel.log` via `MAIL_MAILER=log`)
- A dedicated Settings admin screen (the `settings` table + `Setting` model
  already exist and power the footer/contact page - only the admin UI to
  edit them is missing)
- Dark mode toggle
- An admin "create a walk-in booking" form (staff currently use the same
  public booking form; only customers/guests book through it today)

## Getting started

This project is delivered **without** the `vendor/` folder, same as any
real Laravel project (it's always `.gitignore`d) - you install dependencies
once with Composer on your own machine.

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy the environment file and generate an app key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database file (already included, but if it's ever
#    missing or you started from a fresh clone):
touch database/database.sqlite

# 4. Run migrations and seed demo data
php artisan migrate --seed

# 5. Link storage so uploaded/seeded images are servable
php artisan storage:link
# (a public/storage symlink is already included in this download - only
# run this if that symlink didn't survive your unzip/transfer)

# 6. Serve the app
php artisan serve
```

Visit `http://127.0.0.1:8000`.

### Demo logins

| Role         | Email                          | Password |
|--------------|---------------------------------|----------|
| Admin        | admin@smartsalon.test           | password |
| Receptionist | receptionist@smartsalon.test    | password |
| Customer     | customer@smartsalon.test        | password |

(30 more random customers are also seeded - all use `password` too.)

### Using MySQL instead of SQLite

Comment out the `DB_CONNECTION=sqlite` line in `.env` and uncomment the
MySQL block right below it, then create the database and re-run
`php artisan migrate --seed`.

## About the demo images

Services, employees, gallery photos and testimonial avatars are seeded with
colored placeholder JPEGs generated on the fly by `App\Support\PlaceholderImage`
(using PHP's GD extension) rather than downloaded stock photos, because the
environment this was built in had no internet access. They're saved as real
files under `storage/app/public/...` so everything renders correctly - swap
them for real photos any time through the admin panel (Services, Employees,
Gallery, Testimonials all support image upload, JPG/PNG up to 2MB).

## Project structure

Standard Laravel layout - the salon-specific code lives in:

```
app/Models/                  Category, Service, Employee, Customer, Appointment,
                              Payment, Setting, Gallery, Testimonial, Notification, User
app/Services/AvailabilityService.php   All the "is this employee free?" logic
app/Support/PlaceholderImage.php       Generates demo images with GD
app/Http/Controllers/                  Public, Auth/, Customer/, Admin/
app/Http/Requests/                     One Form Request per write action
app/Http/Middleware/EnsureUserHasRole.php   role:admin / role:admin,receptionist
database/migrations/                   All 14 tables + relationships
database/seeders/                      One seeder per entity, orchestrated by DatabaseSeeder
resources/views/                       layouts/, components/, admin/, customer/, auth/, booking/
public/css/app.css                     The entire design system (one file, no build step)
public/js/{app,booking,admin-charts}.js
```

## How double-booking prevention actually works

1. **Server-side availability generation** (`AvailabilityService::getAvailableSlots`):
   for a given employee/service/date, slots are generated across the
   employee's working hours, spaced by the service's own duration, and any
   slot overlapping an existing active appointment (pending/confirmed/
   rescheduled) is skipped. This powers both the booking form and the
   reschedule screen.
2. **Re-check at save time** (`AvailabilityService::isSlotStillFree`,
   called from `BookingController::store`): right before inserting, inside
   a database transaction, so two people can't both grab a slot that looked
   free a second earlier.
3. **Database unique constraint** on `(employee_id, appointment_date, start_time)`
   as a final safety net - if step 2 is somehow bypassed, the database
   itself refuses the duplicate row and the controller shows a friendly
   error instead of a 500.

## Verification performed in this environment

This sandbox has no network access to Packagist/npm/GitHub, so `composer
install` and booting a live server couldn't be run here. What was verified
instead: every one of the 134 PHP files passes `php -l` (no syntax errors),
every `route()` call resolves to a route actually defined in
`routes/web.php`, every `view()` call points to a Blade file that exists,
every `@extends` target exists, and every Blade control-structure directive
(`@if/@endif`, `@foreach/@endforeach`, etc.) is balanced across all 41 view
files. Two real bugs were caught and fixed this way: `Service` and
`Appointment` needed `getRouteKeyName()` overrides so that generated URLs
(slug / booking number) match the custom route-model-binding columns used
in `routes/web.php` - without that fix, links like "View Confirmation"
would have 404'd.

Please still run `php artisan test` after `composer install` (a
`BookingTest` and `PublicPagesTest` are included, covering the double-
booking rule specifically) before treating this as production-ready.
