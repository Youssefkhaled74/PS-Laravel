# Authentication

Authentication is token-based. Each role (user, vendor, admin) uses its own guard and token issuance endpoints.

Flow
- Client calls login/register endpoint and receives a bearer token.
- Include `Authorization: Bearer <token>` in subsequent requests.

Notes
- Tokens are validated by middleware; ensure the correct guard is used in route groups.
