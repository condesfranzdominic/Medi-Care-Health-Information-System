<?php
return [
    '' => 'controllers/index.php',
    'login' => 'controllers/login.php',
    'logout' => 'controllers/logout.php',
    
    // Super Admin Routes
    'superadmin/dashboard' => 'controllers/superadmin.dashboard.php',
    'superadmin/users' => 'controllers/superadmin.users.php',
    'superadmin/patients' => 'controllers/superadmin.patients.php',
    'superadmin/doctors' => 'controllers/superadmin.doctors.php',
    'superadmin/staff' => 'controllers/superadmin.staff.php',
    'superadmin/services' => 'controllers/superadmin.services.php',
    'superadmin/appointments' => 'controllers/superadmin.appointments.php',
    'superadmin/specializations' => 'controllers/superadmin.specializations.php',
    'superadmin/statuses' => 'controllers/superadmin.statuses.php',
    'superadmin/payment-methods' => 'controllers/superadmin.payment-methods.php',
    'superadmin/payment-statuses' => 'controllers/superadmin.payment-statuses.php',
    'superadmin/payments' => 'controllers/superadmin.payments.php',
    'superadmin/medical-records' => 'controllers/superadmin.medical-records.php',
    'superadmin/schedules' => 'controllers/superadmin.schedules.php',
    
    // Staff Routes
    'staff/dashboard' => 'controllers/staff/dashboard.php',
    'staff/staff' => 'controllers/staff/staff.php',
    'staff/services' => 'controllers/staff/services.php',
    'staff/service-appointments' => 'controllers/staff/service-appointments.php',
    'staff/specializations' => 'controllers/staff/specializations.php',
    'staff/specialization-doctors' => 'controllers/staff/specialization-doctors.php',
    'staff/statuses' => 'controllers/staff/statuses.php',
    'staff/payment-methods' => 'controllers/staff/payment-methods.php',
    'staff/payment-statuses' => 'controllers/staff/payment-statuses.php',
    'staff/payments' => 'controllers/staff/payments.php',
    'staff/medical-records' => 'controllers/staff/medical-records.php',
    
    // Doctor Routes
    'doctor/appointments/today' => 'controllers/doctor/appointments-today.php',
    'doctor/appointments/previous' => 'controllers/doctor/appointments-previous.php',
    'doctor/appointments/future' => 'controllers/doctor/appointments-future.php',
    'doctor/profile' => 'controllers/doctor/profile.php',
    'doctor/schedules' => 'controllers/doctor/schedules.php',
    'doctor/medical-records' => 'controllers/doctor/medical-records.php',
    
    // Patient Routes
    'patient/appointments' => 'controllers/patient/appointments.php',
    'patient/appointments/create' => 'controllers/patient/create-appointment.php',
    'patient/create-appointment' => 'controllers/patient/create-appointment.php',
    'patient/profile' => 'controllers/patient/profile.php',
];