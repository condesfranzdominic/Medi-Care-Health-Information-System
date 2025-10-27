<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Doctors</h1>
    <a href="/superadmin/dashboard" style="color: blue;">← Back to Dashboard</a>
    
    <?php if ($spec_filter && $spec_name_filter): ?>
        <div style="background: #e3f2fd; color: #1976d2; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #1976d2;">
            <strong>📋 Filtered by Specialization:</strong> <?= htmlspecialchars($spec_name_filter) ?>
            <a href="/superadmin/doctors" style="margin-left: 15px; color: #1976d2; text-decoration: underline;">Clear Filter</a>
        </div>
    <?php endif; ?>
    
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
            
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>
            
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone">
            </div>
            
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
            
            <div class="form-group">
                <label>Experience (Years):</label>
                <input type="number" name="experience_years" min="0">
            </div>
            
            <div class="form-group">
                <label>Consultation Fee:</label>
                <input type="number" name="consultation_fee" step="0.01" min="0">
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
            
            <button type="submit" class="btn btn-success">Add Doctor</button>
        </form>
    </div>
    
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
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td><?= htmlspecialchars($doctor['doc_id']) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_email']) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($doctor['spec_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($doctor['doc_license_number'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($doctor['doc_consultation_fee'] ?? 0, 2) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_status'] ?? 'active') ?></td>
                            <td>
                                <button onclick="editDoctor(<?= htmlspecialchars(json_encode($doctor)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $doctor['doc_id'] ?>">
                                    <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 5px 10px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Doctor Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Doctor</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" id="edit_first_name" required>
            </div>
            
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" id="edit_last_name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" id="edit_phone">
            </div>
            
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
            
            <div class="form-group">
                <label>Experience (Years):</label>
                <input type="number" name="experience_years" id="edit_experience_years" min="0">
            </div>
            
            <div class="form-group">
                <label>Consultation Fee:</label>
                <input type="number" name="consultation_fee" id="edit_consultation_fee" step="0.01" min="0">
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
            
            <button type="submit" class="btn btn-success">Update Doctor</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
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
