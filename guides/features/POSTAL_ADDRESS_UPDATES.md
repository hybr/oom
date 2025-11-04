# POSTAL_ADDRESS Entity Updates

## Summary
Added three new attributes to the POSTAL_ADDRESS entity to support better geographic hierarchy and address management.

## Changes Made

### New Attributes Added

1. **district** (Display Order: 6)
   - **Code**: `district`
   - **Name**: District / County
   - **Data Type**: text
   - **Required**: No
   - **Description**: District or county name
   - **Purpose**: Allows recording of administrative divisions between city and state levels

2. **city_id** (Display Order: 7)
   - **Code**: `city_id`
   - **Name**: City
   - **Data Type**: text (Foreign Key)
   - **Required**: Yes
   - **Description**: FK to City
   - **Purpose**: Links postal address to a specific city from the CITY entity

3. **state_id** (Display Order: 8)
   - **Code**: `state_id`
   - **Name**: State / Province
   - **Data Type**: text (Foreign Key)
   - **Required**: Yes
   - **Description**: FK to State
   - **Purpose**: Links postal address to a specific state from the STATE entity

### Updated Field Order

The attributes are now ordered as follows:
1. First Street (required)
2. Second Street
3. Area / Locality (required)
4. Landmark
5. Postal Code / PIN (required)
6. **District / County** (NEW)
7. **City** (NEW - FK to CITY)
8. **State / Province** (NEW - FK to STATE)
9. Latitude
10. Longitude
11. Address Type (required)
12. Is Primary (required)
13. Contact Person
14. Contact Phone
15. Delivery Instructions

### New Relationship

Added a new entity relationship:
- **ID**: rel-0008
- **From**: STATE (State / Province)
- **To**: POSTAL_ADDRESS
- **Type**: OneToMany
- **Name**: postal_addresses_by_state
- **FK Field**: state_id
- **Description**: A state has multiple postal addresses

This complements the existing relationship:
- **From**: CITY (City / Town)
- **To**: POSTAL_ADDRESS
- **FK Field**: city_id

## Database Changes

The following SQL operations were performed:
1. Updated `metadata/001-initial.sql` with new attribute definitions
2. Added new entity relationship for STATE to POSTAL_ADDRESS
3. Applied changes to `database/v4l.sqlite`
4. Fixed display_order conflicts

## Form Behavior

When creating or editing a postal address, users will now:
1. Be required to select a **City** (dropdown/autocomplete based on available cities)
2. Be required to select a **State** (dropdown/autocomplete based on available states)
3. Optionally enter a **District/County** name as free text

The form will automatically use the foreign key relationships to:
- Display city names from the CITY entity
- Display state names from the STATE entity
- Provide autocomplete/dropdown based on the number of available options

## Benefits

1. **Better Geographic Hierarchy**: Addresses can now be linked directly to both city and state entities
2. **Data Consistency**: Using foreign keys ensures addresses reference valid cities and states
3. **Flexible Addressing**: The district field accommodates various administrative division naming conventions
4. **Improved Queries**: Can easily filter addresses by state or city
5. **Data Integrity**: Relationships prevent orphaned address records

## Testing Recommendations

1. Test creating a new postal address with city and state selection
2. Verify that the district field accepts various text inputs
3. Test the autocomplete/dropdown behavior for city and state fields
4. Verify that existing addresses can be updated with the new fields
5. Test geocoding functionality works with the new field structure
