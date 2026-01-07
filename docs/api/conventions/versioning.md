# Versioning

APIs are versioned via the URL (e.g., `/api/v1/...`). When making breaking changes, increment the version and keep the previous version available until clients can migrate.

Guidelines
- Add `v1`, `v2` prefixes in `routes/api/` route groups.
- Document breaking changes in migration notes and ADRs.
