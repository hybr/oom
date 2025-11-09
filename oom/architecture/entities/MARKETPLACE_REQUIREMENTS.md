# Marketplace System Requirements

## Overview
A comprehensive marketplace system for buying, selling, and renting goods and services with geographic-based seller discovery, inventory management, service booking, and subscription capabilities.

---

## Core Concepts

### 1. Catalog Structure
```
Catalog
  └── Category
        └── Item (Generic Product/Service)
              └── Item Variant (Organization-specific offering)
```

- **Catalog**: Top-level container for organizing products/services
- **Category**: Grouping of related items within a catalog
- **Item**: Generic representation of a product/service with standard information
- **Item Variant**: Organization-specific implementation of an item (pricing, availability, terms)

### 2. Item Classification

#### By Type:
- **Goods**: Physical products managed through finished goods inventory
- **Services**: Intangible offerings managed by service providers

#### By Transaction Type:
- **Sale**: One-time purchase (ownership transfer)
- **Rent**: Temporary usage (time-based pricing)

#### By Fulfillment:
- **Needs**: Essential items/services
- **Wants**: Non-essential items/services

---

## Key Features

### A. Inventory & Provider Management
- **Finished Goods Inventory**: Track stock levels, SKUs, warehouse locations for physical goods
- **Service Providers**: Manage service capacity, availability, qualifications

### B. Booking & Appointments
- Services can be booked with specific time slots
- Appointment scheduling system
- Resource allocation for service delivery

### C. Subscription System
- Both goods and services can be offered as subscriptions
- Recurring delivery/service intervals
- Subscription plans with different tiers/pricing

### D. Shopping Cart & Checkout
- Add items (goods/services) to cart
- Support for both purchase and rental transactions
- Mixed cart (multiple items, sellers, transaction types)
- Checkout with payment processing

### E. Geographic-Based Discovery
- **Item Listing Page**: Show all variants (sellers) of a generic item
- **Seller Sorting**: Order by geographic proximity (nearest first)
- **Distance Calculation**: Use geo-coordinates for person/organization addresses

#### Geographic Data Sources:
1. **Person (Buyer)**: Geo-coordinates from shipping address (postal_address)
2. **Organization (Seller)**: Geo-coordinates from building address (organization_building → postal_address)

---

## Entity Requirements

### Catalog Entities
1. **Catalog**
   - ID, name, description
   - Organization owner
   - Active/inactive status
   - Created/updated timestamps

2. **Category**
   - ID, name, description
   - Parent category (for hierarchical structure)
   - Catalog reference
   - Display order, icon/image

3. **Item** (Generic Product/Service)
   - ID, name, description
   - Category reference
   - Item type (good/service)
   - Generic attributes (brand, model, specifications)
   - Images, videos
   - Created/updated timestamps

4. **Item Variant** (Organization-specific)
   - ID, reference to generic Item
   - Organization reference
   - Transaction types (sale/rent/both)
   - Sale price, rental price (hourly/daily/weekly/monthly)
   - Availability status
   - Organization building (fulfillment location)
   - Variant-specific attributes
   - Active/inactive status

### Inventory Management
5. **Finished Goods Inventory**
   - ID, item variant reference
   - Organization building/warehouse
   - Quantity on hand
   - Quantity reserved (in carts/orders)
   - Quantity available
   - SKU, barcode
   - Reorder level, reorder quantity
   - Last restocked date

### Service Management
6. **Service Provider**
   - ID, item variant reference (for services)
   - Person reference (service provider)
   - Organization reference
   - Availability schedule
   - Hourly rate, service capacity
   - Qualifications/certifications
   - Active/inactive status

7. **Service Appointment**
   - ID, service provider reference
   - Customer (person) reference
   - Appointment date/time
   - Duration
   - Status (scheduled/completed/cancelled)
   - Notes

