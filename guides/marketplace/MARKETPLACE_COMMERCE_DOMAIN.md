# Marketplace & Commerce Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Marketplace & Commerce domain manages a comprehensive e-commerce platform for buying, selling, and renting goods and services. It includes catalog management, inventory tracking, service provider management, geographic-based seller discovery, shopping cart, orders, subscriptions, and appointment booking.

**Domain Code:** `MARKETPLACE_COMMERCE`

**Core Entities:** 20
- CATALOG
- CATEGORY
- ITEM
- ITEM_VARIANT
- FINISHED_GOODS_INVENTORY
- SERVICE_PROVIDER
- SERVICE_APPOINTMENT
- SUBSCRIPTION_PLAN
- CUSTOMER_SUBSCRIPTION
- SHOPPING_CART
- SHOPPING_CART_ITEM
- ORDER
- ORDER_ITEM
- ITEM_ATTRIBUTE_DEFINITION
- ITEM_ATTRIBUTE_VALUE
- DELIVERY_ZONE
- SHIPPING_METHOD
- PAYMENT_METHOD
- CUSTOMER_REVIEW
- ITEM_IMAGE

**Supporting Enumerations:** 9
- ENUM_ITEM_TYPE
- ENUM_TRANSACTION_TYPE
- ENUM_NEED_WANT_CLASSIFICATION
- ENUM_SUBSCRIPTION_INTERVAL
- ENUM_SUBSCRIPTION_STATUS
- ENUM_ORDER_STATUS
- ENUM_ORDER_ITEM_FULFILLMENT_STATUS
- ENUM_APPOINTMENT_STATUS
- ENUM_CART_STATUS

---

## Marketplace Architecture Overview

```
CATALOG (Organization's product/service catalog)
  ├─ CATEGORY (Hierarchical categories)
  │    └─ ITEM (Generic products/services)
  │         ├─ ITEM_VARIANT (Organization-specific offerings)
  │         │    ├─ FINISHED_GOODS_INVENTORY (Stock management for goods)
  │         │    ├─ SERVICE_PROVIDER (Provider management for services)
  │         │    └─ SUBSCRIPTION_PLAN (Recurring plans)
  │         └─ ITEM_ATTRIBUTE_VALUE (Specifications)
  │
  └─ SHOPPING_CART → SHOPPING_CART_ITEM → ORDER → ORDER_ITEM
       └─ SERVICE_APPOINTMENT (For service bookings)
```

**Key Feature: Geographic Discovery**
- Items display all variants (sellers) sorted by proximity
- Uses geo-coordinates from organization_building → postal_address
- Nearest sellers appear first to support local commerce

---

## 1. CATALOG

### Entity Structure
```
CATALOG
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ catalog_code* (Unique identifier, e.g., "CAT-2024-001")
├─ name* (Catalog name, e.g., "Main Product Catalog")
├─ description?
├─ catalog_type* [PRODUCT, SERVICE, MIXED]
├─ is_public* (Boolean - visible to all or organization only)
├─ display_order? (For sorting multiple catalogs)
├─ status* [DRAFT, ACTIVE, INACTIVE, ARCHIVED]
├─ published_date?
├─ created_by* (FK → PERSON)
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
CATALOG
  ← ORGANIZATION (Many:1)
  ← PERSON (Many:1) [via created_by]
  → CATEGORY (1:Many)
```

### Purpose
- Top-level container for organizing products/services
- Organizations can have multiple catalogs (e.g., "Retail", "Wholesale", "Services")
- Controls visibility and access to products/services

---

## 2. CATEGORY

### Entity Structure
```
CATEGORY
├─ id* (PK)
├─ catalog_id* (FK → CATALOG)
├─ parent_category_id? (FK → CATEGORY) [Self-referencing for hierarchy]
├─ category_code* (Unique within catalog, e.g., "ELECTRONICS")
├─ name* (Category name, e.g., "Electronics")
├─ description?
├─ icon_url? (Category icon)
├─ banner_image_media_file_id? (FK → MEDIA_FILE)
├─ display_order? (Order within parent category)
├─ level* (Hierarchy level: 1, 2, 3..., computed)
├─ full_path? (Computed: "Electronics > Computers > Laptops")
├─ is_leaf* (Boolean - has no children)
├─ status* [ACTIVE, INACTIVE]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
CATEGORY
  ← CATALOG (Many:1)
  ← CATEGORY (Many:1) [parent_category_id - hierarchical]
  → CATEGORY (1:Many) [children]
  → ITEM (1:Many)
  → ITEM_ATTRIBUTE_DEFINITION (1:Many) [Category-specific attributes]
```

### Category Hierarchy Example
```
Electronics (level 1)
  ├─ Computers (level 2)
  │    ├─ Laptops (level 3) [is_leaf = true]
  │    ├─ Desktops (level 3) [is_leaf = true]
  │    └─ Tablets (level 3) [is_leaf = true]
  └─ Mobile Phones (level 2) [is_leaf = true]
```

### Business Rules
1. **Hierarchy Depth:** Recommended max 5 levels
2. **Circular References:** Not allowed (parent cannot be descendant)
3. **Leaf Categories:** Only leaf categories can have items directly
4. **Unique Codes:** category_code unique within catalog

---

## 3. ITEM (Generic Product/Service)

### Entity Structure
```
ITEM
├─ id* (PK)
├─ category_id* (FK → CATEGORY)
├─ item_code* (Unique identifier, e.g., "ITEM-LAPTOP-001")
├─ name* (Generic item name, e.g., "Dell XPS 15 Laptop")
├─ description* (Rich text description)
├─ short_description? (Summary for listings)
├─ item_type* [GOOD, SERVICE] (FK → ENUM_ITEM_TYPE)
├─ brand? (Brand name)
├─ model? (Model number/name)
├─ manufacturer?
├─ specifications? (JSON - generic specs)
├─ need_want_classification? [NEED, WANT] (FK → ENUM_NEED_WANT_CLASSIFICATION)
├─ is_featured* (Boolean - highlight on homepage)
├─ is_trending* (Boolean - show in trending section)
├─ tags? (JSON array - for search)
├─ meta_title? (SEO title)
├─ meta_description? (SEO description)
├─ meta_keywords? (SEO keywords)
├─ status* [DRAFT, ACTIVE, INACTIVE, DISCONTINUED]
├─ created_by* (FK → PERSON)
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ITEM
  ← CATEGORY (Many:1)
  ← PERSON (Many:1) [via created_by]
  → ITEM_VARIANT (1:Many) [Different sellers offering this item]
  → ITEM_ATTRIBUTE_VALUE (1:Many) [Generic attributes]
  → ITEM_IMAGE (1:Many) [Product images]
  → CUSTOMER_REVIEW (1:Many) [Reviews aggregated from variants]
```

### Purpose
- **Generic representation** of a product/service
- **Standard reference** that multiple organizations can sell
- **Centralized information** shared across all variants
- **Discovery**: Customers search for items, then see variants (sellers)

