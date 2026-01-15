# PRD — Dual Applications (User App + Vendor App)

**Document owner:** Joe  
**Last updated:** 2026-01-04  
**Source of truth:** UI designs (Figma/PDF exports): `Ps.pdf` (User app), `Ps (1).pdf` (Vendor app).  
**Goal:** Provide a single, always-referenced PRD to guide backend implementation and ensure API/data contracts match the UI flows.

---

## Table of contents
1. [Product overview](#product-overview)  
2. [Shared assumptions](#shared-assumptions)  
3. [Shared data model](#shared-data-model)  
4. [User App PRD](#user-app-prd)  
5. [Vendor App PRD](#vendor-app-prd)  
6. [Shared API conventions](#shared-api-conventions)  
7. [Open questions](#open-questions)

---

# Product overview

The platform connects **Customers (Users)** with **Vendors/Personal Shoppers**:
- User creates an order/request (direct or special request)
- Vendors respond / fulfill
- In-app chat supports clarifications
- Payments, tracking, and rating complete the loop

**Two mobile apps:**
- **User App (Customer)**: browse/search, favorites, cart/checkout, create orders, track orders, chat, rate, manage profile.
- **Vendor App (Vendor/Shopper)**: onboarding + approval, subscription plans, product management, order management + shipping confirmation, dashboard/analytics, reviews, profile/settings, coupons.

---

# Shared assumptions

## Entities & roles
- **User**: end customer placing orders.
- **Vendor**: personal shopper/store fulfilling orders.
- **Admin** (optional, later): approves vendors, manages categories, resolves disputes.

## Payment providers (as shown in UI)
- Apple Pay / Mada
- Installments: Tamara, Tabby
- Paymob
- (UI also shows PayPal in vendor subscription flow)

> Backend should be provider-agnostic: a unified payment abstraction with provider-specific integrations.

## Tax & fees
- UI shows **shipping fee** and **10% tax** in checkout/order details.
- Backend should compute totals deterministically:
  - `subtotal` = sum(items price * qty)
  - `shipping_fee` (configurable)
  - `tax_amount` = round(subtotal * tax_rate)
  - `total` = subtotal + shipping_fee + tax_amount

---

# Shared data model

> This is a recommended baseline. Adjust naming to your stack conventions.

## User
- `id`, `name`, `phone`, `email?`
- `password_hash` (or external auth)
- `language` (ar/en), `theme` (light/dark)
- `created_at`, `updated_at`

## Vendor
- `id`, `full_name`, `brand_name`
- `phone`, `email`, `national_id`
- `address_national`, `location_lat`, `location_lng`
- `status` = `PENDING_APPROVAL | APPROVED | REJECTED | SUSPENDED`
- Business:
  - `commercial_register_no?` / `freelance_doc_no?`
  - `bank_account_name`, `bank_name`
- Documents:
  - `documents[]` (file refs)
- `rating_avg`, `rating_count`

## Category
- `id`, `name_ar`, `name_en`, `parent_id?`, `is_active`

## Product
- `id`, `vendor_id`
- `title`, `promo_title?`
- Attributes:
  - `gender` (men/women/kids/unisex)
  - `type` (shirt, pants, etc)
  - `sizes[]`, `colors[]`
- Inventory:
  - `stock_qty`, `max_per_customer`, `weight_kg?`
- Pricing:
  - `price`, `discount_price?`, `discount_ends_at?`
  - `is_taxable` (bool)
- `warranty_months?`
- Media:
  - `images[]`
- `category_id`
- `created_at`, `updated_at`

## Favorite
- `id`, `user_id`, `target_type` (`PRODUCT|VENDOR`), `target_id`, `created_at`

## Cart + CartItem (if using cart)
- `cart_id`, `user_id`
- `cart_item_id`, `product_id`, `qty`, `unit_price_snapshot`

## Address
- `id`, `user_id`, `label`, `city`, `street`, `notes?`
- `lat`, `lng`
- `is_default`

## Order
- `id`, `order_no` (e.g., ORD#)
- `user_id`, `vendor_id?` (nullable until assigned/accepted)
- `type` = `DIRECT | SPECIAL_REQUEST`
- `status` (see [Order status machine](#order-status-machine))
- Money:
  - `currency`
  - `subtotal`, `shipping_fee`, `tax_rate`, `tax_amount`, `total`
- Delivery:
  - `address_id`, `delivery_notes?`
- Timestamps:
  - `created_at`, `paid_at?`, `shipped_at?`, `delivered_at?`, `cancelled_at?`

## OrderItem
- `id`, `order_id`
- If DIRECT: `product_id`, `title_snapshot`, `unit_price_snapshot`, `qty`, `options_snapshot`
- If SPECIAL_REQUEST: `free_text_description`, `reference_images[]`, `target_price?`

## Shipment
- `id`, `order_id`
- `carrier?`, `tracking_number?`, `tracking_url?`
- `status` = `PENDING | SHIPPED | DELIVERED`

## Offer (if special request uses vendor offers)
- `id`, `order_id`, `vendor_id`
- `offer_price`, `shipping_fee`, `tax_included?`
- `delivery_time_days`
- `return_policy_text?`
- `expires_at`
- `status` = `SENT | ACCEPTED | REJECTED | EXPIRED`

## Payment
- `id`, `order_id`
- `provider` (APPLE_PAY/MADA/TAMARA/TABBY/PAYMOB/PAYPAL)
- `amount`, `currency`
- `status` = `INITIATED | REQUIRES_ACTION | SUCCEEDED | FAILED | REFUNDED`
- `provider_ref`, `provider_payload` (json)
- `created_at`, `updated_at`

## Conversation + Message
- `conversation_id`, `order_id`, `user_id`, `vendor_id`
- `message_id`, `sender_type` (`USER|VENDOR|SYSTEM`), `text`, `attachments[]`, `sent_at`, `read_at?`

## Rating
- `id`, `order_id`, `user_id`, `vendor_id`
- `stars` (1-5), `comment?`, `created_at`

## Notification
- `id`, `recipient_type` (`USER|VENDOR`), `recipient_id`
- `type`, `title`, `body`, `payload_json`
- `read_at?`, `created_at`

## Subscription (Vendor)
- `id`, `vendor_id`
- `plan_id`, `billing_period` (`MONTHLY|YEARLY`)
- `status` = `ACTIVE | PAST_DUE | CANCELED | EXPIRED`
- `started_at`, `ends_at`
- `current_period_start`, `current_period_end`
- `payment_provider_ref?`

## Plan (Vendor)
- `id`, `name`, `price_monthly`, `price_yearly`
- `limits_json` (e.g., max_products, analytics_level, etc)
- `is_active`

---

# User App PRD

## Goals
- Smooth onboarding and authentication (phone + OTP flows)
- Search/browse products and vendors
- Add to favorites/cart and checkout
- Create and track orders
- Chat in-context with vendors
- Rate experience after delivery
- Manage profile/settings (language, theme, legal, support)

## In-scope features

### 1) Authentication & account recovery
**User stories**
- As a user, I can login with phone + password.
- As a user, I can reset my password with OTP verification.
- As a user, I can verify my phone using a 6-digit OTP.

**Requirements**
- OTP rate limiting + expiration (e.g., 5 min).
- Login sessions via JWT + refresh token (or equivalent).
- Track device tokens for push notifications.

**APIs**
- `POST /auth/register`
- `POST /auth/login`
- `POST /auth/otp/send`
- `POST /auth/otp/verify`
- `POST /auth/password/forgot`
- `POST /auth/password/reset`
- `POST /auth/logout`

---

### 2) Home, browse, search, filters
**User stories**
- As a user, I can search products by keyword.
- As a user, I can filter by type/brand/color/size/price and sort results.
- As a user, I can view categories like men/women/kids.

**Requirements**
- Search should support pagination and sort.
- Filters should be expressible as query params.
- Search history (optional): store recent queries per user.

**APIs**
- `GET /categories`
- `GET /products?query=&category_id=&brand=&color=&size=&min_price=&max_price=&sort=&page=&page_size=`
- `GET /vendors?query=&category_id=&page=&page_size=`

---

### 3) Favorites
**User stories**
- As a user, I can favorite/unfavorite products/vendors.
- As a user, I can view my favorites list.

**APIs**
- `GET /favorites?type=PRODUCT|VENDOR`
- `POST /favorites` body: `{ "target_type": "PRODUCT", "target_id": "..." }`
- `DELETE /favorites/{favorite_id}`

---

### 4) Cart & Checkout
**User stories**
- As a user, I can add a product to cart, update quantity, remove items.
- As a user, I can see subtotal, shipping, tax, and total.
- As a user, I can checkout and pay using available payment methods.

**Requirements**
- Price snapshots on cart items or at checkout (to prevent mismatch).
- Totals computed server-side.
- Address selection and default address handling.

**APIs**
- `GET /cart`
- `POST /cart/items` body: `{ "product_id": "...", "qty": 1, "options": {...} }`
- `PATCH /cart/items/{item_id}` body: `{ "qty": 2 }`
- `DELETE /cart/items/{item_id}`
- `POST /checkout` body: `{ "address_id": "...", "payment_provider": "MADA" }`
- `POST /payments/intent` body: `{ "order_id": "...", "provider": "MADA" }`
- `POST /payments/webhook/{provider}` (provider callback)
- `GET /payments/{id}`

---

### 5) Orders (Direct + Special Request)
**User stories**
- As a user, I can create a direct order from a product.
- As a user, I can create a special request (description + optional images) when product isn't listed.
- As a user, I can see my orders by status tabs.
- As a user, I can view order details, progress timeline, and totals.
- As a user, I can cancel an order under allowed conditions.
- As a user, I can message the vendor inside order details.

**Requirements**
- Orders list supports filtering by status.
- Order details include timeline events: submitted → paid → shipped → delivered.
- Cancellation rules:
  - Allowed before shipment (configurable).
  - If paid, may require refund workflow (future).
- Special request may have offers:
  - multiple vendors can send offers
  - user accepts one offer → payment enabled

**APIs**
- Create:
  - `POST /orders` (direct)
  - `POST /special-requests` (special)
- List / details:
  - `GET /orders?status=&page=&page_size=`
  - `GET /orders/{id}`
- Cancel:
  - `POST /orders/{id}/cancel`
- Offers (if enabled for special requests):
  - `GET /orders/{id}/offers`
  - `POST /offers/{id}/accept`
- Messaging:
  - `GET /orders/{id}/conversation`
  - `POST /orders/{id}/messages`

---

### 6) Chat (Inbox + conversation)
**User stories**
- As a user, I can view all my conversations.
- As a user, I can send messages and receive replies.
- As a user, I can send attachments (optional).

**Requirements**
- Store message read receipts.
- Push notifications on new messages.
- Basic moderation/abuse reporting (later).

**APIs**
- `GET /conversations`
- `GET /conversations/{id}/messages?page=&page_size=`
- `POST /conversations/{id}/messages` body: `{ "text": "...", "attachments": [...] }`

---

### 7) Ratings & Reviews
**User stories**
- As a user, after delivery I can submit a rating (stars + comment).

**Requirements**
- Only allow rating if order is delivered/received.
- Update vendor aggregated rating.

**APIs**
- `POST /orders/{id}/rating` body: `{ "stars": 5, "comment": "..." }`
- `GET /vendors/{id}/reviews?page=&page_size=`

---

### 8) Profile & Settings & Legal & Support
**User stories**
- As a user, I can change language (Arabic/English).
- As a user, I can toggle dark mode.
- As a user, I can view Terms & Conditions and Privacy Policy.
- As a user, I can contact support.

**APIs**
- `GET /me`
- `PATCH /me`
- `PATCH /me/settings` body: `{ "language": "ar", "theme": "dark" }`
- `GET /legal/terms`
- `GET /legal/privacy`
- `POST /support/tickets` body: `{ "subject": "...", "message": "..." }`

---

## User App non-functional requirements
- **Security:** JWT/refresh, OTP throttling, password hashing (bcrypt/argon2), WAF/rate limit on auth endpoints.
- **Reliability:** idempotency keys for checkout/payment initiation.
- **Performance:** search endpoints paginated; caching for categories.
- **Localization:** proper RTL/LTR handling, server stores chosen language.
- **Observability:** structured logs + tracing for checkout and payment webhooks.

---

# Vendor App PRD

## Goals
- Quick vendor onboarding: personal + business details + document uploads.
- Subscription purchase and renewal.
- Vendor approval status handling.
- Product management (create/edit, images, inventory, discounting, taxability).
- Orders management: view, update status, confirm shipping, communicate.
- Dashboard: key metrics, sales chart, reviews.
- Coupons management (as shown in vendor menu).
- Settings/legal/support + language/theme.

## In-scope features

### 1) Authentication
Same as user app (phone/password + OTP recovery), with vendor identity.

**APIs**
- `POST /vendor/auth/register`
- `POST /vendor/auth/login`
- `POST /vendor/auth/otp/send`
- `POST /vendor/auth/otp/verify`
- `POST /vendor/auth/password/forgot`
- `POST /vendor/auth/password/reset`

---

### 2) Vendor onboarding & profile (personal + business)
**User stories**
- As a vendor, I can complete personal information (name, brand name, national id, phone, email, address, location).
- As a vendor, I can complete business info (CR/freelance doc, bank details).
- As a vendor, I can upload required documents.
- As a vendor, I can see approval status (pending/approved/rejected).

**Requirements**
- Validation rules for required fields and document formats.
- Store documents securely (S3-like storage) with signed URLs.
- Vendor cannot publish products / accept orders until approved (configurable).

**APIs**
- `GET /vendor/me`
- `PATCH /vendor/me/personal`
- `PATCH /vendor/me/business`
- `POST /vendor/me/documents` (multipart)
- `GET /vendor/approval-status`

---

### 3) Subscription plans & payment
**User stories**
- As a vendor, I can view available subscription plans.
- As a vendor, I can select monthly/yearly and pay.
- As a vendor, I can see subscription status (active/expired).
- As a vendor, I may be pending approval even after payment (as shown in UI).

**Requirements**
- Plan limits (e.g., product limits) enforced server-side.
- Renewal workflow (later) or manual repurchase (MVP).
- Webhooks for subscription payment updates.

**APIs**
- `GET /plans`
- `POST /vendor/subscriptions` body: `{ "plan_id": "...", "billing_period": "MONTHLY", "provider": "PAYMOB" }`
- `GET /vendor/subscriptions/current`
- `POST /vendor/payments/intent` body: `{ "subscription_id": "...", "provider": "PAYMOB" }`
- `POST /vendor/payments/webhook/{provider}`

---

### 4) Vendor dashboard
**User stories**
- As a vendor, I can see counts: new/active/completed orders and overall rating.
- As a vendor, I can see a sales chart over the last N months.
- As a vendor, I can see recent reviews.
- As a vendor, I can act on alerts like “confirm shipment”.

**APIs**
- `GET /vendor/dashboard` returns:
  - `orders_new`, `orders_active`, `orders_completed`
  - `rating_avg`, `rating_count`
  - `sales_timeseries[]` (month, value)
  - `recent_reviews[]`
  - `action_items[]`

---

### 5) Product management (My Products)
**User stories**
- As a vendor, I can create a product with attributes (gender/type/size/color), pricing, inventory, discount, warranty, taxability.
- As a vendor, I can upload product images.
- As a vendor, I can edit and deactivate products.
- As a vendor, I can set quantity limits per customer.

**Requirements**
- Category selection may be admin-controlled (as indicated in UI).
- Server must validate discount end date and compute effective price.
- Inventory updates should be atomic (avoid oversell).

**APIs**
- `GET /vendor/products?page=&page_size=`
- `POST /vendor/products`
- `GET /vendor/products/{id}`
- `PATCH /vendor/products/{id}`
- `POST /vendor/products/{id}/images` (multipart)
- `DELETE /vendor/products/{id}` (soft delete / deactivate)

---

### 6) Orders management (Vendor)
**User stories**
- As a vendor, I can view orders by status.
- As a vendor, I can open order details, see items, delivery address, totals, and timeline.
- As a vendor, I can send messages to the user inside the order.
- As a vendor, I can confirm shipping and provide tracking.
- As a vendor, I can cancel order under allowed rules.

**Requirements**
- Vendor can update order status only forward (state machine).
- Shipping confirmation requires at least carrier or tracking number (configurable).
- Notify user on status changes.

**APIs**
- `GET /vendor/orders?status=&page=&page_size=`
- `GET /vendor/orders/{id}`
- `POST /vendor/orders/{id}/ship` body: `{ "carrier": "...", "tracking_number": "...", "tracking_url": "..." }`
- `POST /vendor/orders/{id}/cancel`
- `GET /vendor/orders/{id}/conversation`
- `POST /vendor/orders/{id}/messages`

---

### 7) Reviews
**User stories**
- As a vendor, I can view reviews from customers.

**APIs**
- `GET /vendor/reviews?page=&page_size=`

---

### 8) Coupons (as shown in vendor menu)
**User stories**
- As a vendor, I can create coupon codes and manage them.
- As a vendor, I can view active/expired coupons.

**Requirements**
- Validate coupon rules at checkout (min order, expiry, usage limits).

**APIs**
- `GET /vendor/coupons`
- `POST /vendor/coupons` body: `{ "code": "SAVE10", "type": "PERCENT", "value": 10, "starts_at": "...", "ends_at": "...", "max_uses": 100, "min_subtotal": 0 }`
- `PATCH /vendor/coupons/{id}`
- `DELETE /vendor/coupons/{id}`

---

### 9) Vendor settings, legal, support
**User stories**
- As a vendor, I can change language/theme.
- As a vendor, I can access terms/privacy and contact support.

**APIs**
- `PATCH /vendor/me/settings` body: `{ "language": "en", "theme": "light" }`
- `GET /legal/terms`
- `GET /legal/privacy`
- `POST /support/tickets`

---

## Vendor App non-functional requirements
- **Security:** strict authorization: vendor can only access own products/orders.
- **Compliance:** protect national ID and bank details; restrict logs from leaking sensitive fields.
- **Auditability:** track who changed order status and when (system log).

---

# Shared API conventions

## Auth & authorization
- Use JWT access tokens + refresh tokens.
- Require `Authorization: Bearer <token>` for protected endpoints.
- Distinguish app audiences:
  - User tokens vs Vendor tokens (separate issuers or claim `role`).

## Pagination
- Standard: `page`, `page_size`
- Return: `items`, `page`, `page_size`, `total_items`, `total_pages`

## Error format
```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Phone is invalid",
    "details": [{"field":"phone","reason":"invalid_format"}]
  }
}
```

## File uploads
- Prefer presigned upload:
  - `POST /uploads/presign` -> signed URL + file key
  - client uploads to storage
  - server stores reference

---

# Order status machine

> The UI shows a progress timeline. Recommended minimal state machine:

## States
- `SUBMITTED` (created)
- `PAID`
- `SHIPPED`
- `DELIVERED`
- `CANCELLED`
- Optional future:
  - `RETURN_REQUESTED`, `RETURNED`, `REFUNDED`

## Transitions
- `SUBMITTED -> PAID` (payment success)
- `PAID -> SHIPPED` (vendor confirms shipment)
- `SHIPPED -> DELIVERED` (carrier webhook or manual confirmation)
- `SUBMITTED -> CANCELLED` (user or vendor, if allowed)
- `PAID -> CANCELLED` (allowed only with refund workflow; optional MVP)
- No backward transitions.

## Notifications (examples)
- User:
  - order created, payment success, shipped, delivered, new message, rating request
- Vendor:
  - new order assigned/paid, action needed to ship, new message, new review

---

# Analytics events (recommended)
- Auth:
  - `user_login_success`, `vendor_login_success`, `otp_sent`, `otp_verified`
- Catalog:
  - `search_performed`, `filter_applied`, `product_viewed`, `add_to_favorites`, `add_to_cart`
- Orders:
  - `order_created`, `special_request_created`, `offer_received`, `offer_accepted`
  - `payment_initiated`, `payment_success`, `payment_failed`
  - `order_status_changed`
- Vendor:
  - `vendor_product_created`, `vendor_product_updated`, `vendor_order_shipped`
  - `subscription_started`, `subscription_renewed`, `subscription_failed`
- Reviews:
  - `rating_submitted`

---

# Open questions

1. **Special request flow**: do vendors *send offers* that the user accepts, or is a vendor assigned immediately?
2. **Cart vs Direct order**: is checkout only via cart, or can “Buy now” create order directly?
3. **Multi-vendor orders**: can a single order contain items from multiple vendors? (affects order/vendor linkage).
4. **Refunds/returns**: required in MVP? If yes, define statuses and payment provider refund support.
5. **Vendor approval**: what triggers approval—admin panel, automatic checks, document review SLA?
6. **Subscription limits**: what exactly differs between plans (product limits, analytics, support priority, etc.)?
7. **Shipping tracking**: do we integrate a carrier API or store tracking only?
8. **Coupons**: apply at item-level or order-level? Combine with discounts?

---

## Appendix: Minimal endpoint checklist (MVP)

### User
- Auth, profile/settings, legal/support
- Categories/products search
- Favorites
- Cart + checkout + payment
- Orders list/details/status + chat + rating

### Vendor
- Auth + profile onboarding + documents
- Plans/subscription + payment
- Dashboard metrics
- Products CRUD + images
- Orders list/details + ship/cancel + chat
- Reviews
- Coupons CRUD
