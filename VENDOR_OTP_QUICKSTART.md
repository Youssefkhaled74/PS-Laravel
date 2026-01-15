# 🚀 Vendor OTP - Quick Start Guide

## ⚡ Setup (2 Steps)

### 1️⃣ Run Migration
```bash
php artisan migrate
```

### 2️⃣ Enable OTP Debug (Optional - Development Only)
Add to `.env`:
```env
OTP_DEBUG=true
SMS_DRIVER=log
```

---

## 📡 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/vendor/otp/send` | POST | Send OTP to phone |
| `/api/v1/vendor/otp/verify` | POST | Verify OTP code |
| `/api/v1/vendor/otp/resend` | POST | Resend OTP code |

---

## 📝 Quick Test Flow

### 1. Send OTP
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/send \
  -H "Content-Type: application/json" \
  -H "Lang: en" \
  -d '{"phone": "+966500000001"}'
```

**Response:**
```json
{
  "success": true,
  "message": "OTP sent successfully to your phone.",
  "data": {
    "phone": "+966500000001",
    "resend_in_seconds": 30,
    "expires_in_seconds": 300,
    "otp": "123456"  // Only if OTP_DEBUG=true
  }
}
```

### 2. Check Logs for OTP
```bash
tail -f storage/logs/laravel.log
```

Look for:
```
=== SMS SENT === 
{"to":"+966500000001","message":"Your PS verification code is: 123456..."}
```

### 3. Verify OTP
```bash
curl -X POST http://127.0.0.1:8000/api/v1/vendor/otp/verify \
  -H "Content-Type: application/json" \
  -H "Lang: en" \
  -d '{
    "phone": "+966500000001",
    "otp": "123456"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Phone verified successfully.",
  "data": {
    "token": "2|abc123...",
    "vendor": { /* vendor profile */ }
  }
}
```

---

## 🔒 Business Rules

| Rule | Value |
|------|-------|
| OTP Length | 6 digits |
| OTP Expiry | 5 minutes |
| Resend Cooldown | 30 seconds |
| Max Verify Attempts | 5 |
| Lock Duration | 10 minutes |
| Max Sends Per Hour | 5 |

---

## 🌍 Languages

Set `Lang` header:
- `Lang: en` → English
- `Lang: ar` → Arabic

**Example:**
```bash
curl -H "Lang: ar" -d '{"phone":"+966500000001"}' \
  http://127.0.0.1:8000/api/v1/vendor/otp/send
```

---

## 📂 Files Created

```
app/
├── Models/
│   └── VendorOtp.php
├── Services/
│   ├── Sms/
│   │   └── SmsService.php
│   └── Vendor/Auth/
│       └── VendorOtpService.php
├── Http/
│   ├── Controllers/Api/V1/Vendor/Auth/
│   │   └── VendorOtpController.php
│   └── Requests/Api/V1/Vendor/Auth/
│       ├── SendVendorOtpRequest.php
│       └── VerifyVendorOtpRequest.php

routes/api/vendor/
└── otp.php

database/migrations/
└── 2026_01_15_210000_create_vendor_otps_table.php

docs/
└── VENDOR_OTP.md
```

---

## ❌ Error Responses

### Invalid OTP (401)
```json
{
  "success": false,
  "message": "Invalid OTP code. Please try again.",
  "errors": {
    "remaining_attempts": 3
  }
}
```

### OTP Expired (410)
```json
{
  "success": false,
  "message": "OTP code has expired. Please request a new one."
}
```

### Rate Limited (429)
```json
{
  "success": false,
  "message": "Please wait before requesting a new code.",
  "errors": {
    "seconds": 25
  }
}
```

### Account Locked (429)
```json
{
  "success": false,
  "message": "Account temporarily locked...",
  "errors": {
    "seconds": 600
  }
}
```

---

## 🧪 Test with Postman

1. Import: `postman/PS_Vendor_Auth.postman_collection.json`
2. Select environment: `PS_Local`
3. Navigate to: **Vendor OTP** folder
4. Run: **Send OTP** → Check logs → **Verify OTP**

---

## 🔐 Security Features

✅ OTP hashed before storage (never plain text)  
✅ Rate limiting (30s cooldown, 5/hour max)  
✅ Account locking after 5 failed attempts  
✅ OTP expires after 5 minutes  
✅ Token issued on successful verification  

---

## 📊 Database Tables

### `vendors` (updated)
- `otp_last_sent_at`
- `otp_locked_until`
- `otp_attempts`

### `vendor_otps` (new)
- `id`, `vendor_id`, `phone`
- `otp_hash` (never plain!)
- `expires_at`, `consumed_at`
- `resend_available_at`
- `attempts`

---

## ✅ Status

**Implementation:** ✅ COMPLETE  
**Testing:** ✅ READY  
**Documentation:** ✅ COMPLETE  

---

**Full Documentation:** [docs/VENDOR_OTP.md](docs/VENDOR_OTP.md)

**Ready to use!** 🎉