### Item Discovery Flow
```
1. Customer searches "Dell XPS 15"
2. System finds ITEM "Dell XPS 15 Laptop"
3. System loads all ITEM_VARIANTS for this item
4. Variants sorted by:
   - Geographic proximity (nearest first)
   - Price (if selected)
   - Ratings (if selected)
5. Customer sees all sellers offering this item
```

---

## 4. ITEM_VARIANT (Organization-Specific Offering)

### Entity Structure
```
ITEM_VARIANT
├─ id* (PK)
├─ item_id* (FK → ITEM)
├─ organization_id* (FK → ORGANIZATION)
├─ organization_building_id? (FK → ORGANIZATION_BUILDING) [Fulfillment location]
├─ variant_code* (Unique, e.g., "VAR-ORG123-ITEM456")
├─ variant_name? (Organization-specific name override)
├─ variant_description? (Additional description)
├─ transaction_types* (JSON array: ["SALE"], ["RENT"], or ["SALE","RENT"])
├─ sale_price? (Price for purchase)
├─ sale_currency* (Currency code, default: "USD")
├─ rental_price_hourly?
├─ rental_price_daily?
├─ rental_price_weekly?
├─ rental_price_monthly?
├─ rental_currency?
├─ rental_deposit? (Security deposit for rentals)
├─ cost_price? (Internal cost - for margin calculation)
├─ discount_percentage? (Current discount)
├─ discounted_price? (Computed: sale_price - discount)
├─ tax_rate? (Applicable tax percentage)
├─ sku* (Stock Keeping Unit - unique per organization)
├─ barcode? (UPC/EAN barcode)
├─ condition* [NEW, REFURBISHED, USED_LIKE_NEW, USED_GOOD, USED_FAIR]
├─ warranty_period? (In months)
├─ warranty_terms?
├─ return_policy?
├─ availability_status* [IN_STOCK, OUT_OF_STOCK, PREORDER, DISCONTINUED]
├─ lead_time_days? (Delivery/fulfillment time)
├─ min_order_quantity? (Default: 1)
├─ max_order_quantity? (Purchase limit per order)
├─ variant_attributes? (JSON - org-specific attributes)
├─ is_subscribable* (Boolean - can be subscribed)
├─ rating_average? (Computed from reviews)
├─ review_count? (Count of reviews)
├─ view_count? (Page view counter)
├─ purchase_count? (Sales counter)
├─ status* [ACTIVE, INACTIVE, OUT_OF_STOCK]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ITEM_VARIANT
  ← ITEM (Many:1)
  ← ORGANIZATION (Many:1)
  ← ORGANIZATION_BUILDING (Many:1) [Optional - fulfillment location]
  → FINISHED_GOODS_INVENTORY (1:1) [If item_type = GOOD]
  → SERVICE_PROVIDER (1:Many) [If item_type = SERVICE]
  → SUBSCRIPTION_PLAN (1:Many)
  → SHOPPING_CART_ITEM (1:Many)
  → ORDER_ITEM (1:Many)
  → ITEM_ATTRIBUTE_VALUE (1:Many) [Variant-specific attributes]
  → CUSTOMER_REVIEW (1:Many)
```

### Geographic Sorting Logic
When displaying an item's variants:
```sql
SELECT
    iv.*,
    o.short_name as organization_name,
    ob.name as building_name,
    pa.latitude,
    pa.longitude,
    -- Calculate distance using Haversine formula
    (
        6371 * acos(
            cos(radians(?)) * cos(radians(pa.latitude)) *
            cos(radians(pa.longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(pa.latitude))
        )
    ) AS distance_km
FROM item_variant iv
JOIN item i ON iv.item_id = i.id
JOIN organization o ON iv.organization_id = o.id
LEFT JOIN organization_building ob ON iv.organization_building_id = ob.id
LEFT JOIN postal_address pa ON ob.postal_address_id = pa.id
WHERE iv.item_id = ?
  AND iv.status = 'ACTIVE'
  AND iv.availability_status IN ('IN_STOCK', 'PREORDER')
  AND iv.is_active = 1
  AND iv.deleted_at IS NULL
ORDER BY distance_km ASC, iv.sale_price ASC;
```

### Business Rules
1. **One Variant Per Organization Per Item:** Unique constraint on (item_id, organization_id)
2. **Transaction Type Validation:**
   - If SALE in transaction_types → sale_price required
   - If RENT in transaction_types → at least one rental_price_* required
3. **Inventory:** Goods must have FINISHED_GOODS_INVENTORY record
4. **Service Providers:** Services must have at least one SERVICE_PROVIDER
5. **Price Updates:** Changes logged for price history (future enhancement)

---

## 5. FINISHED_GOODS_INVENTORY

### Entity Structure
```
FINISHED_GOODS_INVENTORY
├─ id* (PK)
├─ item_variant_id* (FK → ITEM_VARIANT) [One-to-one]
├─ organization_building_id* (FK → ORGANIZATION_BUILDING) [Warehouse/store location]
├─ quantity_on_hand* (Current physical stock)
├─ quantity_reserved* (Reserved in carts/pending orders)
├─ quantity_available* (Computed: on_hand - reserved)
├─ quantity_committed* (Confirmed orders awaiting shipment)
├─ reorder_level* (Trigger reorder when stock falls below)
├─ reorder_quantity* (How much to reorder)
├─ maximum_stock_level? (Storage capacity)
├─ warehouse_location? (Bin/aisle location)
├─ batch_number? (For tracking batches)
├─ lot_number? (Manufacturing lot)
├─ expiry_date? (For perishable items)
├─ last_restocked_date?
├─ last_restocked_quantity?
├─ last_stock_check_date?
├─ notes?
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
FINISHED_GOODS_INVENTORY
  ← ITEM_VARIANT (1:1)
  ← ORGANIZATION_BUILDING (Many:1)
```

### Inventory Management Rules
1. **Stock Reservation Flow:**
   ```
   Add to cart → quantity_reserved++
   Checkout → quantity_reserved--, quantity_committed++
   Ship → quantity_committed--, quantity_on_hand--
   Cancel → reverse appropriate counter
   ```

2. **Stock Levels:**
   - `quantity_available = quantity_on_hand - quantity_reserved`
   - Cannot add to cart if `quantity_available <= 0`
   - Alert when `quantity_on_hand <= reorder_level`

3. **Multi-Warehouse:**
   - Same variant can have inventory in multiple buildings
   - Each building has separate inventory record

---

## 6. SERVICE_PROVIDER

### Entity Structure
```
SERVICE_PROVIDER
├─ id* (PK)
├─ item_variant_id* (FK → ITEM_VARIANT) [Service offering]
├─ person_id* (FK → PERSON) [Service provider]
├─ organization_id* (FK → ORGANIZATION)
├─ provider_code* (Unique identifier)
├─ specialization? (Area of expertise)
├─ experience_years?
├─ qualifications? (JSON array - degrees, certifications)
├─ license_number?
├─ license_expiry_date?
├─ hourly_rate?
├─ service_capacity* (Max concurrent appointments)
├─ availability_schedule? (JSON - weekly schedule)
├─ advance_booking_days* (How many days in advance to book)
├─ cancellation_policy?
├─ rating_average? (Computed from reviews)
├─ review_count?
├─ completed_appointments_count?
├─ status* [ACTIVE, INACTIVE, ON_LEAVE, SUSPENDED]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
SERVICE_PROVIDER
  ← ITEM_VARIANT (Many:1)
  ← PERSON (Many:1)
  ← ORGANIZATION (Many:1)
  → SERVICE_APPOINTMENT (1:Many)
  → CUSTOMER_REVIEW (1:Many)
```

