# Vendor Authentication API Documentation

## Overview
This document describes the Vendor Authentication system for the PS Laravel project. It includes Register, Login, and Logout endpoints for vendor accounts.

## 🎯 Features Implemented
- ✅ Vendor Registration with profile image upload
- ✅ Vendor Login with phone + password
- ✅ Vendor Logout (token revocation)
- ✅ Sanctum token-based authentication
- ✅ Multi-language support (Arabic/English)
- ✅ Clean architecture (Service Layer + API Resources)
- ✅ Modular routing structure
- ✅ Postman collection for testing

## 📁 File Structure

### **Controllers**
```
app/Http/Controllers/Api/V1/Vendor/Auth/
├── VendorRegisterController.php
├── VendorLoginController.php
└── VendorLogoutController.php
```

### **Requests (Form Validation)**
```
app/Http/Requests/Api/V1/Vendor/Auth/
├── RegisterVendorRequest.php
└── LoginVendorRequest.php
```

### **Services (Business Logic)**
```
app/Services/Vendor/Auth/
└── VendorAuthService.php
```

### **Resources (API Response Format)**
```
app/Http/Resources/Api/V1/Vendor/
└── VendorResource.php
```

### **Routes**
```
routes/api/vendor/
└── auth.php
```

### **Migrations**
```
database/migrations/
├── 2026_01_07_124000_create_vendors_table.php
└── 2026_01_15_200000_update_vendors_table_for_auth.php
```

### **Seeders**
```
database/seeders/
└── VendorAuthSeeder.php
```

### **Postman**
```
postman/
├── PS_Vendor_Auth.postman_collection.json
└── PS_Local.postman_environment.json (updated)
```

---

## 🗄️ Database Schema

### **vendors table**
| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| id | bigint | NO | Primary key |
| full_name | string | NO | Vendor full name |
| name | string | YES | Business name (legacy) |
| email | string | YES | Email (unique) |
| phone | string | NO | Phone (unique) |
| second_phone | string | YES | Secondary phone |
| whatsapp_phone | string | YES | WhatsApp number |
| password | string | NO | Hashed password |
| avatar_path | string | YES | Relative path to avatar |
| avatar | string | YES | Legacy avatar field |
| bio | text | YES | Vendor bio/description |
| national_id | string | YES | National ID number |
| national_address | string | YES | National address |
| location_text | string | YES | Legacy location field |
| lat | decimal(10,8) | YES | Latitude |
| lng | decimal(11,8) | YES | Longitude |
| status | string | NO | active/inactive/pending |
| phone_verified_at | timestamp | YES | Phone verification time |
| email_verified_at | timestamp | YES | Email verification time |
| created_at | timestamp | NO | Creation timestamp |
| updated_at | timestamp | NO | Update timestamp |

---

## 🔐 Authentication Configuration

### **config/auth.php**
Added vendor guard and provider:

```php
'guards' => [
    'vendor' => [
        'driver' => 'sanctum',
        'provider' => 'vendors',
    ],
],

'providers' => [
    'vendors' => [
        'driver' => 'eloquent',
        'model' => App\Models\Vendor::class,
    ],
],
```

### **Vendor Model Updates**
- Added `HasApiTokens` trait for Sanctum support
- Updated `$fillable` array with new fields
- Added `$casts` for timestamps and coordinates
- Hidden `password` field in responses

---

## 📡 API Endpoints

### **Base URL**
```
http://127.0.0.1:8000/api/v1/vendor/auth
```

### **1. Register Vendor**
**Endpoint:** `POST /register`

**Headers:**
```
Accept: application/json
Lang: en|ar
```

