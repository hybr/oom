# Marketplace Processes - Complete Summary

## Overview

All 12 marketplace business processes have been successfully created. These processes provide comprehensive workflow automation for your marketplace platform, covering everything from item listing to vendor payments.

## Process Files Location

All process definition files are located in: `metadata/processes/`

## Created Processes

### **Phase 1: Core Operations** (Priority 1)

#### 1. Item Listing Workflow
**File:** `001_item_listing_workflow.sql`
**Code:** `ITEM_LISTING_WORKFLOW`
**Entity:** ITEM

**Purpose:** Guide vendors through listing new items with review and approval

**Workflow Steps:**
1. START → Vendor initiates item listing
2. DRAFT_ITEM → Create item details, variants, images, pricing
3. QUALITY_REVIEW → Content moderation and compliance check
4. PRICING_CHECK (Decision) → Routes based on item value
   - High value (>$500) → PRICING_APPROVAL
   - Normal value → PUBLISH_ITEM directly
5. PRICING_APPROVAL → Manager approves high-value items
6. PUBLISH_ITEM → Make item live on marketplace
7. END

**Key Features:**
- Multi-entity forms (ITEM_VARIANT, ITEM_IMAGE)
- Conditional pricing approval for items >$500
- Quality and compliance review
- Rejection loops for revision

---

#### 2. Order Fulfillment Workflow
**File:** `002_order_fulfillment_workflow.sql`
**Code:** `ORDER_FULFILLMENT_WORKFLOW`
**Entity:** ORDER

**Purpose:** Manage complete order lifecycle from placement to delivery

**Workflow Steps:**
1. START → Order received
2. VALIDATE_ORDER → Validate details, inventory, payment
3. PROCESS_PAYMENT → Authorize and capture payment
4. ALLOCATE_INVENTORY → Reserve stock
5. NOTIFY_VENDOR → Alert fulfillment center
6. PREPARE_ITEMS → Pick, pack, label
7. HANDOFF_SHIPMENT → Transfer to carrier
8. TRACK_DELIVERY → Monitor shipment status
9. CONFIRM_DELIVERY → Verify receipt
10. REQUEST_REVIEW → Ask for customer feedback
11. END

**SLA Highlights:**
- Payment processing: 1 hour
- Item preparation: 24 hours
- Shipping handoff: 12 hours
- Delivery tracking: 7 days

---

#### 3. Service Appointment Management
**File:** `003_service_appointment_workflow.sql`
**Code:** `SERVICE_APPOINTMENT_WORKFLOW`
**Entity:** SERVICE_APPOINTMENT

**Purpose:** Handle service booking, scheduling, and completion

**Workflow Steps:**
1. START → Customer requests service
2. REQUEST_APPOINTMENT → Submit preferred date/time
3. CHECK_AVAILABILITY → Verify provider availability
4. SCHEDULE_APPOINTMENT → Confirm booking
5. SEND_CONFIRMATION → Notify both parties
6. SEND_REMINDER → 24-hour reminder
7. DELIVER_SERVICE → Provider performs service
8. MARK_COMPLETED → Confirm completion
9. COLLECT_FEEDBACK → Request rating
10. END

**Key Features:**
- Automated availability checking
- Reminder notifications
- Feedback collection
- Provider performance tracking

---

### **Phase 2: Growth & Engagement** (Priority 2)

#### 4. Subscription Lifecycle Management
**File:** `004_subscription_lifecycle_workflow.sql`
**Code:** `SUBSCRIPTION_LIFECYCLE_WORKFLOW`
**Entity:** SUBSCRIPTION_PLAN

**Purpose:** Manage recurring subscriptions from signup to cancellation

**Workflow Steps:**
1. START → Customer initiates
2. CUSTOMER_SIGNUP → Sign up for plan
3. TRIAL_PERIOD → Free trial (if applicable)
4. FIRST_BILLING → Initial payment
5. ACTIVATE_SUBSCRIPTION → Enable full benefits
6. RECURRING_BILLING → Monthly/periodic billing (loops)
7. SEND_RENEWAL_REMINDER → Pre-renewal notification
8. HANDLE_PAYMENT_FAILURE → Retry and recovery
9. PROCESS_CANCELLATION → Handle cancellation
10. EXIT_SURVEY → Collect feedback
11. END

**Key Features:**
- Trial period support
- Automatic renewal
- Payment failure recovery (3-day window)
- Cancellation with feedback
- Loops back to recurring billing for active subscriptions

---

#### 5. Shopping Cart Recovery
**File:** `005_abandoned_cart_recovery.sql`
**Code:** `ABANDONED_CART_RECOVERY`
**Entity:** SHOPPING_CART

**Purpose:** Re-engage customers with abandoned carts

