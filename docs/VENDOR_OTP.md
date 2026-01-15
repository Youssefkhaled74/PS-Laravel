# 🔐 Vendor OTP Verification - Complete Implementation

## ✅ Implementation Summary

All Vendor OTP verification features have been successfully implemented following the project's architecture patterns.

---

## 📦 FILES CREATED (10 NEW FILES)

### **1. Database**
- `database/migrations/2026_01_15_210000_create_vendor_otps_table.php`
- `app/Models/VendorOtp.php`

### **2. Services**
- `app/Services/Sms/SmsService.php` - SMS sending with log driver
- `app/Services/Vendor/Auth/VendorOtpService.php` - OTP business logic

### **3. Controllers**
- `app/Http/Controllers/Api/V1/Vendor/Auth/VendorOtpController.php`

### **4. Form Requests**
- `app/Http/Requests/Api/V1/Vendor/Auth/SendVendorOtpRequest.php`
- `app/Http/Requests/Api/V1/Vendor/Auth/VerifyVendorOtpRequest.php`

### **5. Routes**
- `routes/api/vendor/otp.php`

---

## 📝 FILES UPDATED (5 FILES)

1. `routes/api.php` - Added OTP routes
2. `config/app.php` - Added OTP_DEBUG config
3. `config/services.php` - Added SMS driver config
4. `resources/lang/en/api.php` - Added OTP translations
5. `resources/lang/ar/api.php` - Added OTP translations (Arabic)
6. `postman/PS_Vendor_Auth.postman_collection.json` - Added OTP endpoints

---

## 🚀 SETUP INSTRUCTIONS

### **Step 1: Run Migration**
```bash
php artisan migrate
```

This creates:
- `vendor_otps` table
- Adds OTP tracking columns to `vendors` table:
  - `otp_last_sent_at`
  - `otp_locked_until`
  - `otp_attempts`

### **Step 2: Configure Environment** (Optional)
Add to `.env`:
```env
# Enable OTP in API response for development (NEVER in production!)
OTP_DEBUG=true

# SMS Driver (log, twilio, nexmo, etc.)
SMS_DRIVER=log
```

### **Step 3: Test with Postman**
1. Import updated collection: `postman/PS_Vendor_Auth.postman_collection.json`
2. Use environment: `PS_Local.postman_environment.json`
3. Navigate to "Vendor OTP" folder
4. Run requests in order: Send → Verify

---

## 📡 API ENDPOINTS

### **Base URL**
```
http://127.0.0.1:8000/api/v1/vendor/otp
```

### **1. Send OTP**
**Endpoint:** `POST /send`

**Headers:**
```
Accept: application/json
Lang: en|ar
```

**Body (JSON):**
```json
{
  "phone": "+966500000001"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "OTP sent successfully to your phone.",
  "data": {
    "phone": "+966500000001",
    "resend_in_seconds": 30,
    "expires_in_seconds": 300,
    "otp": "123456"  // Only if OTP_DEBUG=true
  },
  "errors": null,
  "meta": null
}
```

**Error Response (429 - Rate Limited):**
```json
{
  "success": false,
  "message": "Please wait before requesting a new code.",
  "data": null,
  "errors": {
    "seconds": 25
  },
  "meta": null
}
```

**Error Response (429 - Too Many Requests):**
```json
{
  "success": false,
  "message": "Too many OTP requests. Please try again later.",
  "data": null,
  "errors": null,
  "meta": null
}
```

---

### **2. Verify OTP**
**Endpoint:** `POST /verify`

**Headers:**
```
Accept: application/json
Lang: en|ar
```

**Body (JSON):**
```json
{
  "phone": "+966500000001",
  "otp": "123456"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Phone verified successfully.",
  "data": {
    "token": "2|xyz789...",
    "vendor": {
      "id": 1,
      "full_name": "Ahmed Store",
      "email": "ahmed@example.com",
      "phone": "+966500000001",
      "second_phone": "+966500000002",
      "avatar_url": "http://127.0.0.1:8000/uploads/vendor/avatars/file.jpg",
      "bio": "Best store",
      "national_id": "1234567890",
      "national_address": "Riyadh, SA",
      "lat": "24.71360000",
      "lng": "46.67530000",
      "status": "pending",
      "created_at": "2026-01-15T21:00:00.000000Z"
    }
  },
  "errors": null,
  "meta": null
}
```

**Error Response (401 - Invalid OTP):**
```json
{
  "success": false,
  "message": "Invalid OTP code. Please try again.",
  "data": null,
  "errors": {
    "remaining_attempts": 3
  },
  "meta": null
}
```

