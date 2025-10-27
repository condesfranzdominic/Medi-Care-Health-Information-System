# Super Admin Setup Guide

## 🎯 Overview
This guide will help you set up the Super Admin functionality for the Medi-Care Health Information System with full CRUD operations.

## 📋 Prerequisites
- Supabase account with PostgreSQL database
- `.env` file configured with Supabase credentials
- PHP 8.0+ with PDO PostgreSQL extension

## 🗄️ Database Setup

### Step 1: Run the Database Schema
1. Open your Supabase SQL Editor
2. Copy the contents of `database_schema.sql`
3. Execute the SQL script to create all tables and sample data

This will create:
- **users** table (for authentication)
- **patients** table
- **doctors** table
- **staff** table
- **services** table
- **appointments** table

### Step 2: Default Super Admin Account
The schema includes a default super admin account:
- **Email**: `admin@medicare.com`
- **Password**: `admin123`

⚠️ **Important**: Change this password immediately after first login!

## 🚀 Features Implemented

### Authentication System
- ✅ Login/Logout functionality
- ✅ Role-based access control (superadmin, staff, doctor, patient)
- ✅ Session management
- ✅ Password hashing with bcrypt

### Super Admin Dashboard
- ✅ Overview statistics
- ✅ Quick action links
- ✅ Recent appointments view

### CRUD Operations

#### 1. Users Management (`/superadmin/users`)
- Create new users with roles
- Update user information
- Delete users
- Change passwords

#### 2. Patients Management (`/superadmin/patients`)
- Add new patients
- Edit patient details (name, email, phone, DOB, gender, address)
- Delete patients
- View all patients in table format

#### 3. Doctors Management (`/superadmin/doctors`)
- Add new doctors
- Edit doctor information (name, email, phone, specialization, license)
- Delete doctors
- View all doctors

#### 4. Staff Management (`/superadmin/staff`)
- Add staff members
- Edit staff details (name, email, phone, position)
- Delete staff
- View all staff

#### 5. Services Management (`/superadmin/services`)
- Create new services
- Edit service details (name, description, price)
- Delete services
- View all services with pricing

#### 6. Appointments Management (`/superadmin/appointments`)
- Create appointments (patient + doctor + date/time)
- Update appointment status (pending, confirmed, completed, cancelled)
- Delete appointments
- View all appointments with patient and doctor names

## 📁 File Structure

```
controllers/
├── superadmin.dashboard.php      # Dashboard logic
├── superadmin.users.php          # Users CRUD logic
├── superadmin.patients.php       # Patients CRUD logic
├── superadmin.doctors.php        # Doctors CRUD logic
├── superadmin.staff.php          # Staff CRUD logic
├── superadmin.services.php       # Services CRUD logic
├── superadmin.appointments.php   # Appointments CRUD logic
└── login.php                     # Authentication logic

views/
├── superadmin.dashboard.view.php      # Dashboard UI
├── superadmin.users.view.php          # Users UI
├── superadmin.patients.view.php       # Patients UI
├── superadmin.doctors.view.php        # Doctors UI
├── superadmin.staff.view.php          # Staff UI
├── superadmin.services.view.php       # Services UI
├── superadmin.appointments.view.php   # Appointments UI
└── login.view.php                     # Login form UI

classes/
└── Auth.php                      # Authentication class

includes/
├── routes.php                    # All route definitions
├── router.php                    # Routing logic
└── functions.php                 # Helper functions
```

## 🔐 Security Features

- ✅ Password hashing with `password_hash()`
- ✅ SQL injection protection with prepared statements
- ✅ XSS protection with `htmlspecialchars()`
- ✅ Input sanitization
- ✅ Email validation
- ✅ Role-based access control

## 🧪 Testing the System

### 1. Start the Server
```bash
php -S localhost:8000
```

### 2. Login
Navigate to: `http://localhost:8000/login`
- Email: `admin@medicare.com`
- Password: `admin123`

### 3. Test CRUD Operations

**Users:**
- Go to `/superadmin/users`
- Create a new user
- Edit the user
- Delete the user

**Patients:**
- Go to `/superadmin/patients`
- Add a patient
- Edit patient details
- Delete the patient

**Doctors:**
- Go to `/superadmin/doctors`
- Add a doctor
- Edit doctor information
- Delete the doctor

**Staff:**
- Go to `/superadmin/staff`
- Add staff member
- Edit staff details
- Delete staff

**Services:**
- Go to `/superadmin/services`
- Create a service
- Edit service details
- Delete the service

**Appointments:**
- Go to `/superadmin/appointments`
- Create an appointment
- Update appointment status
- Delete the appointment

## 🎨 UI Features

- Simple, clean interface
- Inline editing with modals
- Confirmation dialogs for deletions
- Success/error message alerts
- Responsive tables
- Form validation

## 🔄 Routes Available

```
/                              → Home page
/login                         → Login page
/logout                        → Logout (destroys session)
/superadmin/dashboard          → Super Admin dashboard
/superadmin/users              → Users management
/superadmin/patients           → Patients management
/superadmin/doctors            → Doctors management
/superadmin/staff              → Staff management
/superadmin/services           → Services management
/superadmin/appointments       → Appointments management
```

## 💡 Next Steps

1. **Change Default Password**: Login and create a new super admin account
2. **Add Real Data**: Replace sample data with actual information
3. **Customize UI**: Enhance the frontend styling as needed
4. **Add Validation**: Implement additional business logic validation
5. **Add Reports**: Create reporting features for analytics
6. **Add Search**: Implement search and filter functionality
7. **Add Pagination**: For large datasets

## 🐛 Troubleshooting

**Database Connection Issues:**
- Check `.env` file has correct Supabase credentials
- Verify PostgreSQL extension is installed: `php -m | grep pdo_pgsql`

**Login Not Working:**
- Ensure `users` table exists in database
- Verify password hash is correct
- Check session is started properly

**CRUD Operations Failing:**
- Check database tables exist
- Verify foreign key relationships
- Review error logs for SQL errors

## 📚 Additional Notes

- All CRUD operations use PostgreSQL prepared statements
- Frontend is kept simple for functionality testing
- Controllers contain all business logic
- Views contain only presentation logic
- Follows MVC pattern strictly
