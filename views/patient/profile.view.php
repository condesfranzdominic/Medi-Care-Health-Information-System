<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/patient/appointments">
                <i class="fas fa-home"></i>
                <span>Appointments</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Profile</span>
        </div>
        <h1 class="page-title">My Profile</h1>
    </div>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?= htmlspecialchars($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($patient)): ?>
    <!-- Personal Information Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Personal Information</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name: <span style="color: var(--status-error);">*</span></label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($patient['pat_first_name']) ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Last Name: <span style="color: var(--status-error);">*</span></label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($patient['pat_last_name']) ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Email: <span style="color: var(--status-error);">*</span></label>
                        <input type="email" name="email" value="<?= htmlspecialchars($patient['pat_email']) ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($patient['pat_phone'] ?? '') ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input type="date" name="date_of_birth" value="<?= htmlspecialchars($patient['pat_date_of_birth'] ?? '') ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Gender:</label>
                        <select name="gender" class="form-control">
                            <option value="">Select...</option>
                            <option value="Male" <?= ($patient['pat_gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($patient['pat_gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($patient['pat_gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group form-grid-full">
                    <label>Address:</label>
                    <textarea name="address" rows="2" class="form-control"><?= htmlspecialchars($patient['pat_address'] ?? '') ?></textarea>
                </div>
                
                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--text-primary);">Emergency Contact</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Emergency Contact Name:</label>
                        <input type="text" name="emergency_contact" value="<?= htmlspecialchars($patient['pat_emergency_contact'] ?? '') ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Emergency Contact Phone:</label>
                        <input type="text" name="emergency_phone" value="<?= htmlspecialchars($patient['pat_emergency_phone'] ?? '') ?>" class="form-control">
                    </div>
                </div>
                
                <div class="action-buttons" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        <span>Update Profile</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Additional Information (Read-only) -->
    <div class="card" style="border-left: 4px solid var(--primary-blue);">
        <div class="card-header">
            <h2 class="card-title">Additional Information</h2>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div>
                    <p style="margin: 0.5rem 0;"><strong>Patient ID:</strong> <?= htmlspecialchars($patient['pat_id']) ?></p>
                    <p style="margin: 0.5rem 0;"><strong>Medical History:</strong> <?= htmlspecialchars($patient['pat_medical_history'] ?? 'None recorded') ?></p>
                    <p style="margin: 0.5rem 0;"><strong>Allergies:</strong> <?= htmlspecialchars($patient['pat_allergies'] ?? 'None recorded') ?></p>
                </div>
                <div>
                    <p style="margin: 0.5rem 0;"><strong>Insurance Provider:</strong> <?= htmlspecialchars($patient['pat_insurance_provider'] ?? 'None') ?></p>
                    <p style="margin: 0.5rem 0;"><strong>Insurance Number:</strong> <?= htmlspecialchars($patient['pat_insurance_number'] ?? 'None') ?></p>
                </div>
            </div>
            <div class="info-box" style="margin-top: 1rem;">
                <i class="fas fa-info-circle"></i>
                <p style="margin: 0; font-size: 0.875rem;"><em>Note: Medical history, allergies, and insurance information can only be updated by medical staff.</em></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