### Availability Schedule Format (JSON)
```json
{
  "monday": {"enabled": true, "slots": [{"start": "09:00", "end": "17:00"}]},
  "tuesday": {"enabled": true, "slots": [{"start": "09:00", "end": "17:00"}]},
  "wednesday": {"enabled": false},
  "thursday": {"enabled": true, "slots": [{"start": "09:00", "end": "12:00"}, {"start": "14:00", "end": "18:00"}]},
  "friday": {"enabled": true, "slots": [{"start": "09:00", "end": "17:00"}]},
  "saturday": {"enabled": true, "slots": [{"start": "10:00", "end": "14:00"}]},
  "sunday": {"enabled": false}
}
```

---

## 7. SERVICE_APPOINTMENT

### Entity Structure
```
SERVICE_APPOINTMENT
├─ id* (PK)
├─ service_provider_id* (FK → SERVICE_PROVIDER)
├─ customer_id* (FK → PERSON)
├─ order_item_id? (FK → ORDER_ITEM) [If booked via order]
├─ shopping_cart_item_id? (FK → SHOPPING_CART_ITEM) [If pre-booking from cart]
├─ appointment_code* (Unique, e.g., "APT-2024-001")
├─ appointment_date* (Date of service)
├─ appointment_time* (Start time)
├─ duration_minutes* (Service duration)
├─ end_time* (Computed: appointment_time + duration)
├─ location_type* [ON_SITE, PROVIDER_LOCATION, CUSTOMER_LOCATION, ONLINE]
├─ service_address_id? (FK → POSTAL_ADDRESS) [If at customer location]
├─ meeting_link? (For online appointments)
├─ notes? (Customer notes/requests)
├─ provider_notes? (Internal provider notes)
├─ price_snapshot* (Price at booking time)
├─ status* [SCHEDULED, CONFIRMED, IN_PROGRESS, COMPLETED, CANCELLED, NO_SHOW, RESCHEDULED]
│    (FK → ENUM_APPOINTMENT_STATUS)
├─ scheduled_by* (FK → PERSON)
├─ confirmed_at?
├─ completed_at?
├─ cancelled_at?
├─ cancellation_reason?
├─ rescheduled_from_appointment_id? (FK → SERVICE_APPOINTMENT)
├─ reminder_sent* (Boolean)
├─ feedback_rating? (1-5 stars)
├─ feedback_comment?
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
SERVICE_APPOINTMENT
  ← SERVICE_PROVIDER (Many:1)
  ← PERSON (Many:1) [customer_id]
  ← PERSON (Many:1) [scheduled_by]
  ← ORDER_ITEM (Many:1) [Optional]
  ← SHOPPING_CART_ITEM (Many:1) [Optional]
  ← POSTAL_ADDRESS (Many:1) [Optional - service_address_id]
  ← SERVICE_APPOINTMENT (Many:1) [Optional - rescheduled_from]
```

### Status Flow
```
SCHEDULED → CONFIRMED → IN_PROGRESS → COMPLETED
    ↓           ↓             ↓
CANCELLED   CANCELLED    CANCELLED
    ↓
RESCHEDULED → (creates new appointment)

After appointment_time passed with no update:
SCHEDULED → NO_SHOW
```

### Business Rules
1. **Capacity Check:** Cannot book if provider at capacity for that time slot
2. **Advance Booking:** Cannot book within `advance_booking_days` window
3. **No Overlap:** Provider cannot have overlapping appointments
4. **Cancellation:** Free cancellation based on cancellation_policy
5. **Reminders:** Auto-send reminders 24h before appointment

---

## 8. SUBSCRIPTION_PLAN

### Entity Structure
```
SUBSCRIPTION_PLAN
├─ id* (PK)
├─ item_variant_id* (FK → ITEM_VARIANT)
├─ plan_code* (Unique, e.g., "SUB-WEEKLY-MEALS")
├─ plan_name* (e.g., "Weekly Meal Box")
├─ description?
├─ billing_interval* [DAILY, WEEKLY, MONTHLY, YEARLY]
│    (FK → ENUM_SUBSCRIPTION_INTERVAL)
├─ billing_interval_count* (e.g., 1 = every week, 2 = every 2 weeks)
├─ price_per_interval* (Subscription price)
├─ currency* (Default: "USD")
├─ trial_period_days? (Free trial days)
├─ setup_fee? (One-time setup cost)
├─ minimum_term_months? (Minimum commitment period)
├─ cancellation_notice_days? (Notice period to cancel)
├─ delivery_schedule? (JSON - for goods: days/times)
├─ service_frequency? (For services: appointments per interval)
├─ quantity_per_delivery* (Default: 1)
├─ is_auto_renew* (Boolean, default: true)
├─ renewal_reminder_days? (Days before renewal to remind)
├─ benefits? (JSON - plan perks)
├─ terms_and_conditions?
├─ status* [ACTIVE, INACTIVE, DISCONTINUED]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
SUBSCRIPTION_PLAN
  ← ITEM_VARIANT (Many:1)
  → CUSTOMER_SUBSCRIPTION (1:Many)
```

### Billing Interval Examples
```
DAILY + count=1        → Every day
WEEKLY + count=1       → Every week
WEEKLY + count=2       → Every 2 weeks (bi-weekly)
MONTHLY + count=1      → Every month
MONTHLY + count=3      → Every 3 months (quarterly)
YEARLY + count=1       → Every year
```

---

## 9. CUSTOMER_SUBSCRIPTION

### Entity Structure
```
CUSTOMER_SUBSCRIPTION
├─ id* (PK)
├─ subscription_plan_id* (FK → SUBSCRIPTION_PLAN)
├─ customer_id* (FK → PERSON)
├─ subscription_number* (Unique, e.g., "SUB-2024-001")
├─ start_date* (Subscription start date)
├─ trial_end_date? (If trial_period_days set)
├─ current_period_start* (Current billing cycle start)
├─ current_period_end* (Current billing cycle end)
├─ next_billing_date* (Next charge date)
├─ end_date? (Subscription end date, null if ongoing)
├─ cancellation_requested_date?
├─ cancellation_effective_date? (When cancellation takes effect)
├─ cancellation_reason?
├─ delivery_address_id? (FK → POSTAL_ADDRESS) [For goods]
├─ service_address_id? (FK → POSTAL_ADDRESS) [For services]
├─ payment_method_id* (FK → PAYMENT_METHOD)
├─ quantity* (Number of units per delivery, default: 1)
├─ billing_amount* (Amount charged per interval)
├─ status* [TRIAL, ACTIVE, PAUSED, CANCELLED, EXPIRED, PAYMENT_FAILED]
│    (FK → ENUM_SUBSCRIPTION_STATUS)
├─ total_billing_cycles_completed*
├─ pause_reason?
├─ paused_at?
├─ pause_until_date?
├─ notes?
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
CUSTOMER_SUBSCRIPTION
  ← SUBSCRIPTION_PLAN (Many:1)
  ← PERSON (Many:1) [customer_id]
  ← POSTAL_ADDRESS (Many:1) [delivery_address_id]
  ← POSTAL_ADDRESS (Many:1) [service_address_id]
  ← PAYMENT_METHOD (Many:1)
  → ORDER (1:Many) [Recurring orders generated]
  → SERVICE_APPOINTMENT (1:Many) [For service subscriptions]
```

