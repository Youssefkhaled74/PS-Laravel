# Vendor Onboarding API - Setup & Testing Guide

## 🗄️ Database Setup

### 1. Run Migrations
```powershell
php artisan migrate
```

This will create the following tables:
- `vendors` (with onboarding fields)
- `vendor_business_profiles` (with document upload fields)
- `vendor_otps`
- `vendor_packages` (with features JSON)
- `vendor_package_assignments`
- `payment_methods`
- `vendor_payment_attempts`
- `brand_vendor` (pivot table)

### 2. Run Seeders
```powershell
# Seed all required data
php artisan db:seed --class=VendorPackageSeeder
php artisan db:seed --class=PaymentMethodSeeder

# Or run all seeders at once
php artisan db:seed
```

**VendorPackageSeeder** creates 4 packages:
- Primary: 99 SAR/month (1,188 SAR/year)
- Basic: 299 SAR/month (3,588 SAR/year)
- Advanced: 399 SAR/month (4,788 SAR/year)
- Professional: 1,999 SAR/month (23,988 SAR/year)

**PaymentMethodSeeder** creates 5 methods:
- Apple Pay
- Mada
- Tamara
- Tabby
- Paymob

### 3. Storage Link (Required for File Uploads)
```powershell
php artisan storage:link
```

---

## 🧪 Postman Testing

### Import Files
1. **Collection**: `postman/PS_Vendor_Onboarding.postman_collection.json`
2. **Environment**: `postman/PS_Local.postman_environment.json`

### Environment Variables
- `base_url`: `http://127.0.0.1:8000`
- `vendor_token`: Auto-populated after login
- `vendor_phone`: `+966500000001`
- `vendor_password`: `password123`

---

## 📋 Complete Onboarding Flow

### Step 1: Authentication
1. **Register Vendor** → `POST /api/v1/vendor/auth/register`
2. **Login** → `POST /api/v1/vendor/auth/login` (saves token automatically)

### Step 2: Phone Verification
3. **Send OTP** → `POST /api/v1/vendor/otp/send`
4. **Verify OTP** → `POST /api/v1/vendor/otp/verify`

### Step 3: Commercial Data
5. **Save Commercial** → `POST /api/v1/vendor/onboarding/commercial`
   - Upload ID card file
   - Upload commercial register file
   - Select brands
   - Enter bank details

### Step 4: Package Selection
6. **Get Packages** → `GET /api/v1/vendor/packages` (public)
7. **Select Package** → `POST /api/v1/vendor/packages/select`
   - Choose monthly or yearly billing

### Step 5: Payment
8. **Get Payment Methods** → `GET /api/v1/vendor/payment/methods` (public)
9. **Confirm Payment** → `POST /api/v1/vendor/payment/confirm`
   - Use `simulate_status`: `paid`, `pending`, or `failed`

### Step 6: Profile Check
10. **Get Profile** → `GET /api/v1/vendor/me`

---

## 🔑 Payment Simulation

The `POST /api/v1/vendor/payment/confirm` endpoint accepts a `simulate_status` parameter:

```json
{
  "vendor_subscription_id": 1,
  "payment_method_id": 1,
  "simulate_status": "paid"
}
```

**Status Options:**
- `paid` → Sets vendor to `awaiting_approval`
- `pending` → Keeps vendor at `payment_pending`
- `failed` → Sets vendor to `payment_failed`

---

## 📂 File Upload Paths

Uploaded files stored at:
```
public/uploads/vendor/documents/{vendor_id}/
├── id_card_12345.pdf
└── commercial_12345.pdf
```

---

## 🌍 Localization

All endpoints support language switching via the `Lang` header:

```http
Lang: en  # English
Lang: ar  # Arabic
```

Translations available for:
- Package names and features
- Payment method names
- API response messages
- Validation errors

---

## 🛡️ Vendor Status Lifecycle

```
pending → 
phone_verified → 
commercial_submitted → 
package_selected → 
payment_pending → 
awaiting_approval → 
active/rejected
```

---

## 📡 API Endpoints

### Public Endpoints
- `GET /api/v1/vendor/packages`
- `GET /api/v1/vendor/payment/methods`

### Protected Endpoints (Require Bearer Token)
- `POST /api/v1/vendor/onboarding/commercial`
- `POST /api/v1/vendor/packages/select`
- `POST /api/v1/vendor/payment/confirm`
- `GET /api/v1/vendor/me`

---

## 🐛 Troubleshooting

### Files not uploading?
```powershell
# Check storage link
php artisan storage:link

# Check permissions (if on Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Seeders not running?
```powershell
# Check if migrations ran first
php artisan migrate:status

# Run fresh migrations + seeders
php artisan migrate:fresh --seed
```

### Token not saving in Postman?
- Check the **Login Vendor** request has the Test script enabled
- Manually copy token from response and set in environment

---

## ✅ Quick Verification

Run this to verify everything is set up:

```powershell
# Check migrations
php artisan migrate:status

# Check seeders created data
php artisan tinker
>>> \App\Models\VendorPackage::count();  # Should be 4
>>> \App\Models\PaymentMethod::count();  # Should be 5
>>> exit
```

---

## 📦 Testing Checklist

- [ ] Register new vendor
- [ ] Login and save token
- [ ] Send/verify OTP
- [ ] Upload commercial documents
- [ ] List packages in EN and AR
- [ ] Select package (monthly)
- [ ] Select package (yearly)
- [ ] List payment methods
- [ ] Confirm payment (paid status)
- [ ] Get vendor profile
- [ ] Test payment failure scenario
- [ ] Test all endpoints in Arabic (`Lang: ar`)

---

**Ready to test!** 🚀
