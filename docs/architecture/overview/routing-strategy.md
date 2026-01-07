# Routing Strategy

- Routes are separated into files under `routes/api/` grouped by domain (e.g., `routes/api/auth.php`, `routes/api/vendors.php`).
- Controllers are thin and delegate to Services. Use route groups with `prefix('v1')` and middleware for guards and localization.
