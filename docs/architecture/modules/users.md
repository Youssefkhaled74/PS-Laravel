# Users Module

1) Overview
- Handles user registration, authentication, profiles, and addresses.

2) Tables / Models
- `users` — core user model.
- `addresses` — user addresses.

3) Services involved
- `app/Services/AuthService.php` — login/register
- `app/Services/UserService.php` — profile updates

4) Key flows
- Registration -> service creates user -> returns resource

5) Permissions/middleware
- `auth:user` guard for protected endpoints

6) Common pitfalls
- Ensure unique email constraint and proper exception handling.

7) Future improvements
- Add social auth providers.
