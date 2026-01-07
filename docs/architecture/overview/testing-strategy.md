# Testing Strategy

Tests live under the `tests/` folder. Use Feature tests for endpoint flows and Unit tests for services.

Guidelines
- Mock external services (payments, SMS) and keep tests deterministic.
- Seeders in `database/seeders` can be used to prepare test fixtures.
