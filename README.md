# Rally — Sports Apparel Store

A responsive sportswear storefront built with PHP 8.2+, MySQL, PDO, and vanilla JavaScript. Includes customer registration/login/logout, product catalog and search, session-based cart, stock-aware checkout, order history, and an admin product dashboard.

## Run locally

Requirements: PHP 8.2+ with `pdo_mysql`, MySQL 8+, and a web server (or PHP's built-in server).

1. Create a MySQL database and import `database/schema.sql`.
2. Set environment variables (or edit `config/config.php`):
   - `DB_HOST` (default `127.0.0.1`)
   - `DB_PORT` (default `3306`)
   - `DB_NAME` (default `sports_ecommerce`)
   - `DB_USER` (default `root`)
   - `DB_PASS` (default empty)
   - `APP_CURRENCY` (default `USD`)
3. From the project root, run `php scripts/create-admin.php admin@example.com 'Choose-a-strong-password'` to create an administrator. The script refuses to overwrite an existing account.
4. Start the server with `php -S 127.0.0.1:8000` and visit http://127.0.0.1:8000.

For production, serve only over HTTPS, set a strong database password, configure PHP's session cookie security, and use a production web server. The included checkout records orders using pay-on-delivery; connect a payment provider before accepting online payments. Product images use remote Unsplash URLs and can be replaced in the admin dashboard.
