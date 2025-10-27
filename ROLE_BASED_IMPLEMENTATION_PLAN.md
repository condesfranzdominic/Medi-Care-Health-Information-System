# 🔒 Role-Based Access Control Implementation Plan

## Overview
Expanding the Medi-Care system from superadmin-only to full role-based access control with 4 roles:
- **Super Admin** - Full control
- **Staff** - Patient, schedule, service management
- **Doctor** - Own schedules, medical records, appointments
- **Patient** - Create appointments, view own schedules

---

## ✅ Completed

### 1. Enhanced Auth Class
- ✅ Added `requireStaff()`, `requireDoctor()`, `requirePatient()`
- ✅ Added `requireRole($roles)` for multi-role access
- ✅ Added `canAccess($allowedRoles)` helper

### 2. Role-Based Login Redirects
- ✅ Super Admin → `/superadmin/dashboard`
- ✅ Staff → `/staff/dashboard`
- ✅ Doctor → `/doctor/appointments/today`
- ✅ Patient → `/patient/appointments`

### 3. Appointment ID Format
- ✅ Changed from `APT-202510-0001` to `2025-10-0000001` (7-digit sequence)

---

## 🚧 Implementation Roadmap

### Phase 1: Staff Module (Priority: High)

#### Staff Dashboard
**Route**: `/staff/dashboard`
**Access**: Staff, Super Admin
**Features**:
- Statistics overview
- Quick actions to allowed modules

#### Staff-Accessible Modules
1. **Staff Management** (already exists as superadmin.staff.php)
   - Add new staff ✅ (Super Admin, Staff)
   - View/Search staff ✅ (Super Admin, Staff)
   - Update staff ✅ (Super Admin, Staff)
   - Delete staff (Super Admin only) - needs access control

2. **Patient Module** (modify superadmin.patients.php)
   - View patients (Super Admin only currently)
   - Need to keep restricted to Super Admin

3. **Specialization Module**
   - View all specializations (Super Admin, Staff)
   - View doctors by specialization (Super Admin, Staff)
   - Add/Update/Delete (Super Admin only)

4. **Service Module** (already exists)
   - Add service (Super Admin, Staff)
   - View services ✅ (Super Admin, Staff)
   - Update service (Super Admin, Staff)
   - Delete service (Super Admin only)

5. **Status Module**
   - Add/View/Update status (Super Admin, Staff)
   - Delete status (Super Admin only)

6. **Payment Method Module**
   - Add/Edit/View payment methods (Super Admin, Staff)
   - Delete (Super Admin only)

7. **Payment Status Module**
   - Add/Edit/View payment statuses (Super Admin, Staff)
   - Delete (Super Admin only)

8. **Payment Module**
   - Add/Update/View payments (Super Admin, Staff)
   - Delete (Super Admin only)

---

### Phase 2: Doctor Module (Priority: High)

#### Doctor Dashboard
**Route**: `/doctor/appointments/today`
**Access**: Doctor, Super Admin
**Features**:
- Today's appointments
- Quick links to schedules and medical records

#### Doctor-Accessible Modules
1. **Doctor Profile**
   - Add new doctor (Super Admin, Doctor)
   - Update own profile (Super Admin, Doctor)
   - Delete (Super Admin only)

2. **Appointments View**
   - View previous appointments (filtered by logged-in doctor)
   - View today's appointments (filtered by logged-in doctor)
   - View future appointments (filtered by logged-in doctor)
   - **RULE**: Doctors cannot see other doctors' appointments

3. **Schedule Module**
   - Add new schedule (Super Admin, Doctor)
   - View all own schedules (Super Admin, Doctor)
   - View today's schedules (Super Admin, Doctor)
   - Update schedules (Super Admin, Doctor)
   - Delete schedules (Super Admin, Doctor)

4. **Medical Records**
   - Create medical record (Super Admin, Doctor)
   - View medical records (Super Admin, Staff, Doctor)
   - Update medical record (Super Admin, Doctor)
   - Delete (Super Admin only)

---

### Phase 3: Patient Module (Priority: Medium)

#### Patient Dashboard
**Route**: `/patient/appointments`
**Access**: Patient only
**Features**:
- View own appointment schedule
- Create new appointments
- View appointment history

#### Patient-Accessible Modules
1. **Appointments**
   - Create appointment (must have registered account)
   - View own appointments
   - Cancel own appointments (if allowed)

2. **Profile**
   - View own profile
   - Update own information

---

### Phase 4: New Modules to Create

#### 1. Specialization Module
**Files to create**:
- `controllers/staff.specializations.php` (view only)
- `controllers/superadmin.specializations.php` (full CRUD)
- `views/staff.specializations.view.php`
- `views/superadmin.specializations.view.php`

**Features**:
- List all specializations
- View doctors by specialization
- Add/Update/Delete (Super Admin only)

#### 2. Schedule Module
**Files to create**:
- `controllers/doctor.schedules.php`
- `controllers/superadmin.schedules.php`
- `views/doctor.schedules.view.php`
- `views/superadmin.schedules.view.php`

**Features**:
- Add schedule slots
- View schedules (filtered by doctor if doctor role)
- Update/Delete schedules

