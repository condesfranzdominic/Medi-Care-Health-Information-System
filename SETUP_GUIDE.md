# Medi-Care - Setup Guide

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+ | PostgreSQL (Supabase) | Web server

### Step 1: Environment Setup
1. Copy `.env.example` to `.env`
2. Update with your Supabase credentials

### Step 2: Database Setup
1. Login to Supabase
2. Run `schema.sql` in SQL Editor

### Step 3: Create Super Admin
```php
// Generate password hash
echo password_hash('your_password', PASSWORD_DEFAULT);
```

```sql
-- Insert super admin
INSERT INTO users (user_email, user_password, user_is_superadmin) 
VALUES ('admin@clinic.com', 'hashed_password_here', TRUE);
```

### Step 4: Run Application
```bash
php -S localhost:8000
```

Access: `http://localhost:8000`

## 📋 User Roles

- **Super Admin**: Full system access
- **Staff**: Manage staff, patients, appointments, services, payments
- **Doctor**: View own appointments and medical records
- **Patient**: Book appointments, view profile

## 🔑 Key Features
- Appointment ID Format: YYYY-MM-0000001
- Role-based access control
- Secure password hashing
- Session management
- Beautiful Tailwind CSS UI

## 📁 Directory Structure
```
/config       - Configuration files
/includes     - Auth, functions, header/footer
/staff        - Staff management
/patients     - Patient management
/doctors      - Doctor management (coming soon)
/appointments - Appointment system
/services     - Services management
/users        - User management
/doctor       - Doctor portal
/patient      - Patient portal
```
