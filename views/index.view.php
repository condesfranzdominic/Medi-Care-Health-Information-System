<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medi-Care Health Information System - Your Health, Our Priority</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .hero p {
            font-size: 1.3em;
            margin-bottom: 30px;
            opacity: 0.95;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 15px 40px;
            font-size: 1.1em;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            font-weight: 600;
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
            font-weight: 600;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        /* Features Section */
        .features {
            padding: 80px 20px;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 50px;
            color: #333;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 3em;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #667eea;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.8;
        }
        
        /* Services Section */
        .services {
            padding: 80px 20px;
            background: white;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .service-item {
            padding: 30px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 10px;
            text-align: center;
        }
        
        .service-item h4 {
            font-size: 1.3em;
            margin-bottom: 10px;
        }
        
        /* About Section */
        .about {
            padding: 80px 20px;
            background: #f8f9fa;
        }
        
        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .about-content p {
            font-size: 1.2em;
            line-height: 1.8;
            color: #555;
            margin-bottom: 20px;
        }
        
        /* Contact Section */
        .contact {
            padding: 80px 20px;
            background: white;
            text-align: center;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 900px;
            margin: 40px auto;
        }
        
        .contact-item {
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .contact-item h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer p {
            margin: 10px 0;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2em;
            }
            
            .hero p {
                font-size: 1.1em;
            }
            
            .section-title {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>🏥 Medi-Care Health Information System</h1>
            <p>Your Health, Our Priority - Comprehensive Healthcare Management</p>
            <div class="cta-buttons">
                <a href="/login" class="btn btn-primary">Login to Your Account</a>
                <a href="#about" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose Medi-Care?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Easy Appointment Booking</h3>
                    <p>Book appointments with your preferred doctors online, anytime. Get instant confirmation with your appointment ID.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👨‍⚕️</div>
                    <h3>Expert Medical Team</h3>
                    <p>Access to qualified doctors across multiple specializations. Browse doctors by specialty and choose the best fit for you.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📋</div>
                    <h3>Digital Medical Records</h3>
                    <p>Secure, centralized medical records accessible to you and your healthcare providers. Your health history at your fingertips.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💳</div>
                    <h3>Flexible Payment Options</h3>
                    <p>Multiple payment methods supported including cash, cards, bank transfer, and mobile payments for your convenience.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure & Private</h3>
                    <p>Your data is protected with industry-standard security. Role-based access ensures your information stays confidential.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>User-Friendly Interface</h3>
                    <p>Intuitive design makes it easy for patients, doctors, and staff to navigate and use the system efficiently.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-item">
                    <h4>General Consultation</h4>
                    <p>Comprehensive health checkups</p>
                </div>
                <div class="service-item">
                    <h4>Specialist Care</h4>
                    <p>Expert treatment across specialties</p>
                </div>
                <div class="service-item">
                    <h4>Laboratory Tests</h4>
                    <p>Advanced diagnostic services</p>
                </div>
                <div class="service-item">
                    <h4>Follow-up Care</h4>
                    <p>Continuous health monitoring</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <h2 class="section-title">About Medi-Care</h2>
            <div class="about-content">
                <p>
                    Medi-Care Health Information System is a comprehensive healthcare management platform designed to streamline 
                    medical services for patients, doctors, and healthcare staff.
                </p>
                <p>
                    Our mission is to make healthcare accessible, efficient, and patient-centered. With advanced features for 
                    appointment scheduling, medical records management, and secure payment processing, we're revolutionizing 
                    how healthcare is delivered.
                </p>
                <p>
                    Whether you're a patient seeking quality care, a doctor managing your practice, or healthcare staff 
                    coordinating services, Medi-Care provides the tools you need for success.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="contact">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <h4>📍 Location</h4>
                    <p>123 Healthcare Avenue<br>Medical District<br>City, State 12345</p>
                </div>
                <div class="contact-item">
                    <h4>📞 Phone</h4>
                    <p>Main: (123) 456-7890<br>Emergency: (123) 456-7899</p>
                </div>
                <div class="contact-item">
                    <h4>📧 Email</h4>
                    <p>info@medicare.com<br>support@medicare.com</p>
                </div>
            </div>
            <div style="margin-top: 40px;">
                <a href="/login" class="btn btn-primary">Access Your Account</a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Medi-Care Health Information System. All rights reserved.</p>
            <p>Your Health, Our Priority</p>
        </div>
    </footer>
</body>
</html>