**Body (multipart/form-data):**
```json
{
    "full_name": "Ahmed Store",           // required, min:3
    "phone": "+966500000001",             // required, unique
    "email": "ahmed@example.com",         // optional, unique
    "second_phone": "+966500000002",      // optional
    "password": "password123",            // required, min:8
    "password_confirmation": "password123", // required
    "bio": "Best electronics store",      // optional, max:1000
    "national_id": "1234567890",          // optional
    "national_address": "Riyadh, SA",     // optional
    "lat": 24.7136,                       // optional, -90 to 90
    "lng": 46.6753,                       // optional, -180 to 180
    "avatar": <file>                      // optional, image, max:2MB
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Vendor registered successfully.",
    "data": {
        "token": "1|abc123...",
        "vendor": {
            "id": 1,
            "full_name": "Ahmed Store",
            "email": "ahmed@example.com",
            "phone": "+966500000001",
            "second_phone": "+966500000002",
            "avatar_url": "http://127.0.0.1:8000/uploads/vendor/avatars/file.jpg",
            "bio": "Best electronics store",
            "national_id": "1234567890",
            "national_address": "Riyadh, SA",
            "lat": "24.71360000",
            "lng": "46.67530000",
            "status": "pending",
            "created_at": "2026-01-15T20:00:00.000000Z"
        }
    },
    "errors": null,
    "meta": null
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validation failed.",
    "data": null,
    "errors": {
        "phone": ["Phone already exists."],
        "password": ["Password confirmation does not match."]
    },
    "meta": null
}
```

---

### **2. Login Vendor**
**Endpoint:** `POST /login`

**Headers:**
```
Accept: application/json
Lang: en|ar
```

**Body (application/json):**
```json
{
    "phone": "+966500000001",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logged in successfully.",
    "data": {
        "token": "2|xyz789...",
        "vendor": {
            "id": 1,
            "full_name": "Ahmed Store",
            "email": "ahmed@example.com",
            "phone": "+966500000001",
            "second_phone": "+966500000002",
            "avatar_url": "http://127.0.0.1:8000/uploads/vendor/avatars/file.jpg",
            "bio": "Best electronics store",
            "national_id": "1234567890",
            "national_address": "Riyadh, SA",
            "lat": "24.71360000",
            "lng": "46.67530000",
            "status": "active",
            "created_at": "2026-01-15T20:00:00.000000Z"
        }
    },
    "errors": null,
    "meta": null
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid phone or password.",
    "data": null,
    "errors": null,
    "meta": null
}
```

---

### **3. Logout Vendor**
**Endpoint:** `POST /logout`

**Headers:**
```
Accept: application/json
Lang: en|ar
Authorization: Bearer {vendor_token}
```

**Body:** None

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logged out successfully.",
    "data": null,
    "errors": null,
    "meta": null
}
```

---

## 🧪 Testing Instructions

### **1. Run Migrations**
```bash
php artisan migrate
```

### **2. Seed Test Data (Optional)**
```bash
php artisan db:seed --class=VendorAuthSeeder
```

This creates 3 test vendors:
- Phone: `+966500000001`, Password: `password123` (Active)
- Phone: `+966500000011`, Password: `password123` (Pending)
- Phone: `+966500000021`, Password: `password123` (Active)

### **3. Test with Postman**
1. Import `postman/PS_Vendor_Auth.postman_collection.json`
2. Import/Update `postman/PS_Local.postman_environment.json`
3. Select "PS Local" environment
4. Run "Register Vendor" request
5. Run "Login Vendor" request (token auto-saves to `vendor_token`)
6. Run "Logout Vendor" request (uses saved token)

### **4. Manual cURL Testing**

**Register:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/auth/register \
  -H "Accept: application/json" \
  -H "Lang: en" \
  -F "full_name=Test Vendor" \
  -F "phone=+966500000099" \
  -F "email=test@vendor.sa" \
  -F "password=password123" \
  -F "password_confirmation=password123"
```

**Login:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Lang: en" \
  -d '{"phone":"+966500000001","password":"password123"}'