### Status Flow
```
TRIAL → ACTIVE → {PAUSED, CANCELLED, EXPIRED}
  ↓               ↓
ACTIVE      ACTIVE (can resume)
  ↓
PAYMENT_FAILED (retry billing)
```

### Subscription Lifecycle
1. **Creation:** Customer subscribes to plan
2. **Trial Period:** Free trial if applicable
3. **Billing:** Auto-charge on `next_billing_date`
4. **Fulfillment:**
   - Goods: Create ORDER for delivery
   - Services: Create SERVICE_APPOINTMENT
5. **Renewal:** Update billing cycle, create next fulfillment
6. **Cancellation:** Honor `cancellation_notice_days`, cancel on `cancellation_effective_date`

---

## 10. SHOPPING_CART

### Entity Structure
```
SHOPPING_CART
├─ id* (PK)
├─ customer_id* (FK → PERSON)
├─ session_id? (For guest users before login)
├─ cart_status* [ACTIVE, ABANDONED, CONVERTED, MERGED]
│    (FK → ENUM_CART_STATUS)
├─ last_activity_at* (Updated on any cart action)
├─ abandoned_at? (Set when last_activity > X hours)
├─ converted_to_order_id? (FK → ORDER)
├─ converted_at?
├─ subtotal* (Sum of cart items, computed)
├─ tax_amount* (Computed based on items)
├─ shipping_amount?
├─ discount_amount?
├─ total* (Computed: subtotal + tax + shipping - discount)
├─ currency* (Default: "USD")
├─ notes?
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
SHOPPING_CART
  ← PERSON (Many:1) [customer_id]
  → SHOPPING_CART_ITEM (1:Many)
  → ORDER (1:1) [via converted_to_order_id]
```

### Cart States
- **ACTIVE:** Currently being used
- **ABANDONED:** No activity for X hours (configurable, e.g., 24h)
- **CONVERTED:** Successfully converted to order
- **MERGED:** Guest cart merged into user cart after login

### Business Rules
1. **One Active Cart Per Customer:** Only one ACTIVE cart per customer
2. **Guest Carts:** Use session_id until login
3. **Cart Merge:** On login, merge guest cart into user cart
4. **Abandonment:** Mark ABANDONED after inactivity threshold
5. **Expiration:** Delete ABANDONED carts after X days

---

## 11. SHOPPING_CART_ITEM

### Entity Structure
```
SHOPPING_CART_ITEM
├─ id* (PK)
├─ shopping_cart_id* (FK → SHOPPING_CART)
├─ item_variant_id* (FK → ITEM_VARIANT)
├─ quantity* (Number of units)
├─ transaction_type* [SALE, RENT] (FK → ENUM_TRANSACTION_TYPE)
├─ rental_start_date? (If transaction_type = RENT)
├─ rental_end_date? (If transaction_type = RENT)
├─ rental_duration_days? (Computed)
├─ unit_price* (Price snapshot at time of adding)
├─ discount_percentage?
├─ discount_amount?
├─ subtotal* (Computed: quantity × unit_price - discount)
├─ tax_amount?
├─ total* (Computed: subtotal + tax)
├─ subscription_plan_id? (FK → SUBSCRIPTION_PLAN) [If subscribing]
├─ is_subscription* (Boolean)
├─ service_appointment_request? (JSON - requested appointment details)
├─ notes? (Customer notes/preferences)
├─ added_at* (When added to cart)
├─ updated_at?
└─ is_active*
```

### Relationships
```
SHOPPING_CART_ITEM
  ← SHOPPING_CART (Many:1)
  ← ITEM_VARIANT (Many:1)
  ← SUBSCRIPTION_PLAN (Many:1) [Optional]
  → SERVICE_APPOINTMENT (1:1) [If service pre-booked]
```

### Item Type Handling

**For Goods (SALE):**
```
quantity = 3
unit_price = $10
subtotal = $30
Check inventory: FINISHED_GOODS_INVENTORY.quantity_available >= 3
Reserve stock: quantity_reserved += 3
```

**For Goods (RENT):**
```
quantity = 1
transaction_type = RENT
rental_start_date = 2024-01-15
rental_end_date = 2024-01-18
rental_duration_days = 3
unit_price = $15/day
subtotal = 3 × $15 = $45
Check rental availability for date range
```

**For Services:**
```
quantity = 1
transaction_type = SALE
service_appointment_request = {
  "preferred_date": "2024-01-20",
  "preferred_time": "10:00",
  "provider_id": "uuid-provider"
}
unit_price = $50
subtotal = $50
Check provider availability
```

**For Subscriptions:**
```
is_subscription = true
subscription_plan_id = "uuid-plan"
unit_price = $20/week
Create CUSTOMER_SUBSCRIPTION on checkout
```

---

## 12. ORDER

### Entity Structure
```
ORDER
├─ id* (PK)
├─ customer_id* (FK → PERSON)
├─ organization_id* (FK → ORGANIZATION) [Primary seller if multi-vendor]
├─ order_number* (Unique, e.g., "ORD-2024-0001")
├─ order_date*
├─ order_source* [WEB, MOBILE, POS, API]
├─ order_type* [REGULAR, SUBSCRIPTION_RENEWAL, PREORDER]
├─ customer_subscription_id? (FK → CUSTOMER_SUBSCRIPTION) [If subscription order]
├─ subtotal* (Sum of order items)
├─ tax_amount*
├─ shipping_amount?
├─ discount_amount?
├─ total_amount* (Computed: subtotal + tax + shipping - discount)
├─ currency* (Default: "USD")
├─ shipping_address_id* (FK → POSTAL_ADDRESS)
├─ billing_address_id* (FK → POSTAL_ADDRESS)
├─ payment_method_id* (FK → PAYMENT_METHOD)
├─ payment_status* [PENDING, AUTHORIZED, CAPTURED, FAILED, REFUNDED, PARTIALLY_REFUNDED]
├─ payment_transaction_id? (External payment gateway ID)
├─ paid_at?
├─ order_status* [PENDING, CONFIRMED, PROCESSING, PACKED, SHIPPED, OUT_FOR_DELIVERY,
│                 DELIVERED, CANCELLED, REFUNDED, RETURNED]
│    (FK → ENUM_ORDER_STATUS)
├─ confirmed_at?
├─ packed_at?
├─ shipped_at?
├─ delivered_at?
├─ expected_delivery_date?
├─ tracking_number?
├─ shipping_carrier?
├─ shipping_method_id? (FK → SHIPPING_METHOD)
├─ delivery_zone_id? (FK → DELIVERY_ZONE)
├─ delivery_instructions?
├─ cancellation_reason?
├─ cancelled_at?
├─ refund_amount?
├─ refund_reason?
├─ refunded_at?
├─ notes?
├─ internal_notes? (Staff-only notes)
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ORDER
  ← PERSON (Many:1) [customer_id]
  ← ORGANIZATION (Many:1)
  ← CUSTOMER_SUBSCRIPTION (Many:1) [Optional]
  ← POSTAL_ADDRESS (Many:1) [shipping_address_id]
  ← POSTAL_ADDRESS (Many:1) [billing_address_id]
  ← PAYMENT_METHOD (Many:1)
  ← SHIPPING_METHOD (Many:1) [Optional]
  ← DELIVERY_ZONE (Many:1) [Optional]
  → ORDER_ITEM (1:Many)
```

