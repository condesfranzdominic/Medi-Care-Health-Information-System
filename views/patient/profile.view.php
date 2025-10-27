<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <h1>My Profile</h1>
    <p><a href="/patient/appointments" class="btn">← Back to Appointments</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="background: #efe; color: #3c3; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($patient)): ?>
    <div style="background: #fff; padding: 30px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Personal Information</h2>
        <form method="POST">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>First Name: *</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($patient['pat_first_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Last Name: *</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($patient['pat_last_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email: *</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($patient['pat_email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($patient['pat_phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Date of Birth:</label>
                    <input type="date" name="date_of_birth" value="<?= htmlspecialchars($patient['pat_date_of_birth'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender">
                        <option value="">Select...</option>
                        <option value="Male" <?= ($patient['pat_gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($patient['pat_gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= ($patient['pat_gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" rows="2"><?= htmlspecialchars($patient['pat_address'] ?? '') ?></textarea>
            </div>
            
            <h3 style="margin-top: 30px; margin-bottom: 15px;">Emergency Contact</h3>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Emergency Contact Name:</label>
                    <input type="text" name="emergency_contact" value="<?= htmlspecialchars($patient['pat_emergency_contact'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Emergency Contact Phone:</label>
                    <input type="text" name="emergency_phone" value="<?= htmlspecialchars($patient['pat_emergency_phone'] ?? '') ?>">
                </div>
            </div>
            
            <button type="submit" class="btn btn-success" style="margin-top: 20px;">Update Profile</button>
        </form>
    </div>
    
    <!-- Profile Summary (Read-only info) -->
    <div style="background: #f8f9fa; padding: 25px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
        <h3>Additional Information</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div>
                <p style="margin: 5px 0; color: #555;"><strong>Patient ID:</strong> <?= htmlspecialchars($patient['pat_id']) ?></p>
                <p style="margin: 5px 0; color: #555;"><strong>Medical History:</strong> <?= htmlspecialchars($patient['pat_medical_history'] ?? 'None recorded') ?></p>
                <p style="margin: 5px 0; color: #555;"><strong>Allergies:</strong> <?= htmlspecialchars($patient['pat_allergies'] ?? 'None recorded') ?></p>
            </div>
            <div>
                <p style="margin: 5px 0; color: #555;"><strong>Insurance Provider:</strong> <?= htmlspecialchars($patient['pat_insurance_provider'] ?? 'None') ?></p>
                <p style="margin: 5px 0; color: #555;"><strong>Insurance Number:</strong> <?= htmlspecialchars($patient['pat_insurance_number'] ?? 'None') ?></p>
            </div>
        </div>
        <p style="margin: 15px 0 0 0; font-size: 13px; color: #666;">
            <em>Note: Medical history, allergies, and insurance information can only be updated by medical staff.</em>
        </p>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
