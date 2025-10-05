# Entity Pages Guide

## ✅ Complete Web Interface Created

All entity pages have been successfully created with full CRUD functionality, responsive design, and user-friendly interfaces.

## 📂 Entity Pages Structure

Each entity has the following pages:

### Standard Pages (All Entities)
1. **List** (`list.php`) - Browse all records with pagination, search, and filters
2. **Detail** (`detail.php`) - View single record with relationships and history
3. **Create** (`create.php`) - Add new record with validation
4. **Edit** (`edit.php`) - Update existing record with optimistic locking
5. **Store** (`store.php`) - Process create form submission
6. **Update** (`update.php`) - Process edit form submission
7. **Delete** (`delete.php`) - Soft delete record with confirmation

## 🌍 Geography Domain

### 1. Continents (`/continents`)
**Entity**: `Entities\Continent`

**Features**:
- ✅ List with search and pagination
- ✅ View with countries count
- ✅ Create/Edit with name validation
- ✅ Soft delete with confirmation
- ✅ Relationship display: Shows all countries
- ✅ Quick actions: Add country to continent

**Fields**:
- `name` (required, min: 2, max: 100)

**Relationships**:
- Has Many → Countries
- Country count badge

### 2. Countries (`/countries`)
**Entity**: `Entities\Country`

**Features**:
- ✅ List with continent filter and search
- ✅ View with continent link and languages
- ✅ Create/Edit with continent selection
- ✅ Filter by continent dropdown
- ✅ Soft delete protection

**Fields**:
- `name` (required, min: 2, max: 100)
- `continent_id` (required, FK to Continent)

**Relationships**:
- Belongs To → Continent
- Has Many → Languages
- Has Many → Postal Addresses

### 3. Languages (`/languages`)
**Entity**: `Entities\Language`

**Features**:
- ✅ List with country filter
- ✅ View with country details
- ✅ Create/Edit with country selection
- ✅ Full name display (Language + Country)

**Fields**:
- `name` (required, min: 2, max: 100)
- `country_id` (required, FK to Country)

**Relationships**:
- Belongs To → Country

### 4. Postal Addresses (`/postal_addresses`)
**Entity**: `Entities\PostalAddress`

**Features**:
- ✅ List with location search
- ✅ View with formatted address display
- ✅ Create/Edit with all address fields
- ✅ Geo-coordinates support
- ✅ Distance calculations (if coordinates present)
- ✅ Multi-line and one-line formatting

**Fields**:
- `first_street`
- `second_street`
- `area`
- `city` (required)
- `state`
- `pin`
- `latitude` (decimal)
- `longitude` (decimal)
- `country_id` (required, FK to Country)

**Special Methods**:
- `getFormattedAddress()` - One-line address
- `getMultiLine()` - Multi-line array
- `distanceTo($other)` - Calculate distance in km
- `findNear($lat, $lng, $radius)` - Proximity search

## 👤 Person Domain

### 5. Persons (`/persons`)
**Entity**: `Entities\Person`

**Features**:
- ✅ List with name search
- ✅ View with full profile
- ✅ Create/Edit with name validation
- ✅ Age calculation from DOB
- ✅ Credential relationship display
- ✅ Education and skills listing

**Fields**:
- `first_name` (required, min: 2, max: 100)
- `middle_name`
- `last_name` (required, min: 2, max: 100)
- `date_of_birth`

**Special Methods**:
- `getFullName()` - Formatted full name
- `getInitials()` - Person initials
- `getAge()` - Age from DOB
- `hasCredential()` - Check if user account exists

**Relationships**:
- Has One → Credential
- Has Many → PersonEducation
- Has Many → PersonSkill

### 6. Credentials (`/credentials`)
**Entity**: `Entities\Credential`

**Features**:
- ✅ List with username search
- ✅ View with person details
- ✅ Security: Password hashing (Argon2ID)
- ✅ Account lockout after 5 failed attempts
- ✅ Password reset tokens
- ✅ Remember me functionality
- ✅ Sensitive data excluded from display

**Fields**:
- `username` (required, unique, min: 3, max: 100)
- `password_hash` (never displayed)
- `person_id` (required, FK to Person)
- `failed_login_attempts`
- `locked_until`
- `reset_token` (hidden)
- `remember_token` (hidden)

