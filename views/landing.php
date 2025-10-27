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
    <?php include 'views/partials/landing_header.php'; ?>

    <div class="hero-section">
        <div class="hero-content doctify-style-content">
            <div class="hero-text-panel">
                <div class="tagline">24/7 Services Available</div>
                <h1>Your Health, Our Technology, Trusted Doctors at Your Fingertips.</h1>
                <p>Whether in person or online, Medi-Care connects you with certified, experienced healthcare professionals—quickly, safely, and effortlessly.</p>
                <div class="hero-actions">
                    <a href="/login" class="btn-primary">Book Appointment</a>
                    <a href="#" class="btn-secondary">How It Works</a>
                </div>
            </div>
            <div class="hero-doctor-image-placeholder"></div>
        </div>
        <div class="hero-sidebar-panel">
            <div class="sidebar-item">
                <h2><span class="material-symbols-outlined">verified_user</span> Certified Medical Experts</h2>
                <p>Only licensed and well-vetted professionals.</p>
            </div>
            <div class="sidebar-item">
                <h2><span class="material-symbols-outlined">schedule</span> Same-Day Appointments</h2>
                <p>No long waits. Book and consult instantly.</p>
            </div>
            <div class="sidebar-item">
                <h2><span class="material-symbols-outlined">lock</span> Secure Digital Health Records</h2>
                <p>Access your patient data anytime and anywhere.</p>
            </div>
            <div class="sidebar-item cta-box">
                <span class="material-symbols-outlined">person</span>
                <h2>In-Person & Online</h2>
                <p>Choose what works for you—physical clinics or virtual appointments.</p>
                <a href="#" class="btn-cta">Explore Our Options</a>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="ease-of-care-section">
            <h2>Getting Care Has Never Been Easier</h2>
            <p>Our streamlined process makes healthcare accessible in just three simple steps.</p>
            <div class="ease-of-care-steps">
                <div class="care-step">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <h3>Book Instantly</h3>
                    <p>Schedule your appointment online or via our app.</p>
                </div>
                <div class="care-step">
                    <span class="material-symbols-outlined">chat</span>
                    <h3>Consult & Follow Up</h3>
                    <p>Get treatment, advice, and prescriptions—all in one place.</p>
                </div>
            </div>
            <a href="/login" class="btn-link">Read More &rarr;</a>
        </div>

        <div class="services-section">
            </div>
        </div>

    <?php include 'views/partials/landing_footer.php'; ?>

    <!-- Landing Page JavaScript -->
    <script src="public/js/landing.js"></script>
</body>
</html>