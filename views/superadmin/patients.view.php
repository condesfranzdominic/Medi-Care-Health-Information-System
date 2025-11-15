<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header" style="margin-bottom: 2rem;">
    <h1 class="page-title" style="margin: 0;">All Patients</h1>
</div>

<?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?= htmlspecialchars($success) ?></span>
    </div>
<?php endif; ?>

<!-- Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: #8b5cf6;"></div>
            <span style="font-size: 0.875rem; color: var(--text-secondary);">Total Patients</span>
        </div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);"><?= ($stats['active'] ?? 0) + ($stats['inactive'] ?? 0) ?></div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></div>
            <span style="font-size: 0.875rem; color: var(--text-secondary);">New Patients This Month</span>
        </div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);"><?= $stats['total_this_month'] ?? 0 ?></div>
    </div>
</div>

<!-- Table Container -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
    <!-- Table Header with Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; border-bottom: 1px solid var(--border-light);">
        <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">All Patients</h2>
        <button type="button" class="btn btn-primary" onclick="openAddPatientModal()" style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-plus"></i>
            <span>Add Patient</span>
        </button>
    </div>

    <?php if (empty($patients)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-secondary);">
            <i class="fas fa-user-injured" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
            <p style="margin: 0;">No patients found.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid var(--border-light);">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">
                            Patient Name
                        </th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">
                            Email
                        </th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">
                            Phone
                        </th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">
                            Gender
                        </th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">
                            Date of Birth
                        </th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-primary); font-size: 0.875rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr style="border-bottom: 1px solid var(--border-light); transition: background 0.2s;" 
                            onmouseover="this.style.background='#f9fafb'" 
                            onmouseout="this.style.background='white'">
                            <td style="padding: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                                        <?= strtoupper(substr($patient['pat_first_name'] ?? 'P', 0, 1)) ?>
                                    </div>
                                    <strong style="color: var(--text-primary);"><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></strong>
                                </div>
                            </td>
                            <td style="padding: 1rem; color: var(--text-secondary);"><?= htmlspecialchars($patient['pat_email']) ?></td>
                            <td style="padding: 1rem; color: var(--text-secondary);"><?= htmlspecialchars($patient['pat_phone'] ?? 'N/A') ?></td>
                            <td style="padding: 1rem; color: var(--text-secondary);"><?= $patient['pat_gender'] ? htmlspecialchars(ucfirst($patient['pat_gender'])) : 'N/A' ?></td>
                            <td style="padding: 1rem; color: var(--text-secondary);"><?= $patient['pat_date_of_birth'] ? date('d M Y', strtotime($patient['pat_date_of_birth'])) : 'N/A' ?></td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <button class="btn btn-sm edit-patient-btn" 
                                            data-patient="<?= base64_encode(json_encode($patient)) ?>" 
                                            title="Edit"
                                            style="padding: 0.5rem; background: transparent; border: none; color: var(--primary-blue); cursor: pointer;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return handleDelete(event, 'Are you sure you want to delete this patient?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $patient['pat_id'] ?>">
                                        <button type="submit" class="btn btn-sm" title="Delete"
                                                style="padding: 0.5rem; background: transparent; border: none; color: var(--status-error); cursor: pointer;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm view-patient-btn" 
                                            data-patient="<?= base64_encode(json_encode($patient)) ?>" 
                                            title="More"
                                            style="padding: 0.5rem; background: transparent; border: none; color: var(--text-secondary); cursor: pointer;">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Pagination -->
        <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; border-top: 1px solid var(--border-light);">
            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> entries
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>" 
                   class="btn btn-sm" 
                   style="<?= $page <= 1 ? 'opacity: 0.5; pointer-events: none;' : '' ?>">
                    < Previous
                </a>
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                if ($start_page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="btn btn-sm">1</a>
                    <?php if ($start_page > 2): ?>
                        <span style="padding: 0.5rem;">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                       class="btn btn-sm <?= $i == $page ? 'btn-primary' : '' ?>" 
                       style="<?= $i == $page ? 'background: var(--primary-blue); color: white;' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <span style="padding: 0.5rem;">...</span>
                    <?php endif; ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" class="btn btn-sm"><?= $total_pages ?></a>
                <?php endif; ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => min($total_pages, $page + 1)])) ?>" 
                   class="btn btn-sm" 
                   style="<?= $page >= $total_pages ? 'opacity: 0.5; pointer-events: none;' : '' ?>">
                    Next >
                </a>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- View Patient Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2 class="modal-title">Patient Details</h2>
            <button type="button" class="modal-close" onclick="closeViewModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="viewContent"></div>
        <div class="action-buttons" style="margin-top: 1.5rem;">
            <button type="button" onclick="closeViewModal()" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                <span>Close</span>
            </button>
        </div>
    </div>
