# V4L Quick Start Guide 🚀

Get up and running with V4L in minutes!

## ⚡ Quick Setup

### Step 1: Verify Requirements

Make sure you have:
- ✅ PHP 8.1+ installed
- ✅ SQLite extension enabled
- ✅ Web server (Apache/Nginx) or PHP built-in server

Check your PHP version:
```bash
php -v
```

### Step 2: Initialize Database

```bash
php database/init-db.php
```

You should see: `Database initialization completed successfully!`

### Step 3: Start the Server

#### Option A: PHP Built-in Server (Development)

**From project root:**
```bash
php -S localhost:8000 -t public
```

**OR from public directory:**
```bash
cd public
php -S localhost:8000
```

Then visit: http://localhost:8000

#### Option B: Apache/Nginx
Configure your web server to point to the `public/` directory.

### Step 4: Create Your Account

1. Click **"Sign Up"** in the top navigation
2. Fill in your details:
   - First Name: John
   - Last Name: Doe
   - Username: johndoe
   - Password: password123 (minimum 8 characters)
3. Click **"Create Account"**

You'll be automatically logged in and redirected to the dashboard!

## 🎯 What to Do Next

### 1. Explore the Geography Data

**Add a Continent:**
- Navigate: **Common** → **Continents**
- Click **"Add New Continent"**
- Enter: "North America"
- Save and view the details

**Add a Country:**
- Navigate: **Common** → **Countries**
- Click **"Add New Country"**
- Name: "United States"
- Continent: Select "North America"
- Save

### 2. Create Your Organization

- Go to **Dashboard**
- Click **"Create Organization"** under Quick Actions
- Fill in:
  - Short Name: "My Local Shop"
  - Tag Line: "Quality products for the community"
  - Subdomain: "mylocalshop" (must be unique)
  - Legal Category: Create one first via Common menu
  - Industry: Create one first via Common menu

### 3. Post a Job Vacancy

- Navigate: **Organization** → **Vacancies**
- Create a position first via **Common** → **Organization Positions**
- Create a vacancy with opening/closing dates
- View it on the public **Jobs** page

### 4. Add Items to Catalog

- Navigate: **Market** → **Browse Catalog**
- Click **"Add Item to Catalog"** (if you're an admin)
- Add products or services
- They'll appear on the marketplace!

## 📁 Sample Data Setup (Optional)

Want to populate with sample data? Here's a quick script:

```php
<?php
// Run this from command line: php sample-data.php
require_once 'bootstrap.php';

$db = db();

// Add continents
$continents = ['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'];
foreach ($continents as $continent) {
    $db->insert("INSERT INTO continents (name, created_at) VALUES (?, datetime('now'))", [$continent]);
}

// Add some skills
$skills = ['PHP', 'JavaScript', 'Python', 'Project Management', 'Marketing', 'Sales', 'Customer Service'];
foreach ($skills as $skill) {
    $db->insert("INSERT INTO popular_skills (name, created_at) VALUES (?, datetime('now'))", [$skill]);
}

// Add education subjects
$subjects = ['Computer Science', 'Business Administration', 'Engineering', 'Marketing', 'Finance'];
foreach ($subjects as $subject) {
    $db->insert("INSERT INTO popular_education_subjects (name, created_at) VALUES (?, datetime('now'))", [$subject]);
}

echo "Sample data added successfully!\n";
```

## 🎨 Customization Tips

### Change Theme Colors
Edit `public/assets/css/style.css`:
```css
:root {
    --primary-color: #0d6efd;  /* Change this to your brand color */
}
```

### Toggle Dark Mode
Click the moon/sun icon in the top navigation bar!

### Modify Navigation
Edit `includes/header.php` to add/remove menu items.

## 🐛 Troubleshooting

### "Database not found" error
```bash
php database/init-db.php
```

### Permission errors
```bash
chmod -R 755 database/ logs/ uploads/
```

### White screen / errors
Enable error display in `.env`:
```
APP_DEBUG=true
```

### Can't login
Make sure you:
1. Created an account via the signup page
2. Using the correct username and password
3. Database was initialized successfully

## 📚 Learn More

- **Full Documentation**: See `README.md`
- **Architecture**: See `instructions.txt`
- **Database Schema**: See `er_diagram.txt`
- **Entity Examples**: Check `entities/Continent.php` for patterns
- **CRUD Examples**: See `public/pages/entities/continents/` for complete CRUD implementation

## 🔗 Key URLs

- **Homepage**: http://localhost:8000/
- **Login**: http://localhost:8000/pages/auth/login.php
- **Sign Up**: http://localhost:8000/pages/auth/signup.php
- **Dashboard**: http://localhost:8000/pages/dashboard.php
- **Marketplace**: http://localhost:8000/pages/market/catalog.php
- **Jobs**: http://localhost:8000/pages/market/jobs.php
- **Continents**: http://localhost:8000/pages/entities/continents/list.php

## ✅ Verification Checklist

- [ ] Database initialized successfully
- [ ] Web server running
- [ ] Can access homepage
- [ ] Can sign up and create account
- [ ] Can login successfully
- [ ] Can see dashboard
- [ ] Can create a continent
- [ ] Can toggle dark/light mode

## 🎉 You're Ready!

Your V4L instance is now running. Start building your local marketplace community!

For questions or issues, refer to the main `README.md` file.

---

**Happy Building!** 🏪