### Order Status Flow
```
PENDING → CONFIRMED → PROCESSING → PACKED → SHIPPED → OUT_FOR_DELIVERY → DELIVERED
  ↓          ↓            ↓           ↓         ↓            ↓
CANCELLED  CANCELLED  CANCELLED  CANCELLED  CANCELLED  CANCELLED

DELIVERED → RETURNED → REFUNDED
```

### Order Lifecycle
1. **Checkout:** Convert SHOPPING_CART to ORDER
2. **Payment:** Process payment, set payment_status
3. **Confirmation:** Confirm order, set order_status = CONFIRMED
4. **Fulfillment:**
   - Goods: Pick → Pack → Ship → Deliver
   - Services: Schedule appointment
5. **Completion:** Mark DELIVERED, update inventory
6. **Post-Delivery:** Handle returns/refunds if needed

---

## 13. ORDER_ITEM

### Entity Structure
```
ORDER_ITEM
├─ id* (PK)
├─ order_id* (FK → ORDER)
├─ item_variant_id* (FK → ITEM_VARIANT)
├─ item_name* (Snapshot at order time)
├─ sku* (Snapshot)
├─ quantity*
├─ transaction_type* [SALE, RENT] (FK → ENUM_TRANSACTION_TYPE)
├─ rental_start_date? (If RENT)
├─ rental_end_date? (If RENT)
├─ rental_duration_days?
├─ rental_return_due_date?
├─ rental_returned_at?
├─ rental_late_fee?
├─ unit_price* (Price snapshot)
├─ discount_percentage?
├─ discount_amount?
├─ subtotal* (Computed)
├─ tax_amount?
├─ total* (Computed)
├─ fulfillment_status* [PENDING, PREPARING, READY, SHIPPED, DELIVERED, RETURNED, CANCELLED]
│    (FK → ENUM_ORDER_ITEM_FULFILLMENT_STATUS)
├─ fulfilled_at?
├─ fulfillment_notes?
├─ service_appointment_id? (FK → SERVICE_APPOINTMENT) [If service item]
├─ is_subscription_item* (Boolean)
├─ notes?
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ORDER_ITEM
  ← ORDER (Many:1)
  ← ITEM_VARIANT (Many:1)
  ← SERVICE_APPOINTMENT (1:1) [Optional]
```

### Fulfillment Status Flow
```
PENDING → PREPARING → READY → SHIPPED → DELIVERED
   ↓          ↓         ↓        ↓
CANCELLED  CANCELLED  CANCELLED  RETURNED
```

### Inventory Impact
**On Order Confirmation:**
```
FINISHED_GOODS_INVENTORY:
  quantity_reserved -= order_item.quantity
  quantity_committed += order_item.quantity
```

**On Shipment:**
```
FINISHED_GOODS_INVENTORY:
  quantity_committed -= order_item.quantity
  quantity_on_hand -= order_item.quantity
```

**On Cancellation:**
```
FINISHED_GOODS_INVENTORY:
  quantity_reserved -= order_item.quantity (restore availability)
```

---

## 14. ITEM_ATTRIBUTE_DEFINITION

### Entity Structure
```
ITEM_ATTRIBUTE_DEFINITION
├─ id* (PK)
├─ category_id* (FK → CATEGORY) [Attributes specific to category]
├─ attribute_code* (Unique within category, e.g., "COLOR", "SIZE")
├─ attribute_name* (Display name, e.g., "Color", "Size")
├─ data_type* [TEXT, NUMBER, BOOLEAN, DATE, ENUM, MULTI_ENUM]
├─ enum_values? (JSON array for ENUM types)
├─ unit_of_measure? (e.g., "inches", "kg", "GB")
├─ is_required* (Boolean - must have value)
├─ is_filterable* (Boolean - show in filters)
├─ is_searchable* (Boolean - include in search)
├─ is_variant_defining* (Boolean - creates variant combinations)
├─ display_order?
├─ description?
├─ validation_regex?
├─ min_value?
├─ max_value?
├─ status* [ACTIVE, INACTIVE]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ITEM_ATTRIBUTE_DEFINITION
  ← CATEGORY (Many:1)
  → ITEM_ATTRIBUTE_VALUE (1:Many)
```

### Purpose
- Define category-specific attributes
- Example: "Laptops" category has attributes like Screen Size, RAM, Storage, Processor
- Controls filtering, search, and variant creation

### Example Definitions for "Laptops" Category
```
attribute_code: "SCREEN_SIZE"
attribute_name: "Screen Size"
data_type: ENUM
enum_values: ["13 inch", "15 inch", "17 inch"]
unit_of_measure: "inches"
is_filterable: true

attribute_code: "RAM"
attribute_name: "RAM"
data_type: ENUM
enum_values: ["8GB", "16GB", "32GB", "64GB"]
unit_of_measure: "GB"
is_filterable: true

attribute_code: "STORAGE"
attribute_name: "Storage"
data_type: ENUM
enum_values: ["256GB SSD", "512GB SSD", "1TB SSD"]
unit_of_measure: "GB"
is_filterable: true
```

---

## 15. ITEM_ATTRIBUTE_VALUE

### Entity Structure
```
ITEM_ATTRIBUTE_VALUE
├─ id* (PK)
├─ item_id? (FK → ITEM) [Generic item attribute]
├─ item_variant_id? (FK → ITEM_VARIANT) [Variant-specific attribute]
├─ attribute_definition_id* (FK → ITEM_ATTRIBUTE_DEFINITION)
├─ value_text? (For TEXT type)
├─ value_number? (For NUMBER type)
├─ value_boolean? (For BOOLEAN type)
├─ value_date? (For DATE type)
├─ value_enum? (For ENUM type - single value)
├─ value_multi_enum? (For MULTI_ENUM type - JSON array)
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ITEM_ATTRIBUTE_VALUE
  ← ITEM (Many:1) [Optional - generic attributes]
  ← ITEM_VARIANT (Many:1) [Optional - variant attributes]
  ← ITEM_ATTRIBUTE_DEFINITION (Many:1)
```