</div>

<!-- Add Patient Modal -->
<div id="addModal" class="modal">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="modal-title">Add New Patient</h2>
            <button type="button" class="modal-close" onclick="closeAddPatientModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="first_name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Last Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="last_name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Email: <span style="color: var(--status-error);">*</span></label>
                    <input type="email" name="email" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" id="add_phone" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Date of Birth:</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Address:</label>
                <textarea name="address" rows="3" class="form-control"></textarea>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Emergency Contact:</label>
                    <input type="text" name="emergency_contact" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Emergency Phone:</label>
                    <input type="text" name="emergency_phone" id="add_emergency_phone" class="form-control">
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group form-grid-full">
                    <label>Medical History:</label>
                    <textarea name="medical_history" rows="3" class="form-control"></textarea>
                </div>
                
                <div class="form-group form-grid-full">
                    <label>Allergies:</label>
                    <textarea name="allergies" rows="2" class="form-control"></textarea>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Insurance Provider:</label>
                    <input type="text" name="insurance_provider" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Insurance Number:</label>
                    <input type="text" name="insurance_number" class="form-control">
                </div>
            </div>
            
            <div class="info-box" style="margin-top: 1.5rem;">
                <i class="fas fa-lock"></i>
                <p><strong>User Account (Login Credentials):</strong> Check the box below to create a user account for this patient to login to the system.</p>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="create_user" value="1" id="patient_create_user_checkbox" onchange="togglePatientPasswordField()" style="margin-right: 10px; width: auto;">
                    <span>Create user account for login</span>
                </label>
            </div>
            
            <div class="form-group" id="patient_password_field" style="display: none;">
                <label>Password: <span style="color: var(--status-error);">*</span></label>
                <input type="password" name="password" id="patient_password_input" minlength="6" placeholder="Minimum 6 characters" class="form-control">
                <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary);">The patient will use their email and this password to login.</small>
            </div>
            
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Patient</span>
                </button>
                <button type="button" onclick="closeAddPatientModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Patient Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Patient</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="first_name" id="edit_first_name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Last Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="last_name" id="edit_last_name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Email: <span style="color: var(--status-error);">*</span></label>
                    <input type="email" name="email" id="edit_email" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Date of Birth:</label>
                    <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" id="edit_gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Address:</label>
                <textarea name="address" id="edit_address" rows="3" class="form-control"></textarea>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Emergency Contact:</label>
                    <input type="text" name="emergency_contact" id="edit_emergency_contact" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Emergency Phone:</label>
                    <input type="text" name="emergency_phone" id="edit_emergency_phone" class="form-control">
                </div>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Medical History:</label>
                <textarea name="medical_history" id="edit_medical_history" rows="3" class="form-control"></textarea>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Allergies:</label>
                <textarea name="allergies" id="edit_allergies" rows="2" class="form-control"></textarea>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Insurance Provider:</label>
                    <input type="text" name="insurance_provider" id="edit_insurance_provider" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Insurance Number:</label>
                    <input type="text" name="insurance_number" id="edit_insurance_number" class="form-control">
                </div>
            </div>
            
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Patient</span>
                </button>
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddPatientModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddPatientModal() {
    document.getElementById('addModal').classList.remove('active');
    // Reset form
    document.querySelector('#addModal form').reset();
    document.getElementById('patient_password_field').style.display = 'none';
    document.getElementById('patient_password_input').required = false;
}

function togglePatientPasswordField() {
    const checkbox = document.getElementById('patient_create_user_checkbox');
    const passwordField = document.getElementById('patient_password_field');
    const passwordInput = document.getElementById('patient_password_input');
    
    if (checkbox.checked) {
        passwordField.style.display = 'block';
        passwordInput.required = true;
    } else {
        passwordField.style.display = 'none';
        passwordInput.required = false;
        passwordInput.value = '';
    }
}

// Phone number formatting function (Philippine format: XXXX-XXX-XXXX)
function formatPhoneNumber(value) {
    if (!value) return '';
    let digits = value.toString().replace(/\D/g, '');
    if (digits.length > 11) digits = digits.substring(0, 11);
    if (digits.length >= 7) {
        return digits.substring(0, 4) + '-' + digits.substring(4, 7) + '-' + digits.substring(7);
    } else if (digits.length >= 4) {
        return digits.substring(0, 4) + '-' + digits.substring(4);
    }
    return digits;
}