### Subscription System
8. **Subscription Plan**
   - ID, item variant reference
   - Plan name (e.g., "Monthly", "Annual")
   - Billing interval (daily/weekly/monthly/yearly)
   - Price per interval
   - Trial period (days)
   - Active/inactive status

9. **Customer Subscription**
   - ID, subscription plan reference
   - Customer (person) reference
   - Start date, end date
   - Next billing date
   - Status (active/paused/cancelled/expired)
   - Delivery/service address
   - Payment method reference

### Shopping Cart & Orders
10. **Shopping Cart**
    - ID, customer (person) reference
    - Created/updated timestamps
    - Status (active/abandoned/converted)

11. **Shopping Cart Item**
    - ID, shopping cart reference
    - Item variant reference
    - Quantity
    - Transaction type (sale/rent)
    - Rental duration (if applicable)
    - Price snapshot (at time of adding)
    - Subscription plan reference (if applicable)

12. **Order**
    - ID, customer reference
    - Order number
    - Order date
    - Total amount
    - Status (pending/confirmed/shipped/delivered/cancelled)
    - Shipping address reference
    - Billing address reference
    - Payment status

13. **Order Item**
    - ID, order reference
    - Item variant reference
    - Quantity
    - Transaction type (sale/rent)
    - Unit price (snapshot)
    - Subtotal
    - Rental start/end dates (if applicable)
    - Fulfillment status

### Classification & Metadata
14. **Item Need/Want Classification**
    - Reference to Item
    - Classification type (need/want)
    - Category-specific (optional)

15. **Item Attribute Definition**
    - ID, category reference
    - Attribute name (e.g., "Color", "Size", "Power")
    - Data type (text/number/boolean/enum)
    - Required/optional

16. **Item Attribute Value**
    - ID, item/item variant reference
    - Attribute definition reference
    - Value

---

## Geographic Sorting Logic

When a visitor views an **Item** page:

1. Query all **Item Variants** for that item where `active = true`
2. For each variant, get the organization's building address
3. Extract geo-coordinates (latitude, longitude) from `postal_address`
4. Calculate distance from visitor's location (if available) or default location
5. Sort variants by distance (ascending)
6. Display sorted list of sellers

### Distance Calculation Formula:
Use Haversine formula for calculating great-circle distance between two points on Earth.

---

## Data Flow Examples

### Example 1: Buying a Laptop
1. Visitor browses **Catalog** → **Category** "Electronics" → **Item** "Dell XPS 15"
2. System shows **Item Variants** from multiple organizations (Best Tech, Computer World, etc.)
3. Variants sorted by distance from visitor's shipping address
4. Visitor selects variant from "Best Tech" (nearest seller)
5. Adds to **Shopping Cart** with quantity 1, transaction type "sale"
6. Proceeds to checkout, creates **Order**
7. System checks **Finished Goods Inventory** for availability
8. Order confirmed, inventory quantity reduced

### Example 2: Renting a Car
1. Visitor searches **Catalog** → **Category** "Vehicles" → **Item** "Toyota Camry 2024"
2. System shows **Item Variants** from rental companies sorted by distance
3. Visitor selects variant, chooses transaction type "rent"
4. Specifies rental duration (3 days)
5. System calculates total (daily rate × 3 days)
6. Adds to cart, proceeds to checkout
7. Order created with rental start/end dates

### Example 3: Booking a Haircut Service
1. Visitor browses **Catalog** → **Category** "Personal Care" → **Item** "Haircut Service"
2. System shows **Item Variants** (salons/barbers) sorted by distance
3. Visitor selects variant, views available **Service Providers**
4. Books **Service Appointment** with specific provider
5. Appointment confirmed in system

### Example 4: Subscribing to Meal Delivery
1. Visitor views **Item** "Healthy Meal Box"
2. Selects **Item Variant** from preferred restaurant
3. Chooses **Subscription Plan** "Weekly Plan"
4. Creates **Customer Subscription**
5. System creates recurring orders every week
6. First delivery scheduled based on subscription start date

