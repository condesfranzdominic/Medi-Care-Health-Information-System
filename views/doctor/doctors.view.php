<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Doctors</h1>
    <a href="/doctor/schedules" style="color: blue;">← Back to My Schedules</a>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin: 15px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 0;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <!-- Create Doctor Form -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Doctor</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>First Name: <span style="color: red;">*</span></label>
                    <input type="text" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label>Last Name: <span style="color: red;">*</span></label>
                    <input type="text" name="last_name" required>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Email: <span style="color: red;">*</span></label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Specialization:</label>
                    <select name="specialization_id">
                        <option value="">Select Specialization</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?= $spec['spec_id'] ?>"><?= htmlspecialchars($spec['spec_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>License Number:</label>
                    <input type="text" name="license_number">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Experience (Years):</label>
                    <input type="number" name="experience_years" min="0">
                </div>
                
                <div class="form-group">
                    <label>Consultation Fee:</label>
                    <input type="number" name="consultation_fee" step="0.01" min="0">
                </div>
            </div>
            
            <div class="form-group">
                <label>Qualification:</label>
                <textarea name="qualification" rows="2"></textarea>
            </div>
            
            <div class="form-group">
                <label>Bio:</label>
                <textarea name="bio" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">
            
            <div style="background: #f0f8ff; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                <h3 style="margin: 0 0 10px 0; color: #1976d2;">🔐 User Account (Login Credentials)</h3>
                <p style="margin: 0; font-size: 14px; color: #666;">Check the box below to create a user account for this doctor to login to the system.</p>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="create_user" value="1" id="create_user_checkbox" onchange="togglePasswordField()" style="margin-right: 10px; width: auto;">
                    <span>Create user account for login</span>
                </label>
            </div>
            
            <div class="form-group" id="password_field" style="display: none;">
                <label>Password: <span style="color: red;">*</span></label>
                <input type="password" name="password" id="password_input" minlength="6" placeholder="Minimum 6 characters">
                <small style="display: block; margin-top: 5px; color: #666;">The doctor will use their email and this password to login.</small>
            </div>
            
            <button type="submit" class="btn btn-success">Add Doctor</button>
        </form>
    </div>
    
    <script>
    function togglePasswordField() {
        const checkbox = document.getElementById('create_user_checkbox');
        const passwordField = document.getElementById('password_field');
        const passwordInput = document.getElementById('password_input');
        
        if (checkbox.checked) {
            passwordField.style.display = 'block';
            passwordInput.required = true;
        } else {
            passwordField.style.display = 'none';
            passwordInput.required = false;
            passwordInput.value = '';
        }
    }
    </script>
    
    <!-- Doctors List -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Doctors</h2>
        
        <?php if (empty($doctors)): ?>
            <p>No doctors found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Specialization</th>
                        <th>License</th>
                        <th>Experience</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td><?= $doctor['doc_id'] ?></td>
                            <td><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_email']) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($doctor['spec_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($doctor['doc_license_number'] ?? 'N/A') ?></td>
                            <td><?= $doctor['doc_experience_years'] ?? 'N/A' ?> years</td>
                            <td>$<?= number_format($doctor['doc_consultation_fee'] ?? 0, 2) ?></td>
                            <td>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                                    background: <?= $doctor['doc_status'] === 'active' ? '#d4edda' : '#f8d7da' ?>; 
                                    color: <?= $doctor['doc_status'] === 'active' ? '#155724' : '#721c24' ?>;">
                                    <?= ucfirst($doctor['doc_status']) ?>
                                </span>
                            </td>
                            <td>
                                <button onclick='editDoctor(<?= json_encode($doctor) ?>)' class="btn btn-primary btn-sm">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: white; margin: 50px auto; padding: 30px; width: 90%; max-width: 800px; border-radius: 8px; max-height: 90vh; overflow-y: auto;">
        <h2>Edit Doctor</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="first_name" id="edit_first_name" required>
                </div>
                
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" id="edit_last_name" required>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" id="edit_phone">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Specialization:</label>
                    <select name="specialization_id" id="edit_specialization_id">
                        <option value="">Select Specialization</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?= $spec['spec_id'] ?>"><?= htmlspecialchars($spec['spec_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>License Number:</label>
                    <input type="text" name="license_number" id="edit_license_number">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Experience (Years):</label>
                    <input type="number" name="experience_years" id="edit_experience_years" min="0">
                </div>
                
                <div class="form-group">
                    <label>Consultation Fee:</label>
                    <input type="number" name="consultation_fee" id="edit_consultation_fee" step="0.01" min="0">
                </div>
            </div>
            
            <div class="form-group">
                <label>Qualification:</label>
                <textarea name="qualification" id="edit_qualification" rows="2"></textarea>
            </div>
            
            <div class="form-group">
                <label>Bio:</label>
                <textarea name="bio" id="edit_bio" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status" id="edit_status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Update Doctor</button>
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function editDoctor(doctor) {
    document.getElementById('edit_id').value = doctor.doc_id;
    document.getElementById('edit_first_name').value = doctor.doc_first_name;
    document.getElementById('edit_last_name').value = doctor.doc_last_name;
    document.getElementById('edit_email').value = doctor.doc_email;
    document.getElementById('edit_phone').value = doctor.doc_phone || '';
    document.getElementById('edit_specialization_id').value = doctor.doc_specialization_id || '';
    document.getElementById('edit_license_number').value = doctor.doc_license_number || '';
    document.getElementById('edit_experience_years').value = doctor.doc_experience_years || '';
    document.getElementById('edit_consultation_fee').value = doctor.doc_consultation_fee || '';
    document.getElementById('edit_qualification').value = doctor.doc_qualification || '';
    document.getElementById('edit_bio').value = doctor.doc_bio || '';
    document.getElementById('edit_status').value = doctor.doc_status || 'active';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
