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
2. **Environment** – copy `.env.example` to `.env` and set your MySQL credentials (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). If you prefer SQLite locally, change `DB_CONNECTION=sqlite` and point `DB_DATABASE` at `database/database.sqlite`.
   ```bash
   php artisan key:generate
   ```
3. **Database & demo data**
   ```bash
   php artisan migrate --seed
   ```
   Demo admin credentials: `admin@vaperoo.test` / `ChangeMe123!`

4. **Schema snapshot** – if you cannot run artisan migrations on your host, import `database/schema/mysql-schema.sql` into MySQL to create every table (roles, permissions, integrations, orders, catalog, queues, cache, etc.) exactly as the migrations define them.

5. **Serve the application**
   ```bash
   php artisan serve
   ```
   Visit [http://localhost:8000](http://localhost:8000).

## Plesk shared hosting deployment (no SSH)

1. **Create a subdomain or domain** in Plesk and ensure PHP ≥ 8.2 with the `pdo_mysql` extension enabled.
2. **Upload project files** either via the Plesk Git integration (Websites & Domains → Git) or by uploading a ZIP through the File Manager and extracting it into `/httpdocs`.
3. **Point the document root** to the `public/` directory (e.g. set document root to `/httpdocs/public`).
4. **Install PHP dependencies**:
   - Preferred: use *Websites & Domains → PHP Composer* to run `composer install --no-dev --optimize-autoloader` from the UI.
   - Alternative: run `composer install` locally, then upload the generated `vendor/` directory.
5. **Configure the environment**:
   - Copy `.env.example` to `.env` using the File Manager.
   - Generate an application key via *Websites & Domains → PHP Composer → Execute command* with `php artisan key:generate` (runs once), or run it locally and paste the resulting `APP_KEY` into `.env`.
   - Fill in the MySQL credentials supplied by Plesk (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and set `APP_ENV=production`, `APP_URL=https://your-domain`.
6. **Build frontend assets** locally (`npm install && npm run build`) and upload the `public/build` directory, or use Plesk's Node.js extension if available.
7. **Set storage permissions** via the File Manager so `storage/` and `bootstrap/cache/` are writable (change permissions to 775 or grant write access to the web user).
8. **Run migrations/seeders** by creating a one-off Scheduled Task in Plesk that executes `php /var/www/vhosts/<domain>/httpdocs/artisan migrate --force` (replace the path accordingly). Repeat for `db:seed` if you want demo data, then disable the task. If scheduled tasks are unavailable, import the bundled `database/schema/mysql-schema.sql` via phpMyAdmin to create the schema manually.
9. **Scheduler & queue** – configure recurring Scheduled Tasks for `php artisan schedule:run` (every minute) and `php artisan queue:work --stop-when-empty` if background jobs are required.

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
| `integrations` | Persists payment, email, SMS, and WhatsApp gateway settings managed via the admin UI. | `name`, `type` (`payment`, `email`, `sms`, `whatsapp`), `driver` (`builtin`, `custom-http`), `provider`, `settings` (JSON), `endpoint_url`, `endpoint_method`, `endpoint_headers`, `is_active` |

The demo seeder provisions the following staff roles with recommended permissions:

- **Super Administrator** – access to everything (roles, integrations, catalog, orders).
- **Administrator** – day-to-day operations plus gateway management.
- **Operations Manager** – fulfilment, customer service, dashboard reporting.
- **Product Manager** – catalog, categories, promotional coupons.
- **Customer** – default role automatically applied to new shoppers.

Invite new team members through the admin → **Team Members** screen to assign roles and optionally grant direct permissions. Gateway credentials for Stripe/Postmark/Twilio/WhatsApp (or custom providers) are configured via admin → **Gateways**, stored in the `integrations` table, and can be consumed by future payment/notification services.

- Built-in drivers simulate well-known providers while you develop locally.
- Custom HTTP drivers let you point to bespoke scripts hosted on shared Plesk servers by capturing endpoint URL, method, headers, and payload templates right in the admin.
- Checkout automatically lists every active payment integration so customers can pick between native providers and custom scripts without code changes.

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
