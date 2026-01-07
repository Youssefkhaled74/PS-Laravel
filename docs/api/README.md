# API Documentation

This directory describes the public API surface of the PS application.

Organization
- `conventions/` — Global API conventions (headers, errors, pagination, auth, uploads)
- `endpoints/` — Grouped endpoint documentation by resource and role (user, vendor, admin)
- `openapi/` — OpenAPI spec placeholder and related artifacts

Format and standards
- Every endpoint doc follows the standard sections: Purpose; URL + Method; Auth; Headers; Request; Validation; Success; Errors; Notes.
- All request/response examples are JSON and follow localized responses with the `Accept-Language` header.

Shared schemas
- See `docs/api/endpoints/_shared/` for pagination and error shapes.
