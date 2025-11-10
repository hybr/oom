# Marketplace Commerce Entities - Implementation Status

## Created Entities (12/29)

### Enumerations (9/9) ✅ COMPLETE
- ✅ 0059_enum_item_type.sql
- ✅ 0060_enum_transaction_type.sql
- ✅ 0061_enum_need_want_classification.sql
- ✅ 0062_enum_subscription_interval.sql
- ✅ 0063_enum_subscription_status.sql
- ✅ 0064_enum_order_status.sql
- ✅ 0065_enum_order_item_fulfillment_status.sql
- ✅ 0066_enum_appointment_status.sql
- ✅ 0067_enum_cart_status.sql

### Core Entities (3/20) - IN PROGRESS
- ✅ 0068_catalog.sql
- ✅ 0069_category.sql
- ✅ 0070_item.sql

---

## Remaining Entities to Create (17)

### Priority 1: Core Marketplace (8 entities)

#### 0071_item_variant.sql
**Dependencies:** item, organization, organization_building, enum_item_type, enum_transaction_type
**Key Fields:**
- id, item_id, organization_id, organization_building_id
- variant_code, variant_name, variant_description
- transaction_types (JSON: ["SALE"], ["RENT"], or both)
- sale_price, sale_currency
- rental_price_hourly, rental_price_daily, rental_price_weekly, rental_price_monthly
- rental_currency, rental_deposit
- cost_price, discount_percentage, discounted_price, tax_rate
- sku, barcode, condition (NEW, REFURBISHED, USED_LIKE_NEW, USED_GOOD, USED_FAIR)
- warranty_period, warranty_terms, return_policy
- availability_status (IN_STOCK, OUT_OF_STOCK, PREORDER, DISCONTINUED)
- lead_time_days, min_order_quantity, max_order_quantity
- variant_attributes (JSON)
- is_subscribable, rating_average, review_count, view_count, purchase_count
- status (ACTIVE, INACTIVE, OUT_OF_STOCK)
**Relationships:**
- many-to-one: ITEM, ORGANIZATION, ORGANIZATION_BUILDING
- one-to-one: FINISHED_GOODS_INVENTORY
- one-to-many: SERVICE_PROVIDER, SUBSCRIPTION_PLAN, SHOPPING_CART_ITEM, ORDER_ITEM

#### 0072_finished_goods_inventory.sql
**Dependencies:** item_variant, organization_building
**Key Fields:**
- id, item_variant_id (one-to-one), organization_building_id
- quantity_on_hand, quantity_reserved, quantity_available (computed)
- quantity_committed
- reorder_level, reorder_quantity, maximum_stock_level
- warehouse_location, batch_number, lot_number, expiry_date
- last_restocked_date, last_restocked_quantity, last_stock_check_date
**Relationships:**
- one-to-one: ITEM_VARIANT
- many-to-one: ORGANIZATION_BUILDING

#### 0073_service_provider.sql
**Dependencies:** item_variant, person, organization
**Key Fields:**
- id, item_variant_id, person_id, organization_id
- provider_code, specialization, experience_years
- qualifications (JSON), license_number, license_expiry_date
- hourly_rate, service_capacity (max concurrent appointments)
- availability_schedule (JSON - weekly schedule)
- advance_booking_days, cancellation_policy
- rating_average, review_count, completed_appointments_count
- status (ACTIVE, INACTIVE, ON_LEAVE, SUSPENDED)
**Relationships:**
- many-to-one: ITEM_VARIANT, PERSON, ORGANIZATION
- one-to-many: SERVICE_APPOINTMENT, CUSTOMER_REVIEW

