# File uploads

Uploads are stored under `public/uploads/{resource}/{id}/...` and returned as public URLs.

Best practices
- Validate mime types and size in `FormRequest` classes.
- Move files in Services (e.g., `UploadService`) and persist the public path in the model.

Example stored path:
```
public/uploads/vendors/20/avatar_1612345678.png
```