```

**Logout:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/auth/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🌍 Localization

### **Supported Languages**
- English (`en`)
- Arabic (`ar`)

### **How to Set Language**
Include `Lang` header in requests:
```
Lang: en   (English)
Lang: ar   (Arabic)
```

### **Translation Keys**
Located in:
- `resources/lang/en/api.php`
- `resources/lang/ar/api.php`

**Vendor Auth Messages:**
- `vendor.auth.register_success`
- `vendor.auth.register_failed`
- `vendor.auth.login_success`
- `vendor.auth.invalid_credentials`
- `vendor.auth.logged_out`
- `vendor.auth.logout_failed`

**Validation Messages:**
- `validation.full_name_required`
- `validation.phone_required`
- `validation.phone_unique`
- `validation.password_min`
- `validation.password_confirmed`
- etc.

---

## 🏗️ Architecture

### **Service Layer Pattern**
All business logic is in `VendorAuthService`:
- `register(array $data): array`
- `login(array $data): array`
- `logout(Vendor $vendor): void`

Controllers are thin and only:
1. Validate via FormRequest
2. Call Service method
3. Return formatted response via ApiResponseTrait

### **Upload Handling**
Uses `UploadsTrait` from existing project:
- Uploads to `public/uploads/vendor/avatars/`
- Stores relative path in database (`avatar_path`)
- Returns full URL in API response (`avatar_url`)

### **Response Format**
All responses use `ApiResponseTrait`:
```php
return $this->success($data, 'message_key', $meta, $code);
return $this->error('error_key', $errors, $code);
```

Standard format:
```json
{
    "success": true|false,
    "message": "Localized message",
    "data": {...},
    "errors": {...},
    "meta": {...}
}
```

---

## 🔒 Security Features

1. **Password Hashing**: Uses `Hash::make()` for bcrypt hashing
2. **Token Authentication**: Sanctum personal access tokens
3. **Unique Constraints**: Phone and email are unique in database
4. **Validation**: FormRequest classes validate all inputs
5. **Hidden Fields**: Password never returned in responses
6. **CSRF Protection**: Not required for API routes
7. **Rate Limiting**: Can be added to routes as needed

---

## 📋 Status Workflow

Vendors can have three statuses:
- `pending` - Default status after registration (requires admin approval)
- `active` - Can access full vendor features
- `inactive` - Account disabled

**Note:** Status management is handled by admin panel (not in this milestone).

---

## 🚀 Next Steps (Future Milestones)

This milestone implements **ONLY** Register/Login/Logout. Future features:
- ❌ OTP verification (not in this milestone)
- ❌ Business profile completion (not in this milestone)
- ❌ Package selection/payment (not in this milestone)
- ❌ Document uploads for verification (not in this milestone)
- ❌ Password reset (future milestone)
- ❌ Email verification (future milestone)

---

## 🐛 Troubleshooting

### **Issue: Token not working**
- Ensure `Authorization: Bearer {token}` header is set correctly
- Check token hasn't been revoked via logout
- Verify Sanctum middleware is applied to protected routes

### **Issue: Avatar upload fails**
- Check `public/uploads/vendor/avatars/` directory exists and is writable
- Verify file is under 2MB
- Ensure file is a valid image format

### **Issue: Validation errors in Arabic**
- Set `Lang: ar` header in request
- Check translation keys exist in `resources/lang/ar/api.php`

### **Issue: "User not authenticated" error**
- Ensure you're using the correct guard: `auth:sanctum`
- Check `vendor` guard is configured in `config/auth.php`
- Verify `HasApiTokens` trait is added to Vendor model

---

## 📞 API Response Examples

### **Arabic Response (Lang: ar)**
```json
{
    "success": true,
    "message": "تم تسجيل التاجر بنجاح.",
    "data": {
        "token": "...",
        "vendor": {...}
    }
}
```

### **Validation Error (Lang: ar)**
```json
{
    "success": false,
    "message": "فشل التحقق من البيانات.",
    "errors": {
        "phone": ["رقم الهاتف موجود مسبقاً."]
    }
}
```

---

## ✅ Checklist

- [x] Database migrations created and tested
- [x] Vendor model updated with HasApiTokens
- [x] Auth guard and provider configured
- [x] Service layer implemented (VendorAuthService)
- [x] Controllers created (Register, Login, Logout)
- [x] Form requests created with validation
- [x] API Resource created (VendorResource)
- [x] Routes configured in modular structure
- [x] Translations added (English + Arabic)
- [x] Postman collection created
- [x] Postman environment updated
- [x] Seeder created for test data
- [x] Documentation completed

---

**Version:** 1.0  
**Date:** January 15, 2026  
**Project:** PS Laravel  
**Milestone:** Vendor Authentication (Register + Login)