#### 3. Medical Records Module
**Files to create**:
- `controllers/doctor.medical-records.php`
- `controllers/staff.medical-records.php` (view only)
- `controllers/superadmin.medical-records.php`
- `views/doctor.medical-records.view.php`
- `views/staff.medical-records.view.php`
- `views/superadmin.medical-records.view.php`

**Features**:
- Create medical record (Doctor, Super Admin)
- View records (Staff, Doctor, Super Admin)
- Update (Doctor, Super Admin)
- Delete (Super Admin only)

#### 4. Payment Method Module
**Files to create**:
- `controllers/staff.payment-methods.php`
- `controllers/superadmin.payment-methods.php`
- `views/staff.payment-methods.view.php`
- `views/superadmin.payment-methods.view.php`

**Features**:
- Add/Edit/View payment methods (Staff, Super Admin)
- Delete (Super Admin only)

#### 5. Payment Status Module
**Files to create**:
- `controllers/staff.payment-statuses.php`
- `controllers/superadmin.payment-statuses.php`
- `views/staff.payment-statuses.view.php`
- `views/superadmin.payment-statuses.view.php`

**Features**:
- Add/Edit/View payment statuses (Staff, Super Admin)
- Delete (Super Admin only)

#### 6. Payment Module
**Files to create**:
- `controllers/staff.payments.php`
- `controllers/superadmin.payments.php`
- `views/staff.payments.view.php`
- `views/superadmin.payments.view.php`

**Features**:
- Add/Update/View payments (Staff, Super Admin)
- Delete (Super Admin only)

---

## 📁 File Structure

```
controllers/
├── superadmin/
│   ├── dashboard.php
│   ├── users.php
│   ├── patients.php
│   ├── doctors.php
│   ├── staff.php
│   ├── services.php
│   ├── appointments.php
│   ├── specializations.php (NEW)
│   ├── schedules.php (NEW)
│   ├── medical-records.php (NEW)
│   ├── payment-methods.php (NEW)
│   ├── payment-statuses.php (NEW)
│   └── payments.php (NEW)
├── staff/
│   ├── dashboard.php (NEW)
│   ├── staff.php (NEW - view/edit)
│   ├── specializations.php (NEW - view only)
│   ├── services.php (NEW)
│   ├── medical-records.php (NEW - view only)
│   ├── payment-methods.php (NEW)
│   ├── payment-statuses.php (NEW)
│   └── payments.php (NEW)
├── doctor/
│   ├── appointments-today.php (NEW)
│   ├── appointments-previous.php (NEW)
│   ├── appointments-future.php (NEW)
│   ├── profile.php (NEW)
│   ├── schedules.php (NEW)
│   └── medical-records.php (NEW)
└── patient/
    ├── appointments.php (NEW)
    ├── create-appointment.php (NEW)
    └── profile.php (NEW)
```

---

## 🔐 Access Control Matrix

| Module | Super Admin | Staff | Doctor | Patient |
|--------|-------------|-------|--------|---------|
| **Staff** | Full CRUD | View, Add, Update | - | - |
| **Patients** | Full CRUD | - | - | Own profile |
| **Doctors** | Full CRUD | - | Own profile | - |
| **Specializations** | Full CRUD | View | - | - |
| **Services** | Full CRUD | Add, View, Update | - | - |
| **Schedules** | Full CRUD | - | Own schedules | - |
| **Appointments** | Full CRUD | - | View own | View/Create own |
| **Medical Records** | Full CRUD | View | Create, Update, View | - |
| **Payment Methods** | Full CRUD | Add, Edit, View | - | - |
| **Payment Statuses** | Full CRUD | Add, Edit, View | - | - |
| **Payments** | Full CRUD | Add, Update, View | - | - |
| **Users** | Full CRUD | Create, View | Create | - |

---

## 🎯 Next Steps

1. **Create Staff Dashboard** - Show statistics and quick links
2. **Modify existing controllers** - Add role-based access checks
3. **Create Doctor Dashboard** - Today's appointments view
4. **Create new modules** - Schedules, Medical Records, Payments
5. **Create Patient Dashboard** - Appointment booking interface
6. **Update routes.php** - Add all new routes
7. **Test access control** - Verify each role can only access allowed modules

---

## 🚨 Important Rules

1. **User Creation Workflow**:
   - After creating Doctor/Staff/Patient → redirect to user creation
   - Link the user account to the entity via foreign key

2. **Appointment Rules**:
   - ID format: `YYYY-MM-0000001`
   - Display appointment ID after creation
   - Patient must have account before booking

3. **Doctor Restrictions**:
   - Can ONLY see own appointments
   - Filter all queries by `doc_id = $_SESSION['doc_id']`

4. **Super Admin**:
   - Can access ALL modules
   - Only role that can delete most entities

---

## 📝 Database Schema Notes

Your existing schema already supports this:
- ✅ `users` table with `user_is_superadmin`, `pat_id`, `staff_id`, `doc_id`
- ✅ `patients`, `doctors`, `staff` tables
- ✅ `appointments` with proper foreign keys
- ✅ `specializations` table
- ✅ `appointment_statuses` table
- ✅ `services` table
- ✅ `schedules` table
- ✅ `medical_records` table
- ✅ `payment_methods` table
- ✅ `payment_statuses` table
- ✅ `payments` table

Everything is already in place in your schema!

---

This is a comprehensive expansion. Would you like me to start implementing specific modules?