function formatPhoneInput(inputId) {
    const input = document.getElementById(inputId);
    if (input && !input.hasAttribute('data-phone-formatted')) {
        input.setAttribute('data-phone-formatted', 'true');
        input.addEventListener('input', function(e) {
            const cursorPosition = e.target.selectionStart;
            const oldValue = e.target.value;
            const formatted = formatPhoneNumber(e.target.value);
            if (oldValue !== formatted) {
                e.target.value = formatted;
                const newCursorPosition = cursorPosition + (formatted.length - oldValue.length);
                setTimeout(() => e.target.setSelectionRange(newCursorPosition, newCursorPosition), 0);
            }
        });
        input.addEventListener('blur', function(e) {
            if (e.target.value) e.target.value = formatPhoneNumber(e.target.value);
        });
        if (input.value) input.value = formatPhoneNumber(input.value);
    }
}

function editPatient(patient) {
    document.getElementById('edit_id').value = patient.pat_id;
    document.getElementById('edit_first_name').value = patient.pat_first_name;
    document.getElementById('edit_last_name').value = patient.pat_last_name;
    document.getElementById('edit_email').value = patient.pat_email;
    document.getElementById('edit_phone').value = patient.pat_phone ? formatPhoneNumber(patient.pat_phone) : '';
    document.getElementById('edit_date_of_birth').value = patient.pat_date_of_birth || '';
    document.getElementById('edit_gender').value = patient.pat_gender || '';
    document.getElementById('edit_address').value = patient.pat_address || '';
    document.getElementById('edit_emergency_contact').value = patient.pat_emergency_contact || '';
    document.getElementById('edit_emergency_phone').value = patient.pat_emergency_phone ? formatPhoneNumber(patient.pat_emergency_phone) : '';
    document.getElementById('edit_medical_history').value = patient.pat_medical_history || '';
    document.getElementById('edit_allergies').value = patient.pat_allergies || '';
    document.getElementById('edit_insurance_provider').value = patient.pat_insurance_provider || '';
    document.getElementById('edit_insurance_number').value = patient.pat_insurance_number || '';
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function viewPatientDetails(patient) {
    const content = `
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <h3 style="margin-bottom: 1rem; color: var(--text-primary);">Patient Information</h3>
                <div class="form-grid">
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Patient ID:</strong> ${patient.pat_id || 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>First Name:</strong> ${patient.pat_first_name || 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>Last Name:</strong> ${patient.pat_last_name || 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>Email:</strong> ${patient.pat_email || 'N/A'}</p>
                    </div>
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Phone:</strong> ${patient.pat_phone || 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>Gender:</strong> ${patient.pat_gender ? patient.pat_gender.charAt(0).toUpperCase() + patient.pat_gender.slice(1) : 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>Date of Birth:</strong> ${patient.pat_date_of_birth || 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>Blood Type:</strong> ${patient.pat_blood_type || 'N/A'}</p>
                    </div>
                </div>
                ${patient.pat_address ? `<div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);"><p style="margin: 0;"><strong>Address:</strong> ${patient.pat_address}</p></div>` : ''}
                ${patient.pat_medical_history ? `<div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);"><p style="margin: 0;"><strong>Medical History:</strong> ${patient.pat_medical_history}</p></div>` : ''}
            </div>
        </div>
    `;
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').classList.add('active');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('active');
}

// Category tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const category = this.dataset.category;
            // Filter patients by category
            filterByCategory(category);
        });
    });
    
    // Add event listeners for edit and view buttons
    document.querySelectorAll('.edit-patient-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            try {
                const encodedData = this.getAttribute('data-patient');
                const decodedJson = atob(encodedData);
                const patientData = JSON.parse(decodedJson);
                editPatient(patientData);
            } catch (e) {
                console.error('Error parsing patient data:', e);
                alert('Error loading patient data. Please check the console for details.');
            }
        });
    });
    
    document.querySelectorAll('.view-patient-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            try {
                const encodedData = this.getAttribute('data-patient');
                const decodedJson = atob(encodedData);
                const patientData = JSON.parse(decodedJson);
                viewPatientDetails(patientData);
            } catch (e) {
                console.error('Error parsing patient data:', e);
                alert('Error loading patient data. Please check the console for details.');
            }
        });
    });
    
    // Initialize phone number formatting
    formatPhoneInput('edit_phone');
    formatPhoneInput('edit_emergency_phone');
    formatPhoneInput('add_phone');
    formatPhoneInput('add_emergency_phone');
    
    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
    
    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });
});

function filterByCategory(category) {
    // Implement category filtering
    console.log('Filtering by category:', category);
}

</script>


<!-- Filter Sidebar -->
<div class="filter-sidebar" id="filterSidebar">
    <div class="filter-sidebar-header">
        <h3 class="filter-sidebar-title">Filters</h3>
        <button type="button" class="filter-sidebar-close" onclick="toggleFilterSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Gender Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('gender')">
            <h4 class="filter-section-title">Gender</h4>
            <button type="button" class="filter-section-toggle" id="genderToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="genderContent">
            <div class="filter-radio-group">
                <?php if (!empty($filter_genders)): ?>
                    <?php 
                    $seen_genders = [];
                    foreach ($filter_genders as $gender): 
                        $gender_normalized = strtolower(trim($gender));
                        // Skip if we've already seen this gender (case-insensitive)
                        if (in_array($gender_normalized, $seen_genders)) continue;
                        $seen_genders[] = $gender_normalized;
                    ?>
                        <div class="filter-radio-item">
                            <input type="radio" name="filter_gender" id="gender_<?= htmlspecialchars($gender_normalized) ?>" value="<?= htmlspecialchars($gender_normalized) ?>">
                            <label for="gender_<?= htmlspecialchars($gender_normalized) ?>"><?= htmlspecialchars(ucfirst($gender_normalized)) ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_gender" id="gender_male" value="male">
                        <label for="gender_male">Male</label>
                    </div>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_gender" id="gender_female" value="female">
                        <label for="gender_female">Female</label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Insurance Provider Filter -->
    <?php if (!empty($filter_insurance_providers)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('insurance')">
            <h4 class="filter-section-title">Insurance Provider</h4>
            <button type="button" class="filter-section-toggle" id="insuranceToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="insuranceContent">
            <input type="text" class="filter-search-input" placeholder="Search Insurance Provider" id="insuranceSearch">
            <div class="filter-radio-group" id="insuranceList">
                <?php foreach ($filter_insurance_providers as $provider): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_insurance" id="insurance_<?= htmlspecialchars(strtolower(str_replace(' ', '_', $provider))) ?>" value="<?= htmlspecialchars($provider) ?>">
                        <label for="insurance_<?= htmlspecialchars(strtolower(str_replace(' ', '_', $provider))) ?>"><?= htmlspecialchars($provider) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyPatientFilters()">Apply all filter</button>
    </div>
</div>

<script>
function toggleFilterSidebar() {
    const sidebar = document.getElementById('filterSidebar');
    const mainContent = document.querySelector('.main-content');
    const filterBtn = document.querySelector('.filter-toggle-btn');
    
    sidebar.classList.toggle('active');
    if (mainContent) {
        mainContent.classList.toggle('filter-active');
    }
    if (filterBtn) {
        filterBtn.classList.toggle('active');
    }
}

function toggleFilterSection(sectionId) {
    const content = document.getElementById(sectionId + 'Content');
    const toggle = document.getElementById(sectionId + 'Toggle');
    
    if (content && toggle) {
        content.classList.toggle('collapsed');
        const icon = toggle.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-chevron-up');
            icon.classList.toggle('fa-chevron-down');
        }
    }
}

function clearAllFilters() {
    document.querySelectorAll('.filter-sidebar input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    const insuranceSearch = document.getElementById('insuranceSearch');
    if (insuranceSearch) {
        insuranceSearch.value = '';
    }
}

function applyPatientFilters() {
    const filters = {
        gender: document.querySelector('input[name="filter_gender"]:checked')?.value || '',
        insurance: document.querySelector('input[name="filter_insurance"]:checked')?.value || ''
    };
    
    // Build URL with filters
    const params = new URLSearchParams();
    if (filters.gender) params.append('gender', filters.gender);
    if (filters.insurance) params.append('insurance', filters.insurance);
    
    const url = '/superadmin/patients' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

// Insurance search functionality
document.addEventListener('DOMContentLoaded', function() {
    const insuranceSearch = document.getElementById('insuranceSearch');
    if (insuranceSearch) {
        insuranceSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const insuranceItems = document.querySelectorAll('#insuranceList .filter-radio-item');
            insuranceItems.forEach(item => {
                const label = item.querySelector('label');
                if (label) {
                    const text = label.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
                }
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