#### 0074_service_appointment.sql
**Dependencies:** service_provider, person, order_item, shopping_cart_item, postal_address, enum_appointment_status
**Key Fields:**
- id, service_provider_id, customer_id, order_item_id (optional), shopping_cart_item_id (optional)
- appointment_code, appointment_date, appointment_time, duration_minutes, end_time (computed)
- location_type (ON_SITE, PROVIDER_LOCATION, CUSTOMER_LOCATION, ONLINE)
- service_address_id (FK to POSTAL_ADDRESS), meeting_link
- notes, provider_notes, price_snapshot
- status (FK to ENUM_APPOINTMENT_STATUS)
- scheduled_by, confirmed_at, completed_at, cancelled_at, cancellation_reason
- rescheduled_from_appointment_id (self-reference)
- reminder_sent, feedback_rating, feedback_comment
**Relationships:**
- many-to-one: SERVICE_PROVIDER, PERSON (customer), PERSON (scheduled_by), ORDER_ITEM, SHOPPING_CART_ITEM, POSTAL_ADDRESS
- self-reference: SERVICE_APPOINTMENT (rescheduled_from)

#### 0075_subscription_plan.sql
**Dependencies:** item_variant, enum_subscription_interval
**Key Fields:**
- id, item_variant_id, plan_code, plan_name, description
- billing_interval (FK to ENUM_SUBSCRIPTION_INTERVAL)
- billing_interval_count (e.g., 1=every week, 2=every 2 weeks)
- price_per_interval, currency
- trial_period_days, setup_fee, minimum_term_months, cancellation_notice_days
- delivery_schedule (JSON), service_frequency
- quantity_per_delivery, is_auto_renew, renewal_reminder_days
- benefits (JSON), terms_and_conditions
- status (ACTIVE, INACTIVE, DISCONTINUED)
**Relationships:**
- many-to-one: ITEM_VARIANT
- one-to-many: CUSTOMER_SUBSCRIPTION

#### 0076_customer_subscription.sql
**Dependencies:** subscription_plan, person, postal_address, payment_method, enum_subscription_status
**Key Fields:**
- id, subscription_plan_id, customer_id, subscription_number
- start_date, trial_end_date, current_period_start, current_period_end
- next_billing_date, end_date, cancellation_requested_date, cancellation_effective_date, cancellation_reason
- delivery_address_id, service_address_id, payment_method_id
- quantity, billing_amount
- status (FK to ENUM_SUBSCRIPTION_STATUS: TRIAL, ACTIVE, PAUSED, CANCELLED, EXPIRED, PAYMENT_FAILED)
- total_billing_cycles_completed, pause_reason, paused_at, pause_until_date
**Relationships:**
- many-to-one: SUBSCRIPTION_PLAN, PERSON, POSTAL_ADDRESS (delivery), POSTAL_ADDRESS (service), PAYMENT_METHOD
- one-to-many: ORDER (recurring orders)

#### 0077_shopping_cart.sql
**Dependencies:** person, order, enum_cart_status
**Key Fields:**
- id, customer_id, session_id (for guest users)
- cart_status (FK to ENUM_CART_STATUS: ACTIVE, ABANDONED, CONVERTED, MERGED)
- last_activity_at, abandoned_at, converted_to_order_id, converted_at
- subtotal (computed), tax_amount, shipping_amount, discount_amount, total (computed)
- currency
**Relationships:**
- many-to-one: PERSON
- one-to-one: ORDER (via converted_to_order_id)
- one-to-many: SHOPPING_CART_ITEM

#### 0078_shopping_cart_item.sql
**Dependencies:** shopping_cart, item_variant, subscription_plan, enum_transaction_type
**Key Fields:**
- id, shopping_cart_id, item_variant_id, quantity
- transaction_type (FK to ENUM_TRANSACTION_TYPE: SALE, RENT)
- rental_start_date, rental_end_date, rental_duration_days (computed)
- unit_price (snapshot), discount_percentage, discount_amount
- subtotal (computed), tax_amount, total (computed)
- subscription_plan_id, is_subscription
- service_appointment_request (JSON), notes
- added_at
**Relationships:**
- many-to-one: SHOPPING_CART, ITEM_VARIANT, SUBSCRIPTION_PLAN
- one-to-one: SERVICE_APPOINTMENT (if service pre-booked)

