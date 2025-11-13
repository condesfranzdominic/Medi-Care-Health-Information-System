<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
    .doctor-detail-page {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }
    
    .back-link:hover {
        color: var(--primary-blue-dark);
        transform: translateX(-4px);
    }
    
    .doctor-detail-card {
        background: white;
        border-radius: 0.75rem;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .doctor-header {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .doctor-avatar-detail {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .doctor-header-info {
        flex: 1;
    }
    
    .doctor-name-detail {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .doctor-specialization-detail {
        font-size: 1.125rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    .doctor-fee-detail {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-blue);
    }
    
    .doctor-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .doctor-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--primary-blue);
    }
    
    .section-content {
        color: #4b5563;
        line-height: 1.7;
        font-size: 0.9375rem;
    }
    
    .section-content p {
        margin-bottom: 1rem;
    }
    
    .book-appointment-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        color: white;
        margin-top: 2rem;
    }
    
    .book-appointment-section h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }
    
    .book-appointment-section p {
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }
    
    .btn-book-large {
        background: white;
        color: #667eea;
        padding: 1rem 2.5rem;
        border-radius: 0.5rem;
        font-weight: 700;
        font-size: 1.125rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-book-large:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 768px) {
        .doctor-detail-page {
            padding: 1rem;
        }
        
        .doctor-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .doctor-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="doctor-detail-page">
    <a href="/patient/book" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Doctors</span>
    </a>
    
    <?php if ($error || !$doctor): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($error ?: 'Doctor not found') ?></span>
        </div>
        <a href="/patient/book" class="btn-action btn-primary" style="margin-top: 1rem; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Return to Doctors
        </a>
    <?php else: ?>
        <?php
        $initials = strtoupper(substr($doctor['doc_first_name'], 0, 1) . substr($doctor['doc_last_name'], 0, 1));
        $doctorName = 'Dr. ' . htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']);
        $specialization = htmlspecialchars($doctor['spec_name'] ?? 'General Practice');
        $fee = $doctor['doc_consultation_fee'] ?? 0;
        ?>
        
        <div class="doctor-detail-card">
            <div class="doctor-header">
                <div class="doctor-avatar-detail"><?= $initials ?></div>
                <div class="doctor-header-info">
                    <h1 class="doctor-name-detail"><?= $doctorName ?></h1>
                    <div class="doctor-specialization-detail"><?= $specialization ?></div>
                    <div class="doctor-fee-detail">₱<?= number_format($fee, 2) ?> per consultation</div>
                </div>
            </div>
            
            <div class="doctor-stats">
                <?php if ($doctor['doc_experience_years']): ?>
                <div class="stat-item">
                    <div class="stat-value"><?= $doctor['doc_experience_years'] ?></div>
                    <div class="stat-label">Years of Experience</div>
                </div>
                <?php endif; ?>
                <div class="stat-item">
                    <div class="stat-value">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    </div>
                    <div class="stat-label">Verified Doctor</div>
                </div>
                <?php if ($doctor['doc_license_number']): ?>
                <div class="stat-item">
                    <div class="stat-value">
                        <i class="fas fa-certificate" style="color: var(--primary-blue);"></i>
                    </div>
                    <div class="stat-label">Licensed</div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($doctor['doc_bio']): ?>
            <div class="doctor-section">
                <h2 class="section-title">
                    <i class="fas fa-user-md"></i>
                    About
                </h2>
                <div class="section-content">
                    <p><?= nl2br(htmlspecialchars($doctor['doc_bio'])) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($doctor['doc_qualification']): ?>
            <div class="doctor-section">
                <h2 class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    Qualifications
                </h2>
                <div class="section-content">
                    <p><?= nl2br(htmlspecialchars($doctor['doc_qualification'])) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($specialization && isset($doctor['spec_description']) && $doctor['spec_description']): ?>
            <div class="doctor-section">
                <h2 class="section-title">
                    <i class="fas fa-stethoscope"></i>
                    Specialization
                </h2>
                <div class="section-content">
                    <p><strong><?= $specialization ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($doctor['spec_description'])) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="book-appointment-section">
            <h3>Ready to Book an Appointment?</h3>
            <p>Schedule your consultation with <?= $doctorName ?> today</p>
            <a href="/patient/appointments/create?doctor_id=<?= $doctor['doc_id'] ?>" class="btn-book-large">
                <i class="fas fa-calendar-check"></i>
                Book Appointment
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

