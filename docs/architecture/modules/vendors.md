# Vendors Module

1) Overview
- Manages vendor onboarding, business profiles, documents, avatar, and package assignments.

2) Tables / Models
- `vendors`, `vendor_business_profiles`, `vendor_documents`, `vendor_package_assignments`, `vendor_payment_selections`.

3) Services involved
- `app/Services/Admin/VendorService.php`
- `app/Services/Admin/VendorBusinessService.php`
- `app/Services/Admin/VendorDocumentService.php`

4) Key flows
- Onboarding: register -> complete profile -> upload documents -> choose package -> assigned

5) Permissions/middleware
- `auth:admin` for admin actions; `auth:vendor` for vendor self-service endpoints

6) Common pitfalls
- FK ordering in migrations (ensure vendors table exists before assignments)

7) Future improvements
- Add document validation and cleanup on replace