---

### Priority 2: Order Management (2 entities)

#### 0079_payment_method.sql
**Dependencies:** person, postal_address
**Key Fields:**
- id, person_id, payment_type (CREDIT_CARD, DEBIT_CARD, PAYPAL, BANK_ACCOUNT, DIGITAL_WALLET)
- is_default
- card_brand, card_last_four, card_expiry_month, card_expiry_year, cardholder_name
- billing_address_id
- payment_gateway (Stripe, PayPal), gateway_customer_id, gateway_payment_method_id
- status (ACTIVE, EXPIRED, INVALID)
**Security Note:** NEVER store full card numbers or CVV
**Relationships:**
- many-to-one: PERSON, POSTAL_ADDRESS (billing)
- one-to-many: ORDER, CUSTOMER_SUBSCRIPTION

#### 0080_order.sql
**Dependencies:** person, organization, customer_subscription, postal_address, payment_method, shipping_method, delivery_zone, enum_order_status
**Key Fields:**
- id, customer_id, organization_id, order_number
- order_date, order_source (WEB, MOBILE, POS, API)
- order_type (REGULAR, SUBSCRIPTION_RENEWAL, PREORDER)
- customer_subscription_id (if subscription order)
- subtotal, tax_amount, shipping_amount, discount_amount, total_amount, currency
- shipping_address_id, billing_address_id, payment_method_id
- payment_status (PENDING, AUTHORIZED, CAPTURED, FAILED, REFUNDED, PARTIALLY_REFUNDED)
- payment_transaction_id, paid_at
- order_status (FK to ENUM_ORDER_STATUS: PENDING, CONFIRMED, PROCESSING, PACKED, SHIPPED, OUT_FOR_DELIVERY, DELIVERED, CANCELLED, REFUNDED, RETURNED)
- confirmed_at, packed_at, shipped_at, delivered_at, expected_delivery_date
- tracking_number, shipping_carrier, shipping_method_id, delivery_zone_id
- delivery_instructions, cancellation_reason, cancelled_at
- refund_amount, refund_reason, refunded_at
**Relationships:**
- many-to-one: PERSON, ORGANIZATION, CUSTOMER_SUBSCRIPTION, POSTAL_ADDRESS (shipping), POSTAL_ADDRESS (billing), PAYMENT_METHOD, SHIPPING_METHOD, DELIVERY_ZONE
- one-to-many: ORDER_ITEM

#### 0081_order_item.sql
**Dependencies:** order, item_variant, service_appointment, enum_transaction_type, enum_order_item_fulfillment_status
**Key Fields:**
- id, order_id, item_variant_id, item_name (snapshot), sku (snapshot), quantity
- transaction_type (FK to ENUM_TRANSACTION_TYPE: SALE, RENT)
- rental_start_date, rental_end_date, rental_duration_days
- rental_return_due_date, rental_returned_at, rental_late_fee
- unit_price (snapshot), discount_percentage, discount_amount
- subtotal (computed), tax_amount, total (computed)
- fulfillment_status (FK to ENUM_ORDER_ITEM_FULFILLMENT_STATUS: PENDING, PREPARING, READY, SHIPPED, DELIVERED, RETURNED, CANCELLED)
- fulfilled_at, fulfillment_notes
- service_appointment_id, is_subscription_item
**Relationships:**
- many-to-one: ORDER, ITEM_VARIANT, SERVICE_APPOINTMENT

---

### Priority 3: Supporting Entities (7 entities)