### Business Rules
1. **Either item_id OR item_variant_id:** Not both
2. **Value Column Selection:** Based on attribute_definition.data_type
3. **Enum Validation:** value_enum must exist in attribute_definition.enum_values

### Example Values
```
item_id: "uuid-dell-xps-15"
attribute_definition_id: "uuid-screen-size"
value_enum: "15 inch"

item_variant_id: "uuid-org123-dell-xps"
attribute_definition_id: "uuid-ram"
value_enum: "16GB"

item_variant_id: "uuid-org123-dell-xps"
attribute_definition_id: "uuid-storage"
value_enum: "512GB SSD"
```

---

## 16. DELIVERY_ZONE

### Entity Structure
```
DELIVERY_ZONE
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ zone_code* (Unique, e.g., "ZONE-NYC")
├─ zone_name* (e.g., "New York City")
├─ description?
├─ coverage_type* [POSTAL_CODE, CITY, RADIUS, POLYGON]
├─ postal_codes? (JSON array - for POSTAL_CODE type)
├─ city_ids? (JSON array of city IDs - for CITY type)
├─ center_latitude? (For RADIUS type)
├─ center_longitude? (For RADIUS type)
├─ radius_km? (For RADIUS type)
├─ polygon_coordinates? (JSON - GeoJSON for POLYGON type)
├─ delivery_fee*
├─ free_delivery_threshold? (Free delivery if order > amount)
├─ min_order_amount? (Minimum order to deliver)
├─ estimated_delivery_days*
├─ is_express_available* (Boolean)
├─ express_fee?
├─ express_delivery_hours?
├─ status* [ACTIVE, INACTIVE]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
DELIVERY_ZONE
  ← ORGANIZATION (Many:1)
  → ORDER (1:Many)
```

### Purpose
- Define delivery coverage areas
- Calculate shipping fees based on customer location
- Set delivery time estimates

---

## 17. SHIPPING_METHOD

### Entity Structure
```
SHIPPING_METHOD
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ method_code* (Unique, e.g., "STANDARD", "EXPRESS")
├─ method_name* (e.g., "Standard Shipping", "Express Delivery")
├─ description?
├─ carrier? (e.g., "FedEx", "UPS", "USPS")
├─ base_fee*
├─ fee_per_item?
├─ fee_per_weight_unit?
├─ weight_unit? (e.g., "kg", "lb")
├─ free_shipping_threshold?
├─ estimated_delivery_days*
├─ tracking_available* (Boolean)
├─ insurance_available* (Boolean)
├─ insurance_fee?
├─ max_weight?
├─ max_dimensions?
├─ status* [ACTIVE, INACTIVE]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
SHIPPING_METHOD
  ← ORGANIZATION (Many:1)
  → ORDER (1:Many)
```

---

## 18. PAYMENT_METHOD

### Entity Structure
```
PAYMENT_METHOD
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ payment_type* [CREDIT_CARD, DEBIT_CARD, PAYPAL, BANK_ACCOUNT, DIGITAL_WALLET]
├─ is_default* (Boolean)
├─ card_brand? (e.g., "Visa", "Mastercard")
├─ card_last_four? (Last 4 digits)
├─ card_expiry_month?
├─ card_expiry_year?
├─ cardholder_name?
├─ billing_address_id? (FK → POSTAL_ADDRESS)
├─ payment_gateway? (e.g., "Stripe", "PayPal")
├─ gateway_customer_id? (Customer ID in payment gateway)
├─ gateway_payment_method_id? (Payment method ID in gateway)
├─ status* [ACTIVE, EXPIRED, INVALID]
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
PAYMENT_METHOD
  ← PERSON (Many:1)
  ← POSTAL_ADDRESS (Many:1) [billing_address_id]
  → ORDER (1:Many)
  → CUSTOMER_SUBSCRIPTION (1:Many)
```

### Security Notes
- **Never store full card numbers**
- **Never store CVV**
- Store only last 4 digits and token from payment gateway
- Use payment gateway APIs for actual payment processing

---

## 19. CUSTOMER_REVIEW

### Entity Structure
```
CUSTOMER_REVIEW
├─ id* (PK)
├─ item_id? (FK → ITEM) [Generic item review]
├─ item_variant_id? (FK → ITEM_VARIANT) [Variant-specific review]
├─ service_provider_id? (FK → SERVICE_PROVIDER) [Provider review]
├─ order_item_id? (FK → ORDER_ITEM) [Verified purchase]
├─ customer_id* (FK → PERSON)
├─ rating* (1-5 stars)
├─ review_title?
├─ review_text?
├─ pros? (JSON array - positive points)
├─ cons? (JSON array - negative points)
├─ is_verified_purchase* (Boolean)
├─ helpful_count* (Upvotes)
├─ not_helpful_count* (Downvotes)
├─ review_date*
├─ status* [PENDING, APPROVED, REJECTED, FLAGGED]
├─ moderation_notes?
├─ organization_response?
├─ organization_response_date?
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
CUSTOMER_REVIEW
  ← ITEM (Many:1) [Optional]
  ← ITEM_VARIANT (Many:1) [Optional]
  ← SERVICE_PROVIDER (Many:1) [Optional]
  ← ORDER_ITEM (Many:1) [Optional]
  ← PERSON (Many:1) [customer_id]
```

### Business Rules
1. **Review Scope:** Can review item, variant, or service provider
2. **Verified Purchase:** is_verified_purchase = true if order_item_id exists
3. **One Review Per Purchase:** Customer can review each purchase once
4. **Rating Impact:** Aggregated ratings update item_variant.rating_average

---

## 20. ITEM_IMAGE

### Entity Structure
```
ITEM_IMAGE
├─ id* (PK)
├─ item_id? (FK → ITEM)
├─ item_variant_id? (FK → ITEM_VARIANT)
├─ media_file_id* (FK → MEDIA_FILE)
├─ image_type* [MAIN, GALLERY, THUMBNAIL, ZOOM]
├─ display_order*
├─ alt_text? (SEO alt text)
├─ is_primary* (Boolean - main display image)
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ITEM_IMAGE
  ← ITEM (Many:1) [Optional]
  ← ITEM_VARIANT (Many:1) [Optional]
  ← MEDIA_FILE (Many:1)
```

---

## Supporting Enumerations

### ENUM_ITEM_TYPE
```sql
INSERT INTO enum_item_type (id, code, name) VALUES
('uuid-1', 'GOOD', 'Physical Good'),
('uuid-2', 'SERVICE', 'Service');
```

### ENUM_TRANSACTION_TYPE
```sql
INSERT INTO enum_transaction_type (id, code, name) VALUES
('uuid-1', 'SALE', 'Sale/Purchase'),
('uuid-2', 'RENT', 'Rental/Lease');
```

### ENUM_NEED_WANT_CLASSIFICATION
```sql
INSERT INTO enum_need_want_classification (id, code, name) VALUES
('uuid-1', 'NEED', 'Essential Need'),
('uuid-2', 'WANT', 'Non-Essential Want');
```

