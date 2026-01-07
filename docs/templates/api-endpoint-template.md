# API Endpoint Template

Use this template when documenting an API endpoint. Copy into `docs/api/endpoints/<module>/<endpoint>.md`.

1) Purpose
- Short description of what the endpoint does.

2) URL + Method
- Example: `POST /api/v1/auth/login`

3) Auth
- Guard: `admin|vendor|user`
- Token: `Bearer <token>`

4) Headers
- `Accept: application/json`
- `Accept-Language: en`
- `Authorization: Bearer <token>` (if required)

5) Request body
```json
{
  "email": "user@example.com",
  "password": "secret"
}
```

6) Validation rules (human readable)
- `email`: required, email
- `password`: required, min:6

7) Success response
```json
{
  "success": true,
  "data": { /* resource shape */ },
  "message": "Logged in"
}
```

8) Error responses
- 422 validation error example (see `docs/api/conventions/errors.md`)

9) Notes / Edge cases
- Rate limits, idempotency, and special behaviors.
