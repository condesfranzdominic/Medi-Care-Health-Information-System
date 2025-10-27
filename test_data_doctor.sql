-- Test Data for Doctor Module
-- Run this in Supabase SQL Editor after running schema.sql

-- 1. Create a test doctor (if not already exists)
INSERT INTO doctors (doc_first_name, doc_last_name, doc_email, doc_phone, doc_specialization_id, doc_license_number, doc_experience_years, doc_consultation_fee, doc_qualification, doc_bio, doc_status)
VALUES 
('John', 'Smith', 'dr.smith@medicare.com', '09201234567', 1, 'LIC-12345', 10, 1500.00, 'MD, Board Certified Internal Medicine', 'Experienced physician specializing in internal medicine with 10 years of practice.', 'active')
ON CONFLICT (doc_email) DO NOTHING;

-- Get the doctor ID (adjust this based on your actual doc_id)
-- For this example, we'll assume doc_id = 1

-- 2. Create user account for the doctor
INSERT INTO users (user_email, user_password, user_is_superadmin, doc_id)
VALUES ('doctor@medicare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', false, 1)
ON CONFLICT (user_email) DO NOTHING;
-- Password: admin123

-- 3. Create test appointments for this doctor
-- Today's appointments
INSERT INTO appointments (appointment_id, pat_id, doc_id, service_id, status_id, appointment_date, appointment_time, appointment_duration, appointment_notes)
VALUES 
('2025-10-0000001', 1, 1, 1, 1, CURRENT_DATE, '09:00:00', 30, 'Regular checkup - patient reports mild headaches'),
('2025-10-0000002', 2, 1, 2, 1, CURRENT_DATE, '10:30:00', 45, 'Follow-up visit for blood pressure monitoring'),
('2025-10-0000003', 3, 1, 1, 1, CURRENT_DATE, '14:00:00', 30, 'New patient consultation')
ON CONFLICT (appointment_id) DO NOTHING;

-- Future appointments
INSERT INTO appointments (appointment_id, pat_id, doc_id, service_id, status_id, appointment_date, appointment_time, appointment_duration, appointment_notes)
VALUES 
('2025-10-0000010', 1, 1, 1, 1, CURRENT_DATE + INTERVAL '1 day', '09:00:00', 30, 'Follow-up checkup'),
('2025-10-0000011', 2, 1, 3, 1, CURRENT_DATE + INTERVAL '2 days', '11:00:00', 60, 'ECG test scheduled'),
('2025-10-0000012', 3, 1, 2, 1, CURRENT_DATE + INTERVAL '3 days', '15:00:00', 45, 'Lab results review')
ON CONFLICT (appointment_id) DO NOTHING;

-- Previous appointments
INSERT INTO appointments (appointment_id, pat_id, doc_id, service_id, status_id, appointment_date, appointment_time, appointment_duration, appointment_notes)
VALUES 
('2025-10-0000020', 1, 1, 1, 2, CURRENT_DATE - INTERVAL '1 day', '10:00:00', 30, 'Completed - prescribed medication'),
('2025-10-0000021', 2, 1, 2, 2, CURRENT_DATE - INTERVAL '3 days', '14:00:00', 45, 'Completed - blood test done'),
('2025-10-0000022', 3, 1, 1, 2, CURRENT_DATE - INTERVAL '7 days', '11:00:00', 30, 'Completed - general consultation')
ON CONFLICT (appointment_id) DO NOTHING;

-- 4. Create test schedules
INSERT INTO schedules (doc_id, schedule_date, start_time, end_time, max_appointments, is_available)
VALUES 
(1, CURRENT_DATE, '08:00:00', '17:00:00', 10, true),
(1, CURRENT_DATE + INTERVAL '1 day', '08:00:00', '17:00:00', 10, true),
(1, CURRENT_DATE + INTERVAL '2 days', '08:00:00', '17:00:00', 10, true),
(1, CURRENT_DATE + INTERVAL '3 days', '09:00:00', '16:00:00', 8, true),
(1, CURRENT_DATE + INTERVAL '7 days', '08:00:00', '12:00:00', 5, true)
ON CONFLICT (doc_id, schedule_date, start_time) DO NOTHING;

-- 5. Create test medical records
INSERT INTO medical_records (pat_id, doc_id, appointment_id, record_date, diagnosis, treatment, prescription, notes, follow_up_date)
VALUES 
(1, 1, '2025-10-0000020', CURRENT_DATE - INTERVAL '1 day', 
 'Tension headache, mild hypertension', 
 'Rest, stress management, blood pressure monitoring', 
 'Paracetamol 500mg - 1 tablet every 6 hours as needed\nAmlodipine 5mg - 1 tablet daily', 
 'Patient advised to reduce caffeine intake and maintain regular sleep schedule',
 CURRENT_DATE + INTERVAL '14 days'),

(2, 1, '2025-10-0000021', CURRENT_DATE - INTERVAL '3 days',
 'Type 2 Diabetes Mellitus, controlled',
 'Continue current medication, dietary modifications',
 'Metformin 500mg - 1 tablet twice daily with meals\nGlimepiride 2mg - 1 tablet before breakfast',
 'HbA1c: 6.8% - Good control. Continue current regimen.',
 CURRENT_DATE + INTERVAL '30 days'),

(3, 1, '2025-10-0000022', CURRENT_DATE - INTERVAL '7 days',
 'Upper respiratory tract infection',
 'Symptomatic treatment, rest, hydration',
 'Amoxicillin 500mg - 1 capsule three times daily for 7 days\nCetirizine 10mg - 1 tablet at bedtime',
 'Patient recovering well. Advised to complete full course of antibiotics.',
 NULL)
ON CONFLICT DO NOTHING;

-- Verification queries
SELECT 'Doctor created:' as info, doc_id, doc_first_name, doc_last_name, doc_email FROM doctors WHERE doc_id = 1;
SELECT 'User created:' as info, user_id, user_email, doc_id FROM users WHERE doc_id = 1;
SELECT 'Appointments created:' as info, COUNT(*) as count FROM appointments WHERE doc_id = 1;
SELECT 'Schedules created:' as info, COUNT(*) as count FROM schedules WHERE doc_id = 1;
SELECT 'Medical records created:' as info, COUNT(*) as count FROM medical_records WHERE doc_id = 1;

-- Show today's appointments for verification
SELECT 'Today''s appointments:' as info, appointment_id, appointment_time, 
       (SELECT pat_first_name || ' ' || pat_last_name FROM patients WHERE pat_id = a.pat_id) as patient_name
FROM appointments a
WHERE doc_id = 1 AND appointment_date = CURRENT_DATE
ORDER BY appointment_time;
