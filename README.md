# Medi-Care Health Information System

A comprehensive healthcare management system built with PHP.

## Project Structure

```
/
│
├── index.php                         # Main entry point
│
├── config/                           
│   ├── config.php                    # Application configuration
│   └── Database.php                  # Database connection class
│
├── classes/                          
│   ├── User.php                     # User management class
│   ├── Patient.php                  # Patient management class
│   ├── Doctor.php                   # Doctor management class
│   ├── Staff.php                    # Staff management class
│   ├── Specialization.php           # Specialization management class
│   ├── Service.php                  # Service management class
│   ├── Schedule.php                 # Schedule management class
│   ├── Appointment.php              # Appointment management class
│   ├── MedicalRecord.php            # Medical record management class
│   ├── Payment.php                  # Payment management class
│   └── Status.php                   # Status management class
│
├── includes/                         
│   ├── header.php                   # Common header template
│   ├── footer.php                   # Common footer template
│   ├── sidebar.php                  # Sidebar navigation
│   └── auth.php                     # Authentication class
│
├── public/                           
│   ├── css/
│   │   └── style.css                # Main stylesheet
│   │
│   ├── js/
│   │   ├── modal.js                 # Modal functionality
│   │   └── main.js                  # Main JavaScript file
│   │
│   └── images/
│       └── sample.jpg               # Sample images
│
├── views/                           
│   ├── patients/                
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   ├── view.php
│   │   ├── index.php
│   │   ├── register.php
│   │   ├── appointments.php
│   │   ├── book-appointment.php
│   │   └── profile.php
│   ├── doctors/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   ├── view.php
│   │   └── appointments.php
│   ├── staff/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   ├── view.php
│   │   └── index.php
│   ├── services/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   ├── view.php
│   │   └── index.php
│   ├── appointments/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   ├── view.php
│   │   ├── index.php
│   │   └── cancel.php
│   ├── schedules/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── view.php
│   ├── medical_records/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── view.php
│   ├── payments/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── view.php
│   ├── users/
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── index.php
│   ├── specializations/
│   │   └── view.php
│   └── statuses/
│       └── view.php
│
├── assets/                           
│   ├── images/
│   │   ├── logo.jpg                 # Application logo
│   │   └── favicon.jpg              # Favicon
│   │
│   ├── uploads/
│   │   └── report.pdf               # Uploaded files
│   │
│   └── font/
│       └── custom.ttf               # Custom fonts
│
├── dashboard/                        
│   ├── superadmin.php              # Super admin dashboard
│   ├── staff.php                   # Staff dashboard
│   ├── doctor.php                  # Doctor dashboard
│   └── patient.php                 # Patient dashboard
│
├── login.php                        # Login page
├── logout.php                       # Logout handler
├── unauthorized.php                 # Unauthorized access page
├── schema.sql                       # Database schema
├── SETUP_GUIDE.md                   # Setup instructions
└── README.md                        # This file
```

## Features

- **User Management**: Support for different user types (Super Admin, Staff, Doctor, Patient)
- **Patient Management**: Complete patient registration and profile management
- **Doctor Management**: Doctor profiles and specialization management
- **Appointment System**: Book, manage, and track appointments
- **Medical Records**: Secure medical record management
- **Payment Processing**: Payment tracking and management
- **Schedule Management**: Doctor and staff scheduling
- **Service Management**: Healthcare service catalog

## User Roles

1. **Super Admin**: Full system access and management
2. **Staff**: Patient and appointment management
3. **Doctor**: Patient care and medical records
4. **Patient**: Personal profile and appointment booking

## Setup Instructions

1. Clone the repository
2. Configure your database settings in `config/config.php`
3. Import the database schema from `schema.sql`
4. Set up your web server to point to the project root
5. Access the application through your web browser

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: PostgreSQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Tailwind CSS
- **Icons**: Font Awesome

## Security Features

- Password hashing
- Session management
- Role-based access control
- SQL injection prevention
- XSS protection

## Contributing

Please read the setup guide and follow the coding standards when contributing to this project.

## License

This project is proprietary software. All rights reserved.