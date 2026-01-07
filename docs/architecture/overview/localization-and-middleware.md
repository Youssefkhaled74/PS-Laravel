# Localization and Middleware

The `SetLocaleFromHeader` middleware reads `Accept-Language` and sets the application locale. Validation and error messages should be localized.

Other important middleware:
- `auth:admin|vendor|user` — Guarded routes
- `throttle` — Rate limiting
