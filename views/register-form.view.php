<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medi-Care - Register as <?= ucfirst($role) ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<style>
    .register-page {
        display: flex;
        min-height: 100vh;
        width: 100%;
        margin: 0;
        padding: 0;
        background: var(--bg-gradient);
    }
    
    .register-left {
        flex: 0 0 50%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        position: relative;
    }
    
    .register-right {
        flex: 1;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .register-right::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(15deg);
    }
    
    .register-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 3rem;
        max-width: 500px;
        width: 100%;
        position: relative;
        z-index: 1;
    }
    
    .register-header {
        text-align: left;
        margin-bottom: 2.5rem;
    }
    
    .register-logo {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .register-logo-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    }
    
    .register-logo-text {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.02em;
    }
    
    .register-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .register-subtitle {
        color: #6b7280;
        font-size: 0.9375rem;
    }
    
    .register-form {
        margin-top: 2rem;
    }
    
    .form-icon-wrapper {
        position: relative;
    }
    
    .form-icon-wrapper input,
    .form-icon-wrapper select,
    .form-icon-wrapper textarea {
        padding-left: 3rem;
        padding-right: 1rem;
    }
    
    .form-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(35%);
        color: #6b7280;
        font-size: 1.125rem;
        pointer-events: none;
        z-index: 1;
    }
    
    .form-group textarea {
        padding-left: 1rem;
        min-height: 100px;
        resize: vertical;
    }
    
    .form-group input:focus + .form-icon,
    .form-group input:not(:placeholder-shown) + .form-icon,
    .form-group select:focus + .form-icon {
        color: var(--primary-blue);
    }
    
    .btn-register {
        width: 100%;
        padding: 0.875rem;
        background: #1f2937;
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 0.5rem;
    }
    
    .btn-register:hover {
        background: #111827;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
    }
    
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .login-link a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .login-link a:hover {
        color: var(--primary-blue-dark);
        text-decoration: underline;
    }
    
    .welcome-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 1.5rem;
        padding: 3rem;
        max-width: 500px;
        width: 100%;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .welcome-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }
    
    .welcome-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        line-height: 1.7;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
        margin-top: 1.5rem;
        transition: var(--transition);
        font-size: 0.875rem;
    }
    
    .back-link:hover {
        color: var(--primary-blue-dark);
        transform: translateX(-4px);
    }
    
    @media (max-width: 1024px) {
        .register-page {
            flex-direction: column;
        }
        
        .register-left {
            flex: 0 0 auto;
            min-height: auto;
            padding: 2rem;
        }
        
        .register-right {
            flex: 0 0 auto;
            padding: 2rem;
        }
        
        .welcome-card {
            max-width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .register-card {
            padding: 2rem;
        }
        
        .welcome-card {
            padding: 2rem;
        }
        
        .welcome-title {
            font-size: 2rem;
        }
    }
</style>

<div class="register-page">
    <!-- Left Panel - Registration Form -->
    <div class="register-left">
        <div class="register-card">
            <div class="register-header">
                <div class="register-logo">
                    <div class="register-logo-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="register-logo-text">Medi-Care</div>
                </div>
                <h1 class="register-title">Register as <?= ucfirst($role) ?></h1>
                <p class="register-subtitle">Fill in your details to create an account</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>
            
            <form method="post" action="" class="register-form">
                <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
                
                <!-- Common Fields -->
                <div class="form-group form-icon-wrapper">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                    <i class="fas fa-user form-icon"></i>
                </div>
                
                <div class="form-group form-icon-wrapper">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                    <i class="fas fa-user form-icon"></i>
                </div>
                
                <div class="form-group form-icon-wrapper email">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fas fa-envelope form-icon"></i>
                </div>
                
                <div class="form-group form-icon-wrapper">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    <i class="fas fa-phone form-icon"></i>
                </div>
                
                <div class="form-group form-icon-wrapper password">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password (min. 8 characters)" required minlength="8">
                    <i class="fas fa-lock form-icon"></i>
                </div>
                
                <div class="form-group form-icon-wrapper password">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required minlength="8">
                    <i class="fas fa-lock form-icon"></i>
                </div>
                
                <!-- Patient Specific Fields -->
                <?php if ($role === 'patient'): ?>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($_POST['date_of_birth'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select gender</option>
                            <option value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" placeholder="Enter your address"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group form-icon-wrapper">
                        <label for="emergency_contact">Emergency Contact Name</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" placeholder="Emergency contact name" value="<?= htmlspecialchars($_POST['emergency_contact'] ?? '') ?>">
                        <i class="fas fa-user form-icon"></i>
                    </div>
                    
                    <div class="form-group form-icon-wrapper">
                        <label for="emergency_phone">Emergency Contact Phone</label>
                        <input type="tel" id="emergency_phone" name="emergency_phone" placeholder="Emergency contact phone" value="<?= htmlspecialchars($_POST['emergency_phone'] ?? '') ?>">
                        <i class="fas fa-phone form-icon"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Doctor Specific Fields -->
                <?php if ($role === 'doctor'): ?>
                    <div class="form-group form-icon-wrapper">
                        <label for="license_number">License Number *</label>
                        <input type="text" id="license_number" name="license_number" placeholder="Enter your medical license number" required value="<?= htmlspecialchars($_POST['license_number'] ?? '') ?>">
                        <i class="fas fa-certificate form-icon"></i>
                    </div>
                    
                    <div class="form-group">
                        <label for="specialization_id">Specialization</label>
                        <select id="specialization_id" name="specialization_id">
                            <option value="">Select specialization</option>
                            <?php foreach ($specializations as $spec): ?>
                                <option value="<?= $spec['spec_id'] ?>" <?= (isset($_POST['specialization_id']) && $_POST['specialization_id'] == $spec['spec_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($spec['spec_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="experience_years">Years of Experience</label>
                        <input type="number" id="experience_years" name="experience_years" placeholder="Years of experience" min="0" value="<?= htmlspecialchars($_POST['experience_years'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="consultation_fee">Consultation Fee</label>
                        <input type="number" id="consultation_fee" name="consultation_fee" placeholder="Consultation fee" step="0.01" min="0" value="<?= htmlspecialchars($_POST['consultation_fee'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="qualification">Qualification</label>
                        <textarea id="qualification" name="qualification" placeholder="Enter your qualifications"><?= htmlspecialchars($_POST['qualification'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" placeholder="Enter a brief bio"><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
                    </div>
                <?php endif; ?>
                
                <!-- Staff Specific Fields -->
                <?php if ($role === 'staff'): ?>
                    <div class="form-group form-icon-wrapper">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position" placeholder="Enter your position (e.g., Receptionist, Nurse)" value="<?= htmlspecialchars($_POST['position'] ?? '') ?>">
                        <i class="fas fa-briefcase form-icon"></i>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="btn-register">Create Account</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="/login">Sign in</a>
            </div>
            
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="/register" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Role Selection</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Right Panel - Welcome Section -->
    <div class="register-right">
        <div class="welcome-card">
            <h2 class="welcome-title">Welcome to Medi-Care</h2>
            <p class="welcome-text">
                <?php if ($role === 'patient'): ?>
                    Join thousands of patients who trust Medi-Care for their healthcare needs. Book appointments with verified doctors and manage your health records all in one place.
                <?php elseif ($role === 'doctor'): ?>
                    Connect with patients and manage your practice efficiently. Join our network of medical professionals and provide quality healthcare services.
                <?php else: ?>
                    Support our clinic operations and help provide excellent patient care. Join our dedicated team of healthcare professionals.
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

</body>
</html>