### ENUM_SUBSCRIPTION_INTERVAL
```sql
INSERT INTO enum_subscription_interval (id, code, name) VALUES
('uuid-1', 'DAILY', 'Daily'),
('uuid-2', 'WEEKLY', 'Weekly'),
('uuid-3', 'MONTHLY', 'Monthly'),
('uuid-4', 'YEARLY', 'Yearly');
```

### ENUM_SUBSCRIPTION_STATUS
```sql
INSERT INTO enum_subscription_status (id, code, name) VALUES
('uuid-1', 'TRIAL', 'Trial Period'),
('uuid-2', 'ACTIVE', 'Active'),
('uuid-3', 'PAUSED', 'Paused'),
('uuid-4', 'CANCELLED', 'Cancelled'),
('uuid-5', 'EXPIRED', 'Expired'),
('uuid-6', 'PAYMENT_FAILED', 'Payment Failed');
```

### ENUM_ORDER_STATUS
```sql
INSERT INTO enum_order_status (id, code, name) VALUES
('uuid-1', 'PENDING', 'Pending'),
('uuid-2', 'CONFIRMED', 'Confirmed'),
('uuid-3', 'PROCESSING', 'Processing'),
('uuid-4', 'PACKED', 'Packed'),
('uuid-5', 'SHIPPED', 'Shipped'),
('uuid-6', 'OUT_FOR_DELIVERY', 'Out for Delivery'),
('uuid-7', 'DELIVERED', 'Delivered'),
('uuid-8', 'CANCELLED', 'Cancelled'),
('uuid-9', 'REFUNDED', 'Refunded'),
('uuid-10', 'RETURNED', 'Returned');
```

### ENUM_ORDER_ITEM_FULFILLMENT_STATUS
```sql
INSERT INTO enum_order_item_fulfillment_status (id, code, name) VALUES
('uuid-1', 'PENDING', 'Pending'),
('uuid-2', 'PREPARING', 'Preparing'),
('uuid-3', 'READY', 'Ready'),
('uuid-4', 'SHIPPED', 'Shipped'),
('uuid-5', 'DELIVERED', 'Delivered'),
('uuid-6', 'RETURNED', 'Returned'),
('uuid-7', 'CANCELLED', 'Cancelled');
```

### ENUM_APPOINTMENT_STATUS
```sql
INSERT INTO enum_appointment_status (id, code, name) VALUES
('uuid-1', 'SCHEDULED', 'Scheduled'),
('uuid-2', 'CONFIRMED', 'Confirmed'),
('uuid-3', 'IN_PROGRESS', 'In Progress'),
('uuid-4', 'COMPLETED', 'Completed'),
('uuid-5', 'CANCELLED', 'Cancelled'),
('uuid-6', 'NO_SHOW', 'No Show'),
('uuid-7', 'RESCHEDULED', 'Rescheduled');
```

### ENUM_CART_STATUS
```sql
INSERT INTO enum_cart_status (id, code, name) VALUES
('uuid-1', 'ACTIVE', 'Active'),
('uuid-2', 'ABANDONED', 'Abandoned'),
('uuid-3', 'CONVERTED', 'Converted to Order'),
('uuid-4', 'MERGED', 'Merged');
```

---

## Complete E-Commerce Flow Diagrams

### Flow 1: Buying a Product
```
1. Customer browses CATALOG → CATEGORY → ITEM
2. Views ITEM page showing all ITEM_VARIANTS sorted by distance
3. Selects nearest seller's ITEM_VARIANT
4. Checks FINISHED_GOODS_INVENTORY.quantity_available
5. Adds to SHOPPING_CART (creates SHOPPING_CART_ITEM)
6. Inventory: quantity_reserved++
7. Proceeds to checkout
8. Creates ORDER and ORDER_ITEM
9. Inventory: quantity_reserved--, quantity_committed++
10. Payment processed
11. Order CONFIRMED
12. Fulfillment: PACKED → SHIPPED
13. Inventory: quantity_committed--, quantity_on_hand--
14. Order DELIVERED
15. Customer leaves CUSTOMER_REVIEW
```

### Flow 2: Renting a Car
```
1. Customer searches ITEM "Toyota Camry 2024"
2. Views ITEM_VARIANTS (rental companies sorted by distance)
3. Selects variant with transaction_type = RENT
4. Specifies rental_start_date and rental_end_date
5. System calculates rental_price_daily × rental_duration_days
6. Adds to SHOPPING_CART
7. Checkout creates ORDER
8. ORDER_ITEM includes rental dates
9. Customer picks up car on rental_start_date
10. Returns car on rental_end_date (rental_returned_at set)
11. Late return? Calculate rental_late_fee
```

### Flow 3: Booking a Service
```
1. Customer browses ITEM "Haircut Service"
2. Views ITEM_VARIANTS (salons sorted by distance)
3. Selects variant, clicks "Book Appointment"
4. Views available SERVICE_PROVIDERS for that variant
5. Selects provider and preferred time slot
6. Adds to SHOPPING_CART with service_appointment_request
7. Checkout creates ORDER
8. System creates SERVICE_APPOINTMENT (status = SCHEDULED)
9. Reminder sent 24h before appointment
10. Status updated: CONFIRMED → IN_PROGRESS → COMPLETED
11. Customer leaves CUSTOMER_REVIEW for provider
```

### Flow 4: Subscribing to Meal Delivery
```
1. Customer views ITEM "Healthy Meal Box"
2. Selects ITEM_VARIANT from preferred restaurant
3. Views SUBSCRIPTION_PLAN options (Weekly, Monthly)
4. Selects "Weekly Plan" ($50/week)
5. Adds to SHOPPING_CART with is_subscription = true
6. Checkout creates CUSTOMER_SUBSCRIPTION
7. Status = TRIAL (if trial_period_days set)
8. After trial: Status = ACTIVE
9. Every week on next_billing_date:
   - Charge payment_method
   - Create ORDER for delivery
   - Update current_period_start/end
10. Customer can pause, resume, or cancel
```

---

## Cross-Domain Relationships

### To Organization Domain
```
CATALOG ← ORGANIZATION
ITEM_VARIANT ← ORGANIZATION
ITEM_VARIANT ← ORGANIZATION_BUILDING (fulfillment location)
FINISHED_GOODS_INVENTORY ← ORGANIZATION_BUILDING (warehouse)
SERVICE_PROVIDER ← ORGANIZATION
DELIVERY_ZONE ← ORGANIZATION
SHIPPING_METHOD ← ORGANIZATION
ORDER ← ORGANIZATION
```
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

