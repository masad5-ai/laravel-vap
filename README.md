# Vaperoo Commerce

A Laravel 12 ecommerce starter tailored for vape and lifestyle retailers. The application ships with a curated storefront inspired by Vaperoo, Uncle V and Vices, an operations-ready admin console, seeded demo catalog, and extensible architecture to support Australian compliance requirements.

## Features

- **Modern storefront** powered by Blade + Tailwind with featured drops, faceted product search, SEO-friendly slugs, and cart / checkout flows.
- **Admin console** for managing products, categories, coupons, orders, team roles, permissions, and gateway credentials from one interface.
- **Session cart + coupons** including fixed/percentage discounts, shipping rules, GST calculation and demo payment workflows.
- **Order pipeline** that tracks fulfilment, payment, tracking data, and decrementing inventory stock.
- **Seeded data** via `php artisan migrate:fresh --seed` providing sample categories, products, coupons, customers, historic orders, and baseline roles/permissions.
- **Breeze authentication** (login, registration, password reset) with an elevated storefront layout and admin-only middleware.

## Local development

1. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build # or npm run dev for hot reloading
   ```
2. **Environment** – copy `.env.example` to `.env` and update database credentials. For SQLite keep the generated `database/database.sqlite` file.
   ```bash
   php artisan key:generate
   ```
3. **Database & demo data**
   ```bash
   php artisan migrate --seed
   ```
   Demo admin credentials: `admin@vaperoo.test` / `ChangeMe123!`

4. **Serve the application**
   ```bash
   php artisan serve
   ```
   Visit [http://localhost:8000](http://localhost:8000).

## Plesk shared hosting deployment

1. **Create a subdomain or domain** in Plesk and enable SSH access.
2. **Upload project files** either by Git (recommended) or via SFTP:
   - In Plesk go to *Websites & Domains → Git* and connect this repository, or
   - Upload the repository contents to the desired document root (e.g. `/httpdocs`).
3. **Set document root** to the `public/` directory (e.g. `/httpdocs/public`).
4. **Install PHP dependencies** on the server (Plesk provides Composer):
   ```bash
   cd ~/httpdocs
   composer install --no-dev --optimize-autoloader
   ```
5. **Copy environment config**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update database credentials with the Plesk-provided MySQL details and set `APP_ENV=production`, `APP_URL=https://your-domain`.
6. **Build frontend assets**
   - If Node is available: `npm install && npm run build`
   - Otherwise, build assets locally and upload the `public/build` directory.
7. **Set storage permissions** for `storage/` and `bootstrap/cache/` (Plesk UI → File Manager → change permissions or via SSH `chmod -R 775 storage bootstrap/cache`).
8. **Run migrations and seeders (optional)**
   ```bash
   php artisan migrate --seed
   ```
9. **Scheduler & queue** – if needed, configure a scheduled task in Plesk to run `php artisan schedule:run` every minute and set up Supervisor or Plesk Task for `php artisan queue:work`.

## Testing

Run feature and unit tests with:
```bash
php artisan test
```

## Access control & integration schema

| Table | Purpose | Key columns |
| --- | --- | --- |
| `roles` | Stores reusable role definitions. | `name`, `slug`, `description`, `is_default` |
| `permissions` | Fine-grained capabilities that can be attached to roles or directly to users. | `name`, `slug`, `description` |
| `role_user` | Pivot table assigning roles to users. | `role_id`, `user_id` |
| `permission_role` | Pivot table linking permissions to roles. | `permission_id`, `role_id` |
| `permission_user` | Allows direct permissions on a user in addition to role inheritance. | `permission_id`, `user_id` |
| `integrations` | Persists payment, email, SMS, and WhatsApp gateway settings managed via the admin UI. | `name`, `type` (`payment`, `email`, `sms`, `whatsapp`), `provider`, `settings` (JSON key/value), `is_active` |

The demo seeder provisions the following staff roles with recommended permissions:

- **Super Administrator** – access to everything (roles, integrations, catalog, orders).
- **Administrator** – day-to-day operations plus gateway management.
- **Operations Manager** – fulfilment, customer service, dashboard reporting.
- **Product Manager** – catalog, categories, promotional coupons.
- **Customer** – default role automatically applied to new shoppers.

Invite new team members through the admin → **Team Members** screen to assign roles and optionally grant direct permissions. Gateway credentials for Stripe/Postmark/Twilio/WhatsApp (or custom providers) are configured via admin → **Gateways**, stored in the `integrations` table, and can be consumed by future payment/notification services.

## Project structure highlights

- `app/Services/CartService.php` – encapsulates cart, coupon, and totals logic.
- `app/Http/Controllers/Storefront/*` – public storefront flows.
- `app/Http/Controllers/Admin/*` – admin CRUD endpoints protected by role + permission middleware.
- `database/seeders/DemoSeeder.php` – seeds demo catalogue, coupons, users, and orders.
- `resources/views/storefront` – hero homepage, product catalogue, checkout, and account views.

## Next steps

- Integrate real payment providers (Stripe/Crypto) using Laravel Cashier or SDKs.
- Configure email notifications (`OrderPlaced`, `ResetPassword`) via Mailgun/SES.
- Add content management for blogs/landing pages.
- Plug gateway settings into live provider SDKs (Stripe, PayPal, Postmark, Twilio, WhatsApp Cloud API).