**Special Methods**:
- `login($username, $password)` - Authenticate
- `signUp($personId, $username, $password)` - Create account
- `forgotPassword()` - Generate reset token
- `resetPassword($token, $newPassword)` - Reset password
- `changePassword($current, $new)` - Change password
- `isLocked()` - Check account lock status

**Relationships**:
- Belongs To → Person

## 🎓 Education & Skill Domain

### 7. Popular Education Subjects (`/popular_education_subjects`)
**Entity**: `Entities\PopularEducationSubject`

**Features**:
- ✅ Reference data management
- ✅ List with search
- ✅ Create/Edit subjects
- ✅ Used in education records

**Fields**:
- `name` (required, min: 2, max: 200)

### 8. Popular Skills (`/popular_skills`)
**Entity**: `Entities\PopularSkill`

**Features**:
- ✅ Reference data management
- ✅ List with search
- ✅ Create/Edit skills
- ✅ Used in skill records

**Fields**:
- `name` (required, min: 2, max: 200)

### 9. Person Education (`/person_education`)
**Entity**: `Entities\PersonEducation`

**Features**:
- ✅ Education history tracking
- ✅ List by person
- ✅ Create/Edit with education levels
- ✅ Duration calculations
- ✅ Completion status
- ✅ Subject grades linking

**Fields**:
- `person_id` (required, FK to Person)
- `institution` (required, min: 2, max: 200)
- `start_date`
- `complete_date`
- `education_level` (ENUM: NONE, PRIMARY, SECONDARY, BACHELOR, MASTER, DOCTORATE, VOCATIONAL, CERTIFICATION)

**Special Methods**:
- `isCompleted()` - Check if education is complete
- `getDurationYears()` - Calculate duration
- `getSubjects()` - Get all subjects with grades

**Relationships**:
- Belongs To → Person
- Has Many → PersonEducationSubject

### 10. Person Education Subjects (`/person_education_subjects`)
**Entity**: `Entities\PersonEducationSubject`

**Features**:
- ✅ Subject-grade linking
- ✅ Multiple marking types
- ✅ Formatted marks display
- ✅ Subject details

**Fields**:
- `person_education_id` (required, FK to PersonEducation)
- `subject_id` (required, FK to PopularEducationSubject)
- `marks_type` (percentage, grade, gpa)
- `marks`

**Special Methods**:
- `getFormattedMarks()` - Display marks with type

**Relationships**:
- Belongs To → PersonEducation
- Belongs To → PopularEducationSubject

### 11. Person Skills (`/person_skills`)
**Entity**: `Entities\PersonSkill`

**Features**:
- ✅ Skill proficiency tracking
- ✅ List by person
- ✅ Create/Edit with skill levels
- ✅ Certification tracking
- ✅ Institution details

**Fields**:
- `person_id` (required, FK to Person)
- `subject_id` (required, FK to PopularSkill)
- `institution`
- `level` (beginner, intermediate, expert)
- `start_date`
- `complete_date`
- `marks_type`
- `marks`

**Special Methods**:
- `isCertified()` - Check if skill is certified
- `getFormattedLevel()` - Display skill level
- `getSkillName()` - Get skill name

**Relationships**:
- Belongs To → Person
- Belongs To → PopularSkill

## 🔐 Authentication Pages

### Login (`/login`)
**Features**:
- ✅ Username/password authentication
- ✅ Remember me checkbox
- ✅ Account lockout after 5 failed attempts
- ✅ Password verification with Argon2ID
- ✅ Auto-rehash if needed
- ✅ Forgot password link
- ✅ Signup link

**Process**:
1. Validate credentials
2. Check account lock status
3. Verify password
4. Reset failed attempts on success
5. Set session variables
6. Optional remember me cookie

### Sign Up (`/signup`)
**Features**:
- ✅ Person creation with credentials
- ✅ Password confirmation
- ✅ Terms acceptance checkbox
- ✅ Username uniqueness check
- ✅ Automatic login after signup
- ✅ Transaction rollback on failure

**Process**:
1. Validate all fields
2. Check username availability
3. Create Person record
4. Create Credential record
5. Auto-login user
6. Redirect to dashboard

### Logout (`/logout`)
**Features**:
- ✅ Session destruction
- ✅ Remember me cookie clearing
- ✅ Redirect to login with message

## 🎨 UI Components

### Reusable Components

#### 1. Pagination (`views/components/pagination.php`)
- Smart page range display
- First/Last page links
- Previous/Next navigation
- Current page highlighting
- Disabled state handling

