# Pagination

Standard pagination format used across endpoints (resource collections):

```json
{
  "success": true,
  "data": [ /* items */ ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 124
  }
}
```

Use `per_page` query param to control page size (server may enforce limits).
