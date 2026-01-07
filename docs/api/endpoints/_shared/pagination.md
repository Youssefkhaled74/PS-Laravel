# Pagination Schema

Example pagination response shape used by many list endpoints.

```json
{
  "success": true,
  "data": [ /* items */ ],
  "meta": { "current_page": 1, "per_page": 15, "total": 124 }
}
```