#### 2. Form Errors (`views/components/form-errors.php`)
- Field-specific errors
- General error display
- Bootstrap styling
- Inline/block modes

### Layout Components

#### 3. Header (`includes/header.php`)
- Bootstrap 5.3 navbar
- Responsive hamburger menu
- User authentication state
- Dropdown menus for modules
- Alert messages (success/error)
- Auto-dismiss alerts (5s)

#### 4. Footer (`includes/footer.php`)
- Copyright information
- Platform branding
- Bootstrap JS bundle
- Custom JavaScript

## 🚀 Features Across All Pages

### Security
- ✅ CSRF protection on all forms
- ✅ SQL injection prevention (PDO)
- ✅ XSS prevention (output escaping)
- ✅ Password hashing (Argon2ID)
- ✅ Account lockout mechanism
- ✅ Optimistic locking (version field)

### User Experience
- ✅ Responsive design (mobile-first)
- ✅ Bootstrap 5.3 UI
- ✅ Bootstrap Icons
- ✅ Form validation (client + server)
- ✅ Success/error messages
- ✅ Breadcrumb navigation
- ✅ Search and filters
- ✅ Pagination (10/25/50/100)
- ✅ Sorting capabilities
- ✅ Relationship displays
- ✅ Quick actions menu

### Data Management
- ✅ Soft delete (all entities)
- ✅ Audit trail logging
- ✅ Version tracking
- ✅ Created/Updated timestamps
- ✅ User attribution
- ✅ History display

## 📱 Responsive Behavior

### Desktop (lg+)
- Full table views
- Sidebar navigation
- Multi-column forms
- Hover tooltips
- Dropdown menus

### Tablet (md)
- Responsive tables
- Collapsible navigation
- 2-column forms
- Touch-optimized buttons (44x44px)

### Mobile (< md)
- Card-based layouts
- Bottom/hamburger navigation
- Single-column forms
- Swipe gestures ready
- Pull-to-refresh ready

## 🔗 URL Structure

### Standard Entity Routes
```
GET    /{entity}              → List all
GET    /{entity}/create       → Create form
POST   /{entity}/store        → Store new record
GET    /{entity}/{id}         → View details
GET    /{entity}/{id}/edit    → Edit form
POST   /{entity}/{id}/update  → Update record
POST   /{entity}/{id}/delete  → Delete record
```

### Authentication Routes
```
GET    /login                 → Login form
POST   /login                 → Process login
GET    /signup                → Signup form
POST   /signup                → Process signup
GET    /logout                → Logout & redirect
```

## 🛠️ Helper Functions Available

All pages have access to:

```php
// Views
view('template', ['data' => $value])

// Configuration
config('app.name')

// URLs
url('/path')
asset('css/style.css')

// Authentication
auth()           // Get current user
csrf_token()     // Get CSRF token
csrf_field()     // Generate CSRF input

// Session
session('key')   // Get session value
old('field')     // Get old input
errors('field')  // Get validation errors

// Redirect
redirect('/path')
```

## 🎯 Quick Access Links

### Geography
- [Continents](/continents)
- [Countries](/countries)
- [Languages](/languages)
- [Postal Addresses](/postal_addresses)

### Person
- [Persons](/persons)
- [Credentials](/credentials)

### Education & Skills
- [Education Subjects](/popular_education_subjects)
- [Popular Skills](/popular_skills)
- [Person Education](/person_education)
- [Education Subject Grades](/person_education_subjects)
- [Person Skills](/person_skills)

### Authentication
- [Login](/login)
- [Sign Up](/signup)
- [Logout](/logout)

## 📊 Summary

**Total Pages Created**: 77+
- Geography Domain: 28 pages (4 entities × 7 pages)
- Person Domain: 14 pages (2 entities × 7 pages)
- Education & Skill Domain: 35 pages (5 entities × 7 pages)
- Authentication: 3 pages (login, signup, logout)
- Components: 2 reusable components
- Layouts: 2 layout files (header, footer)

**All pages include**:
- ✅ Full CRUD operations
- ✅ Responsive design
- ✅ Security features
- ✅ Validation
- ✅ Error handling
- ✅ User feedback
- ✅ Relationship management
- ✅ Search & filter
- ✅ Pagination

---

**The complete web interface is ready for use! 🎉**
