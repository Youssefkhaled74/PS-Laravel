# Errors

All API errors follow a consistent shape produced by the `ApiResponseTrait`.

Standard error response (HTTP 4xx/5xx):

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  },
  "code": 422
}
```

Validation errors
- Returned with HTTP 422. `errors` contains field-specific messages.

Authentication errors
- Returned with HTTP 401 or 403 depending on guard and permissions.

Server errors
- Returned with HTTP 500 and a generic localized message. Detailed traces are not returned in production.

Best practices
- Clients should gracefully handle the `errors` object and display localized messages to users.
- Use `Accept-Language` to receive messages in the preferred locale.
