# PS Documentation

This `docs/` folder contains API documentation and internal architecture notes for the PS project.

Structure
- `docs/api/` — API endpoints, conventions, and OpenAPI placeholder.
- `docs/architecture/` — System architecture, modules, services, and ADRs.
- `docs/templates/` — Ready-to-copy templates for endpoints and module docs.

How to use
- Use `docs/templates/api-endpoint-template.md` when documenting any new API endpoint.
- Use `docs/templates/module-logic-template.md` to document module internals.
- Keep docs small, link to code paths (services, requests, resources) and include examples.

Conventions enforced in docs
- APIs are implemented in Services; controllers are thin.
- Responses go through the `ApiResponseTrait` and respect `Accept-Language` header.
- File uploads are stored under `public/uploads/` and returned as public URLs.

If you want to add a new doc file, follow the templates and ensure the file name uses kebab-case.
