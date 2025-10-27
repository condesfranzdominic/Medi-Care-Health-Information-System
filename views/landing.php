<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medi-Care Health Information System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Landing Page CSS -->
    <link rel="stylesheet" href="public/css/landing.css">
</head>
<body>
    <?php include 'views/partials/header.php'; ?> 

    <div class="hero-section">
        <div class="hero-content">
            <h1>YOUR HEALTH</h1>
            <div class="priority">IS OUR PRIORITY</div>
            <p>Experience seamless medical care with our modern booking system. Schedule appointments, consult with specialized doctors, and manage your health records—all in one place.</p>
            <a href="/login" class="btn-appointment">Book an Appointment</a>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="info-cards">
            <div class="info-card">
                <div class="info-card-header">
                    <span class="material-symbols-outlined info-card-icon">groups</span>
                    <h2>ABOUT US</h2>
                </div>
                <p>We are a dedicated medical facility committed to providing comprehensive healthcare services to our community. Our clinic brings together experienced doctors across multiple specializations, supported by a professional staff team that ensures every patient receives personalized attention and care.</p>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <span style="font-size: 32px; flex-shrink: 0;"></span>
                    <h2>WHAT SETS US APART?</h2>
                </div>
                <p>Our state-of-the-art booking system makes healthcare accessible and convenient. Patients can easily schedule appointments, view their medical records, and manage their healthcare journey—all through our user-friendly platform. Whether you need a routine consultation, laboratory tests, or specialized medical services, we streamline the process so you can focus on what matters most: your health.</p>
            </div>
        </div>

        <div class="services-section">
            <div class="services-header">
                <div class="services-header-left">
                    <span class="material-symbols-outlined services-header-icon">medical_services</span>
                    <h2>OUR SERVICES</h2>
                </div>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <h3>General Consultation</h3>
                    <p>Primary care for initial diagnosis, checkups, and general health inquiries.</p>
                </div>
                <div class="service-card">
                    <h3>Follow-up & Checkups</h3>
                    <p>Booking for pre-scheduled visits to review treatment or health status.</p>
                </div>
                <div class="service-card">
                    <h3>Specialized Care</h3>
                    <p>Appointments with doctors based on their specialization.</p>
                </div>
                <div class="service-card">
                    <h3>Digital Record Access</h3>
                    <p>Secure online viewing of your medical record and prescription history.</p>
                </div>
            </div>
        </div>

        <div class="process-section">
            <div class="process-header">
                <span class="material-symbols-outlined process-header-icon">calendar_clock</span>
                <h2>APPOINTMENT PROCESS</h2>
            </div>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-label">Login</div>
                </div>
                <div class="process-arrow">→</div>
                <div class="process-step">
                    <div class="step-number">2</div>
                    <div class="step-label">Book</div>
                </div>
                <div class="process-arrow">→</div>
                <div class="process-step">
                    <div class="step-number">3</div>
                    <div class="step-label">Confirm</div>
                </div>
                <div class="process-arrow">→</div>
                <div class="process-step">
                    <div class="step-number">4</div>
                    <div class="step-label">Visit</div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'views/partials/footer.php'; ?> 

    <!-- Landing Page JavaScript -->
    <script src="public/js/landing.js"></script>
</body>
</html>