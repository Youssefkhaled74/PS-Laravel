# 🚀 Vendor Authentication - Quick Start Guide

## ⚡ Setup (3 Steps)

### 1️⃣ Run Migration
```bash
php artisan migrate
```

### 2️⃣ Seed Test Data (Optional)
```bash
php artisan db:seed --class=VendorAuthSeeder
```
**Test Credentials:** `+966500000001` / `password123`

### 3️⃣ Import Postman Collection
- `postman/PS_Vendor_Auth.postman_collection.json`
- Environment: `PS_Local.postman_environment.json`

---

## 📡 API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/vendor/auth/register` | POST | ❌ No | Register new vendor |
| `/api/v1/vendor/auth/login` | POST | ❌ No | Login with phone + password |
| `/api/v1/vendor/auth/logout` | POST | ✅ Bearer | Revoke token |

---

## 📝 Register Request

**Endpoint:** `POST /api/v1/vendor/auth/register`

**Headers:**
```
Accept: application/json
Lang: en|ar
```

**Body (form-data):**
```
full_name: "Ahmed Store" (required)
phone: "+966500000001" (required, unique)
email: "ahmed@store.sa" (optional, unique)
password: "password123" (required, min:8)
password_confirmation: "password123" (required)
avatar: <file> (optional, image, max:2MB)
bio: "Best store..." (optional)
national_id: "1234567890" (optional)
national_address: "Riyadh, SA" (optional)
second_phone: "+966500000002" (optional)
lat: 24.7136 (optional)
lng: 46.6753 (optional)
```

**Response (201):**
```json
{
  "success": true,
  "message": "Vendor registered successfully.",
  "data": {
    "token": "1|abc123...",
    "vendor": { /* vendor details */ }
  }
}
```

---

## 🔐 Login Request

**Endpoint:** `POST /api/v1/vendor/auth/login`

**Headers:**
```
Accept: application/json
Content-Type: application/json
Lang: en|ar
```

**Body (JSON):**
```json
{
  "phone": "+966500000001",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logged in successfully.",
  "data": {
    "token": "2|xyz789...",
    "vendor": { /* vendor details */ }
  }
}
```

---

## 🚪 Logout Request

**Endpoint:** `POST /api/v1/vendor/auth/logout`

**Headers:**
```
Accept: application/json
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully."
}
```

---

## 📂 Key Files Created

```
app/
├── Services/Vendor/Auth/
│   └── VendorAuthService.php
├── Http/
│   ├── Controllers/Api/V1/Vendor/Auth/
│   │   ├── VendorRegisterController.php
│   │   ├── VendorLoginController.php
│   │   └── VendorLogoutController.php
│   ├── Requests/Api/V1/Vendor/Auth/
│   │   ├── RegisterVendorRequest.php
│   │   └── LoginVendorRequest.php
│   └── Resources/Api/V1/Vendor/
│       └── VendorResource.php
└── Models/
    └── Vendor.php (updated)

routes/api/vendor/
└── auth.php

database/
├── migrations/
│   └── 2026_01_15_200000_update_vendors_table_for_auth.php
└── seeders/
    └── VendorAuthSeeder.php

postman/
└── PS_Vendor_Auth.postman_collection.json

docs/
└── VENDOR_AUTH.md
```

---

## 🧪 Quick Test (cURL)

### Register:
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/auth/register \
  -H "Accept: application/json" \
  -F "full_name=Test Vendor" \
  -F "phone=+966500000099" \
  -F "password=password123" \
  -F "password_confirmation=password123"
```

### Login:
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/auth/login \
  -H "Content-Type: application/json" \
  -d '{"phone":"+966500000001","password":"password123"}'
```

### Logout:
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🌍 Language Support

Add `Lang` header:
- `Lang: en` → English
- `Lang: ar` → Arabic

**Example:**
```bash
curl -H "Lang: ar" ...
```

---

## 🔑 Vendor Fields Returned

```json
{
  "id": 1,
  "full_name": "Ahmed Store",
  "email": "ahmed@store.sa",
  "phone": "+966500000001",
  "second_phone": "+966500000002",
  "avatar_url": "http://127.0.0.1:8000/uploads/vendor/avatars/file.jpg",
  "bio": "Best store in town",
  "national_id": "1234567890",
  "national_address": "Riyadh, Saudi Arabia",
  "lat": "24.71360000",
  "lng": "46.67530000",
  "status": "pending",
  "created_at": "2026-01-15T20:00:00.000000Z"
}
```

---

## ✅ Status

- ✅ **Complete** - Ready for production
- ✅ **Tested** - All endpoints working
- ✅ **Documented** - Full API docs available
- ✅ **Localized** - EN + AR support
- ✅ **Secure** - Sanctum + validation

---

## 📚 Full Documentation

See: **`docs/VENDOR_AUTH.md`**

---

## 🎯 What's NOT Included (As Requested)

- ❌ OTP verification
- ❌ Business profile completion
- ❌ Package selection/payment
- ❌ Document verification
- ❌ Password reset
- ❌ Email verification

**This milestone:** Register + Login + Logout ONLY ✅

---

**Ready to use!** 🎉
