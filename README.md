# 🏙️ CityConnect — City Community Services Portal

## Quick Setup Guide

### Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10+
- XAMPP / WAMP / Laragon / any PHP local server

---

### 1. Database Setup
```sql
-- In phpMyAdmin, run the SQL file:
mysql -u root -p < database.sql
```
Or open **phpMyAdmin → Import → Select `database.sql`** and click Go.

---

### 2. Configure Database
Edit **`php/config.php`**:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');    // your MySQL username
define('DB_PASS', '');        // your MySQL password
define('DB_NAME', 'city_portal');
```

---

### 3. Place in Web Root
Copy the **`city_portal/`** folder to:
- XAMPP: `C:/xampp/htdocs/city_portal/`
- WAMP: `C:/wamp64/www/city_portal/`

Access at: `http://localhost/city_portal/`

---

### 4. Admin Panel
- URL: `http://localhost/city_portal/admin/login.php`
- Username: `admin`
- Password: `admin123`

⚠️ **Change the password** in `admin/login.php` before deployment!

---

### File Structure
```
city_portal/
├── index.php              ← Home page
├── announcements.php      ← Public announcements
├── complaint.php          ← Complaint submission form
├── services.php           ← Public services directory
├── css/
│   └── style.css          ← All styles
├── js/
│   └── main.js            ← All JavaScript
├── php/
│   ├── config.php         ← DB connection
│   ├── header.php         ← Shared header
│   └── footer.php         ← Shared footer
├── admin/
│   ├── login.php          ← Admin login
│   ├── auth.php           ← Session guard
│   ├── dashboard.php      ← Overview stats
│   ├── complaints.php     ← Manage complaints
│   ├── announcements.php  ← Manage announcements
│   └── logout.php         ← Session destroy
└── database.sql           ← DB schema + sample data
```

---

### Features Implemented
✅ Task 1 (Frontend):
- 5 interlinked HTML/PHP pages with shared nav, header, footer
- Fully responsive CSS with media queries
- JavaScript form validation (required fields, numeric phone, category selection)
- Live search filter on Public Services page
- Accessible design — clear fonts, high contrast, simple navigation

✅ Task 2 (Backend):
- MySQL database with 3 tables: complaints, announcements, services
- Complaint form saves to DB with `Pending` default status
- Announcements fetched from DB, newest first
- Services loaded dynamically from DB
- Admin panel with PHP session-based login
- Complaint status updates (Pending → In Progress → Resolved)
- Add/delete announcements
- Prepared statements on ALL queries (SQL injection prevention)
- Server-side input validation on all forms
