# Headers

Global headers used by the API:

- `Accept: application/json` — Requests should prefer JSON responses.
- `Content-Type: application/json` — For JSON request bodies.
- `Accept-Language: en|ar` — Controls localization of returned messages and labels. Middleware `SetLocaleFromHeader` reads this header.
- `Authorization: Bearer <token>` — Use for authenticated endpoints (admin/vendor/user depending on guard).
- `X-Requested-With: XMLHttpRequest` — Optional, used by some front-ends to indicate AJAX.

Examples

Request headers example:

```
Accept: application/json
Content-Type: application/json
Accept-Language: en
Authorization: Bearer eyJhbGci...
```

Notes
- Always include `Accept-Language` on requests where message localization matters (errors, validation messages).
- Authentication tokens are issued by the corresponding auth endpoints (vendor/admin/user). Use the correct guard when calling protected routes.