**Error Response (429 - Account Locked):**
```json
{
  "success": false,
  "message": "Account temporarily locked due to too many failed attempts.",
  "data": null,
  "errors": {
    "seconds": 600
  },
  "meta": null
}
```

**Error Response (410 - OTP Expired):**
```json
{
  "success": false,
  "message": "OTP code has expired. Please request a new one.",
  "data": null,
  "errors": null,
  "meta": null
}
```

---

### **3. Resend OTP**
**Endpoint:** `POST /resend`

**Headers:**
```
Accept: application/json
Lang: en|ar
```

**Body (JSON):**
```json
{
  "phone": "+966500000001"
}
```

**Success Response:** Same as Send OTP

**Error Responses:** Same as Send OTP

---

## 🔒 BUSINESS RULES

### **OTP Generation**
- Length: 6 digits
- Format: Numeric only (000000 - 999999)
- Hashed before storage (never stored plain)

### **OTP Expiry**
- Valid for: 5 minutes
- After expiry: Must request new OTP

### **Resend Cooldown**
- Wait time: 30 seconds between resends
- Status code: 429 if trying to resend too soon

### **Rate Limiting**
- Max sends per hour: 5
- Status code: 429 if exceeded

### **Verification Attempts**
- Max attempts: 5 per OTP
- After 5 failed attempts:
  - Account locked for: 10 minutes
  - Status code: 429
  - Must wait before trying again

### **SMS Delivery**
- Driver: Log (for development)
- Check: `storage/logs/laravel.log` for OTP codes
- Format: Both English and Arabic messages

---

## 📊 DATABASE SCHEMA

### **vendors table (updated)**
```sql
otp_last_sent_at     TIMESTAMP NULL
otp_locked_until     TIMESTAMP NULL
otp_attempts         INT DEFAULT 0
```

### **vendor_otps table (new)**
```sql
id                   BIGINT PRIMARY KEY
vendor_id            BIGINT FK vendors.id
phone                VARCHAR
otp_hash             VARCHAR (never plain OTP!)
expires_at           TIMESTAMP
consumed_at          TIMESTAMP NULL
resend_available_at  TIMESTAMP
attempts             INT DEFAULT 0
created_at           TIMESTAMP
updated_at           TIMESTAMP
```

---

## 🧪 TESTING GUIDE

### **Testing Flow**

#### **1. Send OTP**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/send \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Lang: en" \
  -d '{
    "phone": "+966500000001"
  }'
```

**Check logs for OTP:**
```bash
tail -f storage/logs/laravel.log
```

You'll see:
```
[2026-01-15 21:00:00] local.INFO: === SMS SENT === {"to":"+966500000001","message":"Your PS verification code is: 123456. Valid for 5 minutes.\n\nرمز التحقق الخاص بك في PS هو: 123456. صالح لمدة 5 دقائق.","timestamp":"2026-01-15 21:00:00"}
```

#### **2. Verify OTP**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/verify \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Lang: en" \
  -d '{
    "phone": "+966500000001",
    "otp": "123456"
  }'
```

**Save the token from response!**

#### **3. Test with OTP_DEBUG=true**
Add to `.env`:
```
OTP_DEBUG=true
```

Now the Send OTP response will include the OTP:
```json
{
  "data": {
    "phone": "+966500000001",
    "resend_in_seconds": 30,
    "expires_in_seconds": 300,
    "otp": "123456"  // ← Visible in response!
  }
}
```

**⚠️ IMPORTANT:** Never enable `OTP_DEBUG=true` in production!

---

### **Test Scenarios**

#### **Scenario 1: Happy Path**
1. Send OTP ✅
2. Check logs for OTP code ✅
3. Verify with correct OTP ✅
4. Receive token ✅

#### **Scenario 2: Expired OTP**
1. Send OTP ✅
2. Wait 6 minutes ⏰
3. Try to verify → `410 OTP Expired` ❌

#### **Scenario 3: Invalid OTP**
1. Send OTP ✅
2. Verify with wrong code (5 times) ❌
3. Account locked for 10 minutes 🔒

#### **Scenario 4: Resend Too Soon**
1. Send OTP ✅
2. Immediately try resend → `429 Wait before resend` ❌
3. Wait 30 seconds ⏰
4. Resend → Success ✅

#### **Scenario 5: Rate Limit**
1. Send OTP 5 times within 1 hour ✅
2. Try 6th time → `429 Too many requests` ❌

#### **Scenario 6: Arabic Language**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/send \
  -H "Lang: ar" \
  -d '{"phone": "+966500000001"}'