#### 0082_item_attribute_definition.sql
**Dependencies:** category
**Key Fields:**
- id, category_id, attribute_code, attribute_name
- data_type (TEXT, NUMBER, BOOLEAN, DATE, ENUM, MULTI_ENUM)
- enum_values (JSON), unit_of_measure
- is_required, is_filterable, is_searchable, is_variant_defining
- display_order, description, validation_regex, min_value, max_value
- status (ACTIVE, INACTIVE)
**Example:** For "Laptops" category: Screen Size, RAM, Storage, Processor
**Relationships:**
- many-to-one: CATEGORY
- one-to-many: ITEM_ATTRIBUTE_VALUE

#### 0083_item_attribute_value.sql
**Dependencies:** item, item_variant, item_attribute_definition
**Key Fields:**
- id, item_id (optional), item_variant_id (optional), attribute_definition_id
- value_text, value_number, value_boolean, value_date, value_enum, value_multi_enum (JSON)
**Note:** Either item_id OR item_variant_id, not both
**Relationships:**
- many-to-one: ITEM (optional), ITEM_VARIANT (optional), ITEM_ATTRIBUTE_DEFINITION

#### 0084_delivery_zone.sql
**Dependencies:** organization
**Key Fields:**
- id, organization_id, zone_code, zone_name, description
- coverage_type (POSTAL_CODE, CITY, RADIUS, POLYGON)
- postal_codes (JSON), city_ids (JSON)
- center_latitude, center_longitude, radius_km
- polygon_coordinates (JSON - GeoJSON)
- delivery_fee, free_delivery_threshold, min_order_amount
- estimated_delivery_days, is_express_available, express_fee, express_delivery_hours
- status (ACTIVE, INACTIVE)
**Relationships:**
- many-to-one: ORGANIZATION
- one-to-many: ORDER

#### 0085_shipping_method.sql
**Dependencies:** organization
**Key Fields:**
- id, organization_id, method_code, method_name, description
- carrier (FedEx, UPS, USPS)
- base_fee, fee_per_item, fee_per_weight_unit, weight_unit
- free_shipping_threshold, estimated_delivery_days
- tracking_available, insurance_available, insurance_fee
- max_weight, max_dimensions
- status (ACTIVE, INACTIVE)
**Relationships:**
- many-to-one: ORGANIZATION
- one-to-many: ORDER

#### 0086_customer_review.sql
**Dependencies:** item, item_variant, service_provider, order_item, person
**Key Fields:**
- id, item_id (optional), item_variant_id (optional), service_provider_id (optional)
- order_item_id (for verified purchase), customer_id
- rating (1-5 stars), review_title, review_text
- pros (JSON array), cons (JSON array)
- is_verified_purchase, helpful_count, not_helpful_count
- review_date, status (PENDING, APPROVED, REJECTED, FLAGGED)
- moderation_notes, organization_response, organization_response_date
**Relationships:**
- many-to-one: ITEM (optional), ITEM_VARIANT (optional), SERVICE_PROVIDER (optional), ORDER_ITEM (optional), PERSON

#### 0087_item_image.sql
**Dependencies:** item, item_variant, media_file
**Key Fields:**
- id, item_id (optional), item_variant_id (optional), media_file_id
- image_type (MAIN, GALLERY, THUMBNAIL, ZOOM)
- display_order, alt_text, is_primary
**Relationships:**
- many-to-one: ITEM (optional), ITEM_VARIANT (optional), MEDIA_FILE

---

## Entity Creation Pattern

Each entity file should follow this structure:

```sql
-- =====================================================================
-- ENTITY_NAME Entity Metadata
-- Brief description
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ENTITY_NAME
-- =========================================
INSERT OR IGNORE INTO entity_definition (
    id,              -- UUID with pattern
    code,            -- UPPERCASE_SNAKE_CASE
    name,            -- Title Case
    description,     -- Full sentence description
    domain,          -- MARKETPLACE_COMMERCE
    table_name,      -- lowercase_snake_case
    is_active        -- 1
) VALUES (...);

-- =========================================
-- 2. Entity Attributes: ENTITY_NAME
-- =========================================
INSERT OR IGNORE INTO entity_attribute (
    id, entity_id, code, name, data_type, is_required, is_unique,
    is_system, is_label, default_value, min_value, max_value,
    enum_values, validation_regex, description, display_order
) VALUES
-- System Fields (7 fields: id, created_at, updated_at, deleted_at, version_no, created_by, updated_by)
(...),

-- Foreign Keys (list all FKs)
(...),

-- Core Fields (business fields)
(...);

-- =========================================
-- 3. Entity Relationships: ENTITY_NAME
-- =========================================
INSERT OR IGNORE INTO entity_relationship (
    id, from_entity_id, to_entity_id, relation_type,
    relation_name, fk_field, description
) VALUES
(...);
```

---

## UUID Patterns

Use consistent UUID prefixes for each entity:
- **item_variant**: `i1v1a1r1-i1a1-4n1t-xxxx-xxxxxxxxxxxx`
- **finished_goods_inventory**: `f1g1i1n1-v1e1-4n1t-xxxx-xxxxxxxxxxxx`
- **service_provider**: `s1p1r1o1-v1i1-4d1e-rxxx-xxxxxxxxxxxx`
- **service_appointment**: `s1a1p1p1-o1i1-4n1t-mxxx-xxxxxxxxxxxx`
- **subscription_plan**: `s1u1b1p1-l1a1-4n11-a111-111111111111`
- **customer_subscription**: `c1s1u1b1-s1c1-4r1p-t111-111111111111`
- **shopping_cart**: `s1c1a1r1-t000-4111-a111-111111111111`
- **shopping_cart_item**: `s1c1i1t1-e1m1-4111-a111-111111111111`
- **payment_method**: `p1a1y1m1-e1t1-4h1d-o111-111111111111`
- **order**: `o1r1d1e1-r000-4111-a111-111111111111`
- **order_item**: `o1r1d1i1-t1e1-4m11-a111-111111111111`
- **item_attribute_definition**: `i1a1t1d1-e1f1-4111-a111-111111111111`
- **item_attribute_value**: `i1a1t1v1-a1l1-4u1e-a111-111111111111`
- **delivery_zone**: `d1z1o1n1-e000-4111-a111-111111111111`
- **shipping_method**: `s1h1i1p1-m1e1-4t1d-h111-111111111111`
- **customer_review**: `c1r1e1v1-i1e1-4w11-a111-111111111111`
- **item_image**: `i1i1m1g1-e000-4111-a111-111111111111`

---

## Next Steps

1. Create remaining entity files (0071-0087)
2. Create data seed files in `/metadata/data/` for enumerations:
   - `enum_item_type_data.sql` (GOOD, SERVICE)
   - `enum_transaction_type_data.sql` (SALE, RENT)
   - `enum_need_want_classification_data.sql` (NEED, WANT)
   - `enum_subscription_interval_data.sql` (DAILY, WEEKLY, MONTHLY, YEARLY)
   - `enum_subscription_status_data.sql` (TRIAL, ACTIVE, PAUSED, etc.)
   - `enum_order_status_data.sql` (PENDING, CONFIRMED, PROCESSING, etc.)
   - `enum_order_item_fulfillment_status_data.sql` (PENDING, PREPARING, SHIPPED, etc.)
   - `enum_appointment_status_data.sql` (SCHEDULED, CONFIRMED, COMPLETED, etc.)
   - `enum_cart_status_data.sql` (ACTIVE, ABANDONED, CONVERTED, MERGED)

3. Test entity creation by running migration scripts
4. Create sample data for testing
5. Update entity relationship diagrams

---

## Status Summary

- **Total Entities**: 29 (9 enumerations + 20 core entities)
- **Created**: 12 (9 enumerations + 3 core entities)
- **Remaining**: 17 core entities
- **Progress**: 41% complete

---

**Last Updated:** 2025-11-09
**Domain:** Marketplace & Commerce
