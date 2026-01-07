# Project Structure Overview

This document explains the major folders and where logic lives in PS (Laravel 12).

Key folders
- `app/Http/Controllers/` — Thin controllers that route requests to services.
- `app/Services/` — Primary location for business logic. All API behavior should be implemented here.
- `app/Models/` — Eloquent models and relationships.
- `app/Http/Requests/` — FormRequest classes for validation.
- `app/Http/Resources/` — API Resources used to format responses.
- `routes/api/` — API route files grouped by domain (user, vendor, admin...).
- `public/uploads/` — File upload storage (publicly available via URLs).

Design notes
- Services encapsulate use-cases, accept simple arrays or DTOs, and return models or data arrays consumed by controllers.
- Responses are transformed through `ApiResponseTrait` to enforce consistent shape and localization.