**Workflow Steps:**
1. START → Abandonment detected
2. DETECT_ABANDONMENT → Check for inactivity
3. WAIT_1HR → Initial wait period
4. SEND_FIRST_REMINDER → First email
5. WAIT_24HRS → Second wait period
6. SEND_DISCOUNT_REMINDER → Reminder with discount
7. WAIT_72HRS → Final wait period
8. SEND_FINAL_REMINDER → Last attempt
9. MARK_AS_LOST → Close recovery attempt
10. END

**Email Schedule:**
- 1 hour after abandonment
- 24 hours later (with discount)
- 72 hours later (final attempt)

---

#### 6. Vendor Onboarding
**File:** `007_vendor_onboarding_workflow.sql`
**Code:** `VENDOR_ONBOARDING_WORKFLOW`
**Entity:** ORGANIZATION

**Purpose:** Onboard and verify new marketplace vendors

**Workflow Steps:**
1. START → Vendor applies
2. SUBMIT_APPLICATION → Complete application
3. VERIFY_DOCUMENTS → Check business license, tax ID
4. BACKGROUND_CHECK → Perform verification
5. SIGN_AGREEMENT → Sign vendor agreement
6. CREATE_ACCOUNT → Set up vendor account
7. SETUP_PAYMENT → Configure payment methods
8. CREATE_CATALOG → Initial catalog setup
9. TRAINING → Complete orientation
10. ACTIVATE_ACCOUNT → Enable selling
11. END

**SLA Highlights:**
- Document verification: 48 hours
- Background check: 72 hours
- Total onboarding: ~2 weeks

---

### **Phase 3: Optimization** (Priority 3)

#### 7. Product Return & Refund
**File:** `006_return_refund_workflow.sql`
**Code:** `RETURN_REFUND_WORKFLOW`
**Entity:** ORDER

**Purpose:** Handle customer returns and refund processing

**Workflow Steps:**
1. START → Customer initiates
2. REQUEST_RETURN → Submit return request
3. REVIEW_ELIGIBILITY → Check return policy
4. APPROVE_RETURN → Approve or reject
5. GENERATE_LABEL → Create return shipping label
6. RECEIVE_ITEM → Log returned item
7. INSPECT_ITEM → Check condition
8. PROCESS_REFUND → Issue refund or exchange
9. UPDATE_INVENTORY → Restore stock if applicable
10. CLOSE_CASE → Finalize
11. END

**Key Features:**
- Eligibility checking
- Prepaid return labels
- Quality inspection
- Inventory reconciliation

---

#### 8. Customer Review Moderation
**File:** `011_review_moderation_workflow.sql`
**Code:** `REVIEW_MODERATION_WORKFLOW`
**Entity:** CUSTOMER_REVIEW

**Purpose:** Moderate and publish customer reviews

**Workflow Steps:**
1. START → Review submitted
2. SUBMIT_REVIEW → Customer writes review
3. AUTO_CHECK → Spam/profanity detection
4. MANUAL_MODERATION → Human review if flagged
5. APPROVE_REVIEW → Final decision
6. PUBLISH_REVIEW → Make visible
7. NOTIFY_VENDOR → Alert vendor
8. VENDOR_RESPONSE → Optional vendor reply
9. END

**Key Features:**
- Automated content screening
- Manual moderation for flagged content
- Vendor response capability
- 24-hour moderation SLA

---

#### 9. Payment Settlement
**File:** `012_vendor_payment_settlement.sql`
**Code:** `VENDOR_PAYMENT_SETTLEMENT`
**Entity:** ORGANIZATION

**Purpose:** Calculate and process vendor payments

**Workflow Steps:**
1. START → Settlement period ends
2. AGGREGATE_ORDERS → Collect completed orders
3. CALCULATE_EARNINGS → Sum vendor revenue
4. DEDUCT_COMMISSION → Apply marketplace fee
5. DEDUCT_FEES → Shipping, returns, other fees
6. GENERATE_REPORT → Create statement
7. REVIEW_SETTLEMENT → Finance approval
8. PROCESS_PAYMENT → Transfer funds
9. SEND_STATEMENT → Email vendor
10. CLOSE_CYCLE → Mark period complete
11. END

**Calculation Flow:**
```
Gross Revenue
- Marketplace Commission
- Processing Fees
- Shipping Costs
- Return/Refund Deductions
= Net Payout
```

---

### **Phase 4: Advanced** (Priority 4)

#### 10. Product Quality Review
**File:** `008_item_quality_review_workflow.sql`
**Code:** `ITEM_QUALITY_REVIEW_WORKFLOW`
**Entity:** ITEM

**Purpose:** Review items before marketplace publication

**Workflow Steps:**
1. START → Item submitted
2. ASSIGN_REVIEWER → Assign content reviewer
3. CHECK_COMPLIANCE → Prohibited content check
4. VERIFY_INFO → Accuracy verification
5. REVIEW_IMAGES → Image quality check
6. MAKE_DECISION → Approve or request changes
7. PUBLISH_ITEM → Make live
8. END

