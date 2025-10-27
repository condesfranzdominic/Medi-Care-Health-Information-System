<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1>My Profile</h1>
    <p><a href="/doctor/appointments/today" class="btn">← Back to Dashboard</a></p>
    
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
    
    <?php if (!empty($doctor)): ?>
    <div style="background: #fff; padding: 30px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Update Profile Information</h2>
        <form method="POST">
            <div class="form-group">
                <label>First Name: *</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($doctor['doc_first_name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Last Name: *</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($doctor['doc_last_name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email: *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($doctor['doc_email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($doctor['doc_phone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Specialization:</label>
                <select name="specialization_id">
                    <option value="">Select Specialization</option>
                    <?php foreach ($specializations as $spec): ?>
                        <option value="<?= $spec['spec_id'] ?>" <?= $doctor['doc_specialization_id'] == $spec['spec_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($spec['spec_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>License Number:</label>
                <input type="text" name="license_number" value="<?= htmlspecialchars($doctor['doc_license_number'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Experience (Years):</label>
                <input type="number" name="experience_years" min="0" value="<?= htmlspecialchars($doctor['doc_experience_years'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Consultation Fee:</label>
                <input type="number" name="consultation_fee" step="0.01" min="0" value="<?= htmlspecialchars($doctor['doc_consultation_fee'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Qualification:</label>
                <textarea name="qualification" rows="3"><?= htmlspecialchars($doctor['doc_qualification'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Bio:</label>
                <textarea name="bio" rows="5"><?= htmlspecialchars($doctor['doc_bio'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-success">Update Profile</button>
        </form>
    </div>
    
    <!-- Profile Summary -->
    <div style="background: #f8f9fa; padding: 25px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea;">
        <h3>Current Profile Summary</h3>
        <p><strong>Name:</strong> Dr. <?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></p>
        <p><strong>Specialization:</strong> <?= htmlspecialchars($doctor['spec_name'] ?? 'Not specified') ?></p>
        <p><strong>License:</strong> <?= htmlspecialchars($doctor['doc_license_number'] ?? 'Not specified') ?></p>
        <p><strong>Experience:</strong> <?= htmlspecialchars($doctor['doc_experience_years'] ?? '0') ?> years</p>
        <p><strong>Consultation Fee:</strong> ₱<?= number_format($doctor['doc_consultation_fee'] ?? 0, 2) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($doctor['doc_status'] ?? 'active') ?></p>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