---

## Key Relationships

- `Item` → `Category` (many-to-one)
- `Item Variant` → `Item` (many-to-one)
- `Item Variant` → `Organization` (many-to-one)
- `Item Variant` → `Organization Building` (many-to-one) - for fulfillment location
- `Finished Goods Inventory` → `Item Variant` (one-to-one for goods)
- `Service Provider` → `Item Variant` (many-to-one for services)
- `Service Provider` → `Person` (many-to-one)
- `Subscription Plan` → `Item Variant` (many-to-one)
- `Customer Subscription` → `Subscription Plan` (many-to-one)
- `Shopping Cart` → `Person` (many-to-one)
- `Shopping Cart Item` → `Shopping Cart` (many-to-one)
- `Shopping Cart Item` → `Item Variant` (many-to-one)
- `Order` → `Person` (many-to-one)
- `Order Item` → `Order` (many-to-one)
- `Order Item` → `Item Variant` (many-to-one)

---

## Business Rules

1. **Item Variants**: Each organization can create only one variant per generic item
2. **Inventory**: Only tracked for goods, not services
3. **Geo-coordinates**: Mandatory for postal_address to enable distance sorting
4. **Transaction Types**: Item variants can support sale only, rent only, or both
5. **Subscriptions**: Can be created for both goods (recurring delivery) and services (recurring appointments)
6. **Cart Expiration**: Abandoned carts expire after X days (configurable)
7. **Price Snapshots**: Shopping cart and orders store price snapshots to handle price changes
8. **Service Capacity**: Service providers have maximum concurrent appointments
9. **Rental Returns**: System tracks return dates and late fees for rentals
10. **Local Preference**: Default sorting always prioritizes geographic proximity

---

## Enumerations Needed

1. **enum_item_type**: good, service
2. **enum_transaction_type**: sale, rent
3. **enum_need_want_classification**: need, want
4. **enum_subscription_interval**: daily, weekly, monthly, yearly
5. **enum_subscription_status**: active, paused, cancelled, expired
6. **enum_order_status**: pending, confirmed, processing, shipped, delivered, cancelled, refunded
7. **enum_order_item_fulfillment_status**: pending, preparing, shipped, delivered, returned
8. **enum_appointment_status**: scheduled, confirmed, in_progress, completed, cancelled, no_show
9. **enum_cart_status**: active, abandoned, converted

---

## Questions & Clarifications Needed

1. **Multi-warehouse support**: Can an organization have inventory across multiple buildings?
   - Current assumption: Yes, via organization_building reference in inventory

2. **Rental deposit system**: Do rentals require security deposits?
   - Not specified yet - may need separate entity

3. **Service appointments vs subscriptions**: Can services have subscriptions (e.g., weekly cleaning)?
   - Current assumption: Yes, subscription creates recurring appointments

4. **Shipping/delivery fees**: How are these calculated?
   - Not specified yet - may need delivery_zone or distance-based pricing entity

5. **Returns/refunds**: What's the process for returned goods?
   - Not specified yet - may need returns entity

6. **Payment integration**: How are payments processed?
   - Assume integration with existing person_credential or new payment_method entity

7. **Reviews/ratings**: Can customers review items/variants/sellers?
   - Not specified yet - may need review entity

8. **Item variants vs inventory**: Should variant be separate from inventory record?
   - Current assumption: Yes, variant = "what we sell", inventory = "how much we have"

---

## Next Steps

Once this requirements document is approved:

1. Review existing entities (person, organization, postal_address, organization_building)
2. Create enumeration entities (enum_item_type, enum_transaction_type, etc.)
3. Create core catalog entities (catalog, category, item, item_variant)
4. Create inventory/service entities
5. Create subscription entities
6. Create cart/order entities
7. Create supporting entities (attributes, appointments, etc.)
8. Update entity relationship diagrams
9. Define foreign key constraints based on dependency order
10. Generate SQL files with proper sequence numbering
