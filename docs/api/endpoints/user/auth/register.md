# Register

1) Purpose
- Create a new user account.

2) URL + Method
- `POST /api/v1/auth/register`

3) Auth
- Guest

4) Headers
- `Accept: application/json`
4) Request body
```json
{
  "name": "User",
  "email": "user@example.com",
  "password": "secret"
}
```

6) Validation rules
- `name`: required
- `email`: required, email, unique
- `password`: required, min:6

7) Success response
```json
{
  "success": true,
  "data": { "user": { /* user resource */ } },
  "message": "Created"
}
```

8) Error responses
- 422 validation error

9) Notes
- Account verification may be required depending on configuration.
