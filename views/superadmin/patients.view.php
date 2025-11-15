<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Patients</span>
        </div>
        <h1 class="page-title">Manage Patients</h1>
    </div>
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

<!-- Search and Filter Bar -->
<div class="search-filter-bar-modern">
    <button type="button" class="filter-toggle-btn" onclick="toggleFilterSidebar()">
        <i class="fas fa-filter"></i>
        <span>Filter</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <form method="GET" style="flex: 1; display: flex; align-items: center; gap: 0.75rem;">
        <div class="search-input-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-input-modern" 
                   value="<?= htmlspecialchars($search_query ?? '') ?>" 
                   placeholder="Search Patient...">
        </div>
        <?php if (!empty($search_query)): ?>
            <a href="/superadmin/patients" class="btn btn-sm btn-secondary">
                <i class="fas fa-times"></i>
                <span>Clear</span>
            </a>
        <?php endif; ?>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <button type="button" class="category-tab" data-category="active">Active</button>
        <button type="button" class="category-tab" data-category="inactive">Inactive</button>
    </div>
</div>

<?php if (!empty($search_query)): ?>
    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p>Found <?= count($patients) ?> result(s) for "<?= htmlspecialchars($search_query) ?>"</p>
    </div>
<?php endif; ?>

<!-- Add Patient Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddPatientModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Patient</span>
    </button>
</div>

<!-- Patients List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Patients</h2>
    </div>
    <?php if (empty($patients)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-user-injured"></i></div>
            <div class="empty-state-text">No patients found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
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
                            <td>
                                <strong><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($patient['pat_email']) ?></td>
                            <td><?= htmlspecialchars($patient['pat_phone'] ?? 'N/A') ?></td>
                            <td><?= $patient['pat_gender'] ? htmlspecialchars(ucfirst($patient['pat_gender'])) : 'N/A' ?></td>
                            <td><?= htmlspecialchars($patient['pat_date_of_birth'] ?? 'N/A') ?></td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editPatient(<?= json_encode($patient) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="viewPatientDetails(<?= json_encode($patient) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return handleDelete(event, 'Are you sure you want to delete this patient?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $patient['pat_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div class="pagination">
            <div class="pagination-controls">
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="pagination-btn" <?= $page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                    <i class="fas fa-angle-double-left"></i>
                </a>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>" class="pagination-btn" <?= $page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                    <i class="fas fa-angle-left"></i>
                </a>
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="pagination-btn <?= $i == $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => min($total_pages, $page + 1)])) ?>" class="pagination-btn" <?= $page >= $total_pages ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                    <i class="fas fa-angle-right"></i>
                </a>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" class="pagination-btn" <?= $page >= $total_pages ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </div>
            <div class="pagination-info" style="margin-top: 1rem; text-align: center; color: var(--text-secondary); font-size: 0.875rem;">
                Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> patients
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
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
                    <input type="text" name="phone" class="form-control">
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
                    <input type="text" name="emergency_phone" class="form-control">
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
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function viewPatientDetails(patient) {
    // Navigate to patient details page
    window.location.href = '/superadmin/patients?view=' + patient.pat_id;
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
});

function filterByCategory(category) {
    // Implement category filtering
    console.log('Filtering by category:', category);
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});
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
