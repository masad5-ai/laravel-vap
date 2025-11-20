# Ecommerce Starter (Laravel 12 + Breeze)

This repository is the starting point for the ecommerce experience previously discussed (customer auth, cart/checkout flow, seeded catalog, and admin dashboard). It uses Laravel 12 with Breeze, Vite, Tailwind, and is compatible with MySQL-backed Plesk hosting.

## Production readiness snapshot

The project is **not production-ready yet** because core items like real payment integration, order fulfillment workflows, and production-grade hardening are still outstanding. Use this checklist to track what remains:

- ✅ Authentication, cart, checkout capture, seeded products, and admin CRUD are scaffolded.
- 🔲 Replace demo/seed data with real catalog content and assets.
- 🔲 Configure mail (transactional emails for orders/reset) in `.env`.
- 🔲 Enable HTTPS and force HTTPS redirects at the web server and Laravel middleware levels.
- 🔲 Configure a persistent session/cache store (Redis/Memcached) and queues for mail/jobs.
- 🔲 Integrate a production payment gateway (e.g., Stripe/Braintree) and secure webhooks.
- 🔲 Add tax/shipping rules, order state transitions, and inventory adjustments.
- 🔲 Harden security (CSP, rate limiting, logging/auditing, request validation, secret rotation).
- 🔲 Set up backups, monitoring/alerting, and 2FA for admin accounts.
- 🔲 Run an end-to-end QA pass (browser, API, acceptance) before go-live.

## Deploying to Plesk (Node 18 + Vite)

1. Copy `.env.example` to `.env`, set `APP_KEY` via `php artisan key:generate`, and fill MySQL credentials.
2. Run `composer install --optimize-autoloader` and `npm ci && npm run build` (Node 18-compatible).
3. Run migrations and seed demo data: `php artisan migrate --seed`.
4. Point the webroot to `public/`, ensure writable `storage` and `bootstrap/cache` directories.
5. Configure a queue worker (or fallback to `sync` in `.env`), mail transport, and HTTPS redirection.

## Local development

```bash
cp .env.example .env
php artisan key:generate
composer install
npm install
php artisan migrate --seed
npm run dev
```

## Testing

```bash
php artisan test
```

## License

MIT
