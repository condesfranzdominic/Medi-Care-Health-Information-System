<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Patients</h1>
    <a href="/superadmin/dashboard" style="color: blue;">← Back to Dashboard</a>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin: 15px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 0;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <!-- Search Bar -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3>Search Patients</h3>
        <form method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" 
                   placeholder="Search by first name or last name..." 
                   style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <button type="submit" class="btn">Search</button>
            <?php if ($search_query): ?>
                <a href="/superadmin/patients" class="btn">Clear</a>
            <?php endif; ?>
        </form>
        <?php if ($search_query): ?>
            <p style="margin-top: 10px; color: #666;">
                Found <?= count($patients) ?> result(s) for "<?= htmlspecialchars($search_query) ?>"
            </p>
        <?php endif; ?>
    </div>
    
    <!-- Create Patient Form -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Patient</h2>
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
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth">
            </div>
            
            <div class="form-group">
                <label>Gender:</label>
                <select name="gender">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Emergency Contact:</label>
                <input type="text" name="emergency_contact">
            </div>
            
            <div class="form-group">
                <label>Emergency Phone:</label>
                <input type="text" name="emergency_phone">
            </div>
            
            <div class="form-group">
                <label>Medical History:</label>
                <textarea name="medical_history" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Allergies:</label>
                <textarea name="allergies" rows="2"></textarea>
            </div>
            
            <div class="form-group">
                <label>Insurance Provider:</label>
                <input type="text" name="insurance_provider">
            </div>
            
            <div class="form-group">
                <label>Insurance Number:</label>
                <input type="text" name="insurance_number">
            </div>
            
            <button type="submit" class="btn btn-success">Add Patient</button>
        </form>
    </div>
    
    <!-- Patients List -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Patients</h2>
        
        <?php if (empty($patients)): ?>
            <p>No patients found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars($patient['pat_id']) ?></td>
                            <td><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></td>
                            <td><?= htmlspecialchars($patient['pat_email']) ?></td>
                            <td><?= htmlspecialchars($patient['pat_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($patient['pat_gender'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($patient['pat_date_of_birth'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="editPatient(<?= htmlspecialchars(json_encode($patient)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $patient['pat_id'] ?>">
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

<!-- Edit Patient Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Patient</h2>
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
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" id="edit_date_of_birth">
            </div>
            
            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" id="edit_gender">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" id="edit_address" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Emergency Contact:</label>
                <input type="text" name="emergency_contact" id="edit_emergency_contact">
            </div>
            
            <div class="form-group">
                <label>Emergency Phone:</label>
                <input type="text" name="emergency_phone" id="edit_emergency_phone">
            </div>
            
            <div class="form-group">
                <label>Medical History:</label>
                <textarea name="medical_history" id="edit_medical_history" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Allergies:</label>
                <textarea name="allergies" id="edit_allergies" rows="2"></textarea>
            </div>
            
            <div class="form-group">
                <label>Insurance Provider:</label>
                <input type="text" name="insurance_provider" id="edit_insurance_provider">
            </div>
            
            <div class="form-group">
                <label>Insurance Number:</label>
                <input type="text" name="insurance_number" id="edit_insurance_number">
            </div>
            
            <button type="submit" class="btn btn-success">Update Patient</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editPatient(patient) {
    document.getElementById('edit_id').value = patient.pat_id;
    document.getElementById('edit_first_name').value = patient.pat_first_name;
    document.getElementById('edit_last_name').value = patient.pat_last_name;
    document.getElementById('edit_email').value = patient.pat_email;
    document.getElementById('edit_phone').value = patient.pat_phone || '';
    document.getElementById('edit_date_of_birth').value = patient.pat_date_of_birth || '';
    document.getElementById('edit_gender').value = patient.pat_gender || '';
    document.getElementById('edit_address').value = patient.pat_address || '';
    document.getElementById('edit_emergency_contact').value = patient.pat_emergency_contact || '';
    document.getElementById('edit_emergency_phone').value = patient.pat_emergency_phone || '';
    document.getElementById('edit_medical_history').value = patient.pat_medical_history || '';
    document.getElementById('edit_allergies').value = patient.pat_allergies || '';
    document.getElementById('edit_insurance_provider').value = patient.pat_insurance_provider || '';
    document.getElementById('edit_insurance_number').value = patient.pat_insurance_number || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
