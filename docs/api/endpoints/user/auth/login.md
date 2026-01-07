# Login

1) Purpose
- Authenticate user and return an access token.

2) URL + Method
- `POST /api/v1/auth/login`

3) Auth
- Guest

4) Headers
- `Accept: application/json`
- `Accept-Language: en`

5) Request body
```json
{
  "email": "user@example.com",
  "password": "secret"
}
```

6) Validation rules
- `email`: required, email
- `password`: required

7) Success response
```json
{
  "success": true,
  "data": { "token": "...", "user": { /* user resource */ } },
  "message": "Logged in"
}
```

8) Error responses
- 422 validation error
- 401 invalid credentials

9) Notes
- Tokens are returned by the Service layer. See `app/Services/AuthService.php`.