```

Response in Arabic:
```json
{
  "message": "تم إرسال رمز التحقق إلى هاتفك بنجاح."
}
```

---

## 📱 SMS LOG FORMAT

When OTP is sent, check `storage/logs/laravel.log`:

```
[2026-01-15 21:00:00] local.INFO: === SMS SENT ===  
{
  "to": "+966500000001",
  "message": "Your PS verification code is: 123456. Valid for 5 minutes.\n\nرمز التحقق الخاص بك في PS هو: 123456. صالح لمدة 5 دقائق.",
  "timestamp": "2026-01-15 21:00:00"
}
```

---

## 🌍 LOCALIZATION

All messages support English and Arabic via `Lang` header.

### **English (`Lang: en`)**
- OTP sent successfully to your phone.
- Phone verified successfully.
- Invalid OTP code. Please try again.
- OTP code has expired. Please request a new one.
- Please wait before requesting a new code.
- Too many OTP requests. Please try again later.
- Account temporarily locked.

### **Arabic (`Lang: ar`)**
- تم إرسال رمز التحقق إلى هاتفك بنجاح.
- تم التحقق من رقم الهاتف بنجاح.
- رمز التحقق غير صحيح. يرجى المحاولة مرة أخرى.
- انتهت صلاحية رمز التحقق. يرجى طلب رمز جديد.
- يرجى الانتظار قبل طلب رمز جديد.
- عدد كبير من الطلبات. يرجى المحاولة لاحقاً.
- تم قفل الحساب مؤقتاً.

---

## 🔧 CONFIGURATION

### **App Configuration** (`config/app.php`)
```php
'otp_debug' => env('OTP_DEBUG', false),
```

### **SMS Service** (`config/services.php`)
```php
'sms' => [
    'driver' => env('SMS_DRIVER', 'log'),
],
```

### **Environment Variables** (`.env`)
```env
OTP_DEBUG=true          # Show OTP in API response (dev only!)
SMS_DRIVER=log          # SMS driver: log, twilio, nexmo
```

---

## 🏗️ ARCHITECTURE

### **Service Layer Pattern**
All business logic is in `VendorOtpService`:
- `send(string $phone): array`
- `verify(string $phone, string $otp): array`
- `resend(string $phone): array`

### **SMS Service**
`SmsService` handles SMS sending:
- Currently uses `log` driver (writes to Laravel logs)
- Easily extensible for Twilio, Nexmo, etc.

### **Controllers**
Controllers are thin:
1. Validate via FormRequest
2. Call Service method
3. Handle exceptions and return formatted response

### **Security**
- OTP is **hashed** before storage (never stored plain)
- Uses Laravel's `Hash::make()` and `Hash::check()`
- Rate limiting prevents abuse
- Account locking after failed attempts

---

## 📊 ERROR CODES REFERENCE

| Code | Description | When |
|------|-------------|------|
| 200 | Success | OTP sent/verified successfully |
| 401 | Invalid OTP | Wrong OTP code entered |
| 404 | Not Found | No OTP found for phone |
| 410 | Gone | OTP has expired |
| 422 | Validation Error | Invalid phone format or missing fields |
| 429 | Too Many Requests | Rate limit exceeded or resend cooldown |
| 500 | Server Error | Unexpected error |

---

## ✅ CHECKLIST

- [x] Database migration created
- [x] VendorOtp model created
- [x] SMS Service implemented (log driver)
- [x] VendorOtpService implemented
- [x] VendorOtpController created
- [x] Form requests created with validation
- [x] Routes configured (modular)
- [x] Translations added (EN + AR)
- [x] Config files updated (app, services)
- [x] Postman collection updated
- [x] Documentation completed
- [x] Testing guide provided

---

## 🎯 WHAT'S NOT INCLUDED (As Requested)

- ❌ Payment/packages
- ❌ Business profile completion
- ❌ Vendor dashboard
- ❌ Document verification

**This milestone:** OTP verification ONLY ✅

---

## 🚀 NEXT STEPS

1. Run migration: `php artisan migrate`
2. Set `OTP_DEBUG=true` in `.env` (for testing)
3. Import Postman collection
4. Test Send OTP endpoint
5. Check `storage/logs/laravel.log` for OTP code
6. Test Verify OTP endpoint
7. Verify token is returned

---

**Implementation Status:** ✅ COMPLETE

All requirements met. System is ready for testing!

---

## 📞 Quick Test Commands

```bash
# 1. Send OTP
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/send \
  -H "Content-Type: application/json" \
  -d '{"phone":"+966500000001"}'

# 2. Check logs for OTP
tail -f storage/logs/laravel.log

# 3. Verify OTP
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/verify \
  -H "Content-Type: application/json" \
  -d '{"phone":"+966500000001","otp":"123456"}'

# 4. Resend OTP (after 30 seconds)
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/resend \
  -H "Content-Type: application/json" \
  -d '{"phone":"+966500000001"}'
```

---

**Ready to use!** 🎉
