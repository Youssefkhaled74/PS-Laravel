# ADR 0001 — Use Service Layer for Business Logic

Status: Accepted

Decision
- All core business logic should live in `app/Services/` rather than in controllers.

Consequences
- Controllers remain thin and simple, delegating to services.
- Services can be unit-tested independently of HTTP.
