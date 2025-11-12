<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/patient/appointments">
                <i class="fas fa-calendar"></i>
                <span>My Appointments</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Book Appointment</span>
        </div>
        <h1 class="page-title">Book New Appointment</h1>
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
        <div>
            <p style="margin-bottom: 1rem;"><?= $success ?></p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="/patient/appointments" class="btn btn-success">
                    <i class="fas fa-calendar"></i>
                    <span>View My Appointments</span>
                </a>
                <a href="/patient/appointments/create" class="btn btn-secondary">
                    <i class="fas fa-plus"></i>
                    <span>Book Another</span>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Appointment Details</h2>
    </div>
    <div class="card-body">
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Please fill in the details below to book your appointment. You will receive an appointment ID for your reference.</p>
        
        <form method="POST">
            <div class="form-group">
                <label>Select Doctor: <span style="color: var(--status-error);">*</span></label>
                <select name="doctor_id" id="doctor_id" required onchange="showDoctorInfo()" class="form-control">
                    <option value="">Choose a doctor...</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor['doc_id'] ?>" 
                                data-spec="<?= htmlspecialchars($doctor['spec_name'] ?? 'General') ?>"
                                data-fee="<?= number_format($doctor['doc_consultation_fee'] ?? 0, 2) ?>"
                                data-bio="<?= htmlspecialchars($doctor['doc_bio'] ?? '') ?>">
                            Dr. <?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?> 
                            - <?= htmlspecialchars($doctor['spec_name'] ?? 'General') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div id="doctorInfo" style="display: none; background: var(--primary-blue-bg); padding: 1rem; border-radius: var(--radius-md); margin: 1rem 0; border-left: 4px solid var(--primary-blue);">
                <h4 style="margin: 0 0 0.75rem 0; color: var(--text-primary);">Doctor Information</h4>
                <p style="margin: 0.5rem 0; color: var(--text-primary);">
                    <strong>Specialization:</strong> <span id="docSpec"></span>
                </p>
                <p style="margin: 0.5rem 0; color: var(--text-primary);">
                    <strong>Consultation Fee:</strong> ₱<span id="docFee"></span>
                </p>
                <p style="margin: 0.5rem 0; color: var(--text-primary);" id="docBioContainer">
                    <strong>About:</strong> <span id="docBio"></span>
                </p>
            </div>
            
            <div class="form-group">
                <label>Service (Optional):</label>
                <select name="service_id" class="form-control">
                    <option value="">Select a service...</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['service_id'] ?>">
                            <?= htmlspecialchars($service['service_name']) ?> 
                            - ₱<?= number_format($service['service_price'] ?? 0, 2) ?>
                            (<?= $service['service_duration_minutes'] ?? 30 ?> min)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Appointment Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="appointment_date" min="<?= date('Y-m-d') ?>" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Preferred Time: <span style="color: var(--status-error);">*</span></label>
                    <input type="time" name="appointment_time" required class="form-control">
                </div>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Notes/Reason for Visit:</label>
                <textarea name="notes" rows="4" placeholder="Please describe your symptoms or reason for visit..." class="form-control"></textarea>
            </div>
            
            <div class="info-box" style="margin-top: 1.5rem;">
                <i class="fas fa-info-circle"></i>
                <p><strong>Note:</strong> Your appointment request will be reviewed. You will receive an appointment ID immediately after submission. Please keep this ID for your reference.</p>
            </div>
            
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success" style="font-size: 1rem; padding: 0.75rem 2rem;">
                    <i class="fas fa-calendar-check"></i>
                    <span>Book Appointment</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showDoctorInfo() {
    const select = document.getElementById('doctor_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('docSpec').textContent = option.dataset.spec;
        document.getElementById('docFee').textContent = option.dataset.fee;
        
        const bio = option.dataset.bio;
        if (bio) {
            document.getElementById('docBio').textContent = bio;
            document.getElementById('docBioContainer').style.display = 'block';
        } else {
            document.getElementById('docBioContainer').style.display = 'none';
        }
        
        document.getElementById('doctorInfo').style.display = 'block';
    } else {
        document.getElementById('doctorInfo').style.display = 'none';
    }
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