### To Person Domain
```
CATALOG ← PERSON (via created_by)
ITEM ← PERSON (via created_by)
SERVICE_PROVIDER ← PERSON (service provider)
SERVICE_APPOINTMENT ← PERSON (customer, scheduled_by)
CUSTOMER_SUBSCRIPTION ← PERSON (customer)
SHOPPING_CART ← PERSON (customer)
ORDER ← PERSON (customer)
PAYMENT_METHOD ← PERSON
CUSTOMER_REVIEW ← PERSON (customer)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

### To Geographic Domain
```
ITEM_VARIANT ← ORGANIZATION_BUILDING ← POSTAL_ADDRESS (for geo-sorting)
SERVICE_APPOINTMENT ← POSTAL_ADDRESS (service_address_id)
CUSTOMER_SUBSCRIPTION ← POSTAL_ADDRESS (delivery/service address)
ORDER ← POSTAL_ADDRESS (shipping/billing address)
PAYMENT_METHOD ← POSTAL_ADDRESS (billing_address)
DELIVERY_ZONE uses city, postal_code for coverage
```
See: [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)

### To Media & File Domain
```
CATEGORY ← MEDIA_FILE (banner_image)
ITEM_IMAGE ← MEDIA_FILE
```
See: [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)

---

## Common Queries

### 1. Get Items in Category with Variants
```sql
SELECT
    i.id,
    i.name,
    i.item_type,
    COUNT(DISTINCT iv.id) as variant_count,
    MIN(iv.sale_price) as min_price,
    MAX(iv.sale_price) as max_price,
    AVG(iv.rating_average) as avg_rating
FROM item i
LEFT JOIN item_variant iv ON iv.item_id = i.id
    AND iv.status = 'ACTIVE'
    AND iv.deleted_at IS NULL
WHERE i.category_id = ?
  AND i.status = 'ACTIVE'
  AND i.deleted_at IS NULL
GROUP BY i.id
ORDER BY i.name;
```

### 2. Get Variants for Item Sorted by Distance
```sql
SELECT
    iv.*,
    o.short_name as organization_name,
    ob.name as building_name,
    pa.latitude,
    pa.longitude,
    pa.address_line_1,
    pa.city_name,
    (
        6371 * acos(
            cos(radians(?)) * cos(radians(pa.latitude)) *
            cos(radians(pa.longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(pa.latitude))
        )
    ) AS distance_km
FROM item_variant iv
JOIN organization o ON iv.organization_id = o.id
LEFT JOIN organization_building ob ON iv.organization_building_id = ob.id
LEFT JOIN postal_address pa ON ob.postal_address_id = pa.id
WHERE iv.item_id = ?
  AND iv.status = 'ACTIVE'
  AND iv.availability_status = 'IN_STOCK'
  AND iv.deleted_at IS NULL
ORDER BY distance_km ASC, iv.sale_price ASC;
```

### 3. Check Inventory Availability
```sql
SELECT
    iv.id,
    iv.sku,
    fgi.quantity_on_hand,
    fgi.quantity_reserved,
    fgi.quantity_available,
    CASE
        WHEN fgi.quantity_available >= ? THEN 'AVAILABLE'
        WHEN fgi.quantity_available > 0 THEN 'LIMITED'
        ELSE 'OUT_OF_STOCK'
    END as availability
FROM item_variant iv
JOIN finished_goods_inventory fgi ON fgi.item_variant_id = iv.id
WHERE iv.id = ?
  AND iv.deleted_at IS NULL
  AND fgi.deleted_at IS NULL;
```

### 4. Get Active Cart for Customer
```sql
SELECT
    sc.*,
    COUNT(sci.id) as item_count,
    SUM(sci.quantity) as total_items
FROM shopping_cart sc
LEFT JOIN shopping_cart_item sci ON sci.shopping_cart_id = sc.id
    AND sci.deleted_at IS NULL
WHERE sc.customer_id = ?
  AND sc.cart_status = 'ACTIVE'
  AND sc.deleted_at IS NULL
GROUP BY sc.id;
```

### 5. Get Customer Orders with Status
```sql
SELECT
    o.*,
    COUNT(DISTINCT oi.id) as item_count,
    SUM(oi.quantity) as total_items
FROM "order" o
LEFT JOIN order_item oi ON oi.order_id = o.id
    AND oi.deleted_at IS NULL
WHERE o.customer_id = ?
  AND o.deleted_at IS NULL
GROUP BY o.id
ORDER BY o.order_date DESC;
```

### 6. Get Subscription Deliveries
```sql
SELECT
    cs.*,
    sp.plan_name,
    sp.billing_interval,
    sp.price_per_interval,
    iv.variant_name,
    i.name as item_name,
    COUNT(o.id) as deliveries_count
FROM customer_subscription cs
JOIN subscription_plan sp ON cs.subscription_plan_id = sp.id
JOIN item_variant iv ON sp.item_variant_id = iv.id
JOIN item i ON iv.item_id = i.id
LEFT JOIN "order" o ON o.customer_subscription_id = cs.id
    AND o.deleted_at IS NULL
WHERE cs.customer_id = ?
  AND cs.status IN ('TRIAL', 'ACTIVE')
  AND cs.deleted_at IS NULL
GROUP BY cs.id
ORDER BY cs.next_billing_date;
```

### 7. Get Upcoming Service Appointments
```sql
SELECT
    sa.*,
    sp.person_id,
    p.first_name || ' ' || p.last_name as provider_name,
    iv.variant_name,
    i.name as service_name
FROM service_appointment sa
JOIN service_provider sp ON sa.service_provider_id = sp.id
JOIN person p ON sp.person_id = p.id
JOIN item_variant iv ON sp.item_variant_id = iv.id
JOIN item i ON iv.item_id = i.id
WHERE sa.customer_id = ?
  AND sa.appointment_date >= date('now')
  AND sa.status IN ('SCHEDULED', 'CONFIRMED')
  AND sa.deleted_at IS NULL
ORDER BY sa.appointment_date, sa.appointment_time;
```

---

## Data Integrity Rules

1. **Item Variant Uniqueness:**
   - One variant per organization per item
   - Unique constraint: (item_id, organization_id)

2. **Transaction Type Validation:**
   - SALE transaction requires sale_price
   - RENT transaction requires at least one rental_price_*

3. **Inventory Management:**
   - quantity_available = quantity_on_hand - quantity_reserved
   - Cannot add to cart if quantity_available <= 0
   - Alert when quantity_on_hand <= reorder_level

4. **Cart Constraints:**
   - One ACTIVE cart per customer
   - Cart items reference valid item_variants
   - Inventory reserved when added to cart

5. **Order Processing:**
   - Cannot create order without payment_method
   - Order total must match sum of order_items
   - Inventory committed on order confirmation

6. **Subscription Rules:**
   - Active subscriptions must have valid payment_method
   - Cannot cancel before minimum_term_months
   - Auto-renew creates next order on next_billing_date

7. **Appointment Booking:**
   - Cannot double-book service provider
   - Check availability_schedule before booking
   - Respect advance_booking_days

8. **Review Integrity:**
   - One review per customer per purchase
   - Verified purchase if order_item_id exists
   - Rating impacts item_variant.rating_average

9. **Soft Deletes:**
   - All entities use soft deletes (deleted_at)
   - Always filter `deleted_at IS NULL`

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Organization Domain:** [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)
- **Person Domain:** [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)
- **Geographic Domain:** [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)
- **Media & File Domain:** [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)

---

**Last Updated:** 2025-11-09
**Domain:** Marketplace & Commerce