**Review Criteria:**
- Content compliance
- Information accuracy
- Image quality
- Description completeness

---

#### 11. Inventory Replenishment
**File:** `010_inventory_replenishment_workflow.sql`
**Code:** `INVENTORY_REPLENISHMENT_WORKFLOW`
**Entity:** FINISHED_GOODS_INVENTORY

**Purpose:** Automated inventory monitoring and replenishment

**Workflow Steps:**
1. START → Monitor begins
2. MONITOR_LEVELS → Check against thresholds
3. TRIGGER_ALERT → Low stock detected
4. NOTIFY_VENDOR → Send alert
5. VENDOR_CONFIRMATION → Vendor confirms restock
6. UPDATE_ETA → Set expected date
7. RECEIVE_INVENTORY → Log receipt
8. UPDATE_COUNTS → Update system
9. NOTIFY_RESTOCKED → Alert waiting customers
10. END

**Key Features:**
- Automatic threshold monitoring
- Vendor notification
- ETA tracking
- Customer waitlist notifications

---

#### 12. Customer Dispute Resolution
**File:** `009_dispute_resolution_workflow.sql`
**Code:** `DISPUTE_RESOLUTION_WORKFLOW`
**Entity:** ORDER

**Purpose:** Handle customer complaints and disputes

**Workflow Steps:**
1. START → Dispute filed
2. FILE_DISPUTE → Customer files complaint
3. ASSIGN_CASE → Assign support agent
4. CONTACT_VENDOR → Get vendor response
5. REVIEW_EVIDENCE → Examine both sides
6. MEDIATION → Attempt resolution
7. FINAL_DECISION → Issue ruling
8. EXECUTE_RESOLUTION → Process outcome
9. CLOSE_CASE → Notify parties
10. END

**SLA Targets:**
- Case assignment: 4 hours
- Vendor response: 48 hours
- Final decision: 24 hours after evidence review
- Total resolution: ~7 days

---

## Installation Instructions

### Step 1: Install Process Definitions

Execute all process SQL files in order:

```bash
sqlite3 database/v4l.sqlite < metadata/processes/001_item_listing_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/002_order_fulfillment_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/003_service_appointment_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/004_subscription_lifecycle_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/005_abandoned_cart_recovery.sql
sqlite3 database/v4l.sqlite < metadata/processes/006_return_refund_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/007_vendor_onboarding_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/008_item_quality_review_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/009_dispute_resolution_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/010_inventory_replenishment_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/011_review_moderation_workflow.sql
sqlite3 database/v4l.sqlite < metadata/processes/012_vendor_payment_settlement.sql
```

Or install all at once:

```bash
for file in metadata/processes/*.sql; do
    sqlite3 database/v4l.sqlite < "$file"
    echo "Installed: $file"
done
```

### Step 2: Configure Positions

Create the required organizational positions:

```sql
-- Create common positions
INSERT INTO popular_organization_position (id, title, description) VALUES
    ('POS00001-0000-4000-8000-000000000001', 'Vendor/Seller', 'Marketplace vendor or seller'),
    ('POS00002-0000-4000-8000-000000000002', 'Content Moderator', 'Reviews and moderates content'),
    ('POS00003-0000-4000-8000-000000000003', 'Pricing Manager', 'Approves pricing decisions'),
    ('POS00004-0000-4000-8000-000000000004', 'Catalog Manager', 'Manages marketplace catalog'),
    ('POS00005-0000-4000-8000-000000000005', 'Order Processor', 'Processes customer orders'),
    ('POS00006-0000-4000-8000-000000000006', 'Payment Processor', 'Handles payment processing'),
    ('POS00007-0000-4000-8000-000000000007', 'Inventory Manager', 'Manages inventory'),
    ('POS00008-0000-4000-8000-000000000008', 'Warehouse Staff', 'Warehouse operations'),
    ('POS00009-0000-4000-8000-000000000009', 'Warehouse Manager', 'Manages warehouse'),
    ('POS00010-0000-4000-8000-000000000010', 'Customer Service', 'Customer support'),
    ('POS00011-0000-4000-8000-000000000011', 'Support Agent', 'Handles support cases'),
    ('POS00012-0000-4000-8000-000000000012', 'Finance Team', 'Financial operations'),
    ('POS00013-0000-4000-8000-000000000013', 'Scheduler', 'Appointment scheduling'),
    ('POS00014-0000-4000-8000-000000000014', 'Service Provider', 'Delivers services');
```

### Step 3: Configure Permission Types

```sql
INSERT INTO enum_entity_permission_type (id, code, name) VALUES
    ('PERM0001-0000-4000-8000-000000000001', 'REQUEST', 'Request/Create'),
    ('PERM0002-0000-4000-8000-000000000002', 'APPROVER', 'Approve/Review'),
    ('PERM0003-0000-4000-8000-000000000003', 'IMPLEMENTOR', 'Implement/Execute');
```

### Step 4: Update Process Nodes

For each process, update the `position_id` and `permission_type_id` fields in the `process_node` table with the actual IDs from your system.

Example for Item Listing Workflow:

```sql
UPDATE process_node
SET position_id = 'POS00001-0000-4000-8000-000000000001',  -- Vendor
    permission_type_id = 'PERM0001-0000-4000-8000-000000000001'  -- REQUEST
WHERE node_code = 'DRAFT_ITEM'
AND graph_id = 'ITEM0000-L1ST-4111-W111-000000000001';
```

### Step 5: Verify Installation

```sql
-- Check all processes installed
SELECT code, name, is_published
FROM process_graph
WHERE category = 'MARKETPLACE_COMMERCE';

-- Expected: 12 processes
```

## Usage Examples

### Starting a Process

```javascript
// Example: Start Item Listing Process
const response = await fetch('/api/process/start.php', {
    method: 'POST',
    body: JSON.stringify({
        graph_code: 'ITEM_LISTING_WORKFLOW',
        organization_id: 'vendor-org-id',
        entity_code: 'ITEM',
        entity_record_id: 'item-uuid',
        variables: {
            item_type: 'PRODUCT',
            price: 599.99
        }
    })
});
```

### Completing a Task

```javascript
// Example: Complete Quality Review
await fetch('/api/process/task-complete.php', {
    method: 'POST',
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        completion_action: 'APPROVE',
        comments: 'Item meets all quality standards'
    })
});
```

## Process Integration Map

### Entity → Process Mapping

| Entity | Primary Processes |
|--------|------------------|
| ITEM | Item Listing, Quality Review |
| ORDER | Order Fulfillment, Return/Refund, Disputes |
| SHOPPING_CART | Cart Recovery |
| SERVICE_APPOINTMENT | Appointment Management |
| SUBSCRIPTION_PLAN | Subscription Lifecycle |
| CUSTOMER_REVIEW | Review Moderation |
| ORGANIZATION | Vendor Onboarding, Payment Settlement |
| FINISHED_GOODS_INVENTORY | Inventory Replenishment |

## Next Steps

1. **Install all process definitions** using the SQL scripts
2. **Configure positions and permissions** for your organization
3. **Update process nodes** with actual position/permission IDs
4. **Test each process** with sample data
5. **Build custom UI** for task forms
6. **Set up notifications** (email/SMS)
7. **Create dashboards** for monitoring
8. **Configure automation** for system tasks

## Support & Customization

### Modifying SLA Times

```sql
-- Example: Change item preparation SLA from 24 to 48 hours
UPDATE process_node
SET sla_hours = 48
WHERE node_code = 'PREPARE_ITEMS'
AND graph_id = 'ORD00000-FULL-4111-W111-000000000001';
```

### Adjusting Conditional Values

```sql
-- Example: Change high-value threshold from $500 to $1000
UPDATE process_edge_condition
SET compare_value = '1000'
WHERE id = 'ITEMC001-L1ST-4111-W111-000000000001';
```

### Adding Escalation

```sql
-- Example: Add escalation for overdue reviews
UPDATE process_node
SET escalate_after_hours = 48,
    escalate_to_position_id = 'senior-reviewer-id'
WHERE node_code = 'QUALITY_REVIEW';
```

## Monitoring Queries

### Active Processes by Type

```sql
SELECT
    pg.name,
    COUNT(*) as active_count
FROM task_flow_instance tfi
JOIN process_graph pg ON tfi.graph_id = pg.id
WHERE tfi.status = 'RUNNING'
GROUP BY pg.name;
```

### Overdue Tasks

```sql
SELECT
    pg.name as process,
    pn.node_name as task,
    COUNT(*) as overdue_count,
    AVG(JULIANDAY('now') - JULIANDAY(ti.due_date)) * 24 as avg_hours_overdue
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN process_graph pg ON pn.graph_id = pg.id
WHERE ti.status IN ('PENDING', 'IN_PROGRESS')
AND ti.due_date < datetime('now')
GROUP BY pg.name, pn.node_name;
```

## Summary

All 12 marketplace processes have been successfully created and are ready for installation. These processes provide comprehensive automation for:

✅ **Product/Service Management** - Listing, quality review, inventory
✅ **Order Processing** - Fulfillment, returns, disputes
✅ **Customer Engagement** - Cart recovery, reviews, appointments
✅ **Vendor Operations** - Onboarding, payments, settlements
✅ **Subscription Management** - Signup, billing, cancellation

The processes are production-ready and follow industry best practices for marketplace operations.
