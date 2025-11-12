<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Doctors</span>
        </div>
        <h1 class="page-title">Manage Doctors</h1>
    </div>
</div>

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
                   placeholder="Search Doctor...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <?php if (isset($specializations)): ?>
            <?php foreach (array_slice($specializations, 0, 5) as $spec): ?>
                <button type="button" class="category-tab" data-category="<?= $spec['spec_id'] ?>">
                    <?= htmlspecialchars($spec['spec_name']) ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($spec_filter) && $spec_filter && isset($spec_name_filter) && $spec_name_filter): ?>
    <div class="info-box">
        <i class="fas fa-filter"></i>
        <p>
            <strong>Filtered by Specialization:</strong> <?= htmlspecialchars($spec_name_filter) ?>
            <a href="/superadmin/doctors" style="margin-left: 15px; color: var(--primary-blue); text-decoration: underline;">Clear Filter</a>
        </p>
    </div>
<?php endif; ?>

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

<!-- Add Doctor Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddDoctorModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Doctor</span>
    </button>
</div>

<!-- Doctors List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Doctors</h2>
    </div>
    <?php if (empty($doctors)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-user-md"></i></div>
            <div class="empty-state-text">No doctors found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
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
                            <td><strong><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($doctor['doc_email']) ?></td>
                            <td><?= htmlspecialchars($doctor['doc_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($doctor['spec_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($doctor['doc_license_number'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($doctor['doc_consultation_fee'] ?? 0, 2) ?></td>
                            <td>
                                <span class="status-badge <?= ($doctor['doc_status'] ?? 'active') === 'active' ? 'active' : 'inactive' ?>">
                                    <?= htmlspecialchars($doctor['doc_status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editDoctor(<?= htmlspecialchars(json_encode($doctor)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="viewDoctorDetails(<?= htmlspecialchars(json_encode($doctor)) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $doctor['doc_id'] ?>">
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
                Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> doctors
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Add Doctor Modal -->
<div id="addModal" class="modal">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="modal-title">Add New Doctor</h2>
            <button type="button" class="modal-close" onclick="closeAddDoctorModal()">
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
                    <label>Specialization:</label>
                    <select name="specialization_id" class="form-control">
                        <option value="">Select Specialization</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?= $spec['spec_id'] ?>"><?= htmlspecialchars($spec['spec_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>License Number:</label>
                    <input type="text" name="license_number" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Experience (Years):</label>
                    <input type="number" name="experience_years" min="0" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Consultation Fee:</label>
                    <input type="number" name="consultation_fee" step="0.01" min="0" class="form-control">
                </div>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Qualification:</label>
                <textarea name="qualification" rows="2" class="form-control"></textarea>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Bio:</label>
                <textarea name="bio" rows="3" class="form-control"></textarea>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="info-box" style="margin-top: 1.5rem;">
                <i class="fas fa-lock"></i>
                <p><strong>User Account (Login Credentials):</strong> Check the box below to create a user account for this doctor to login to the system.</p>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="create_user" value="1" id="add_create_user_checkbox" onchange="toggleAddPasswordField()" style="margin-right: 10px; width: auto;">
                    <span>Create user account for login</span>
                </label>
            </div>
            
            <div class="form-group" id="add_password_field" style="display: none;">
                <label>Password: <span style="color: var(--status-error);">*</span></label>
                <input type="password" name="password" id="add_password_input" minlength="6" placeholder="Minimum 6 characters" class="form-control">
                <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary);">The doctor will use their email and this password to login.</small>
            </div>
            
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Doctor</span>
                </button>
                <button type="button" onclick="closeAddDoctorModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Doctor Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Doctor</h2>
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
                    <label>Specialization:</label>
                    <select name="specialization_id" id="edit_specialization_id" class="form-control">
                        <option value="">Select Specialization</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?= $spec['spec_id'] ?>"><?= htmlspecialchars($spec['spec_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>License Number:</label>
                    <input type="text" name="license_number" id="edit_license_number" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Experience (Years):</label>
                    <input type="number" name="experience_years" id="edit_experience_years" min="0" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Consultation Fee:</label>
                    <input type="number" name="consultation_fee" id="edit_consultation_fee" step="0.01" min="0" class="form-control">
                </div>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Qualification:</label>
                <textarea name="qualification" id="edit_qualification" rows="2" class="form-control"></textarea>
            </div>
            
            <div class="form-group form-grid-full">
                <label>Bio:</label>
                <textarea name="bio" id="edit_bio" rows="3" class="form-control"></textarea>
            </div>
            
            <div class="form-group">
                <label>Status:</label>
                <select name="status" id="edit_status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Doctor</span>
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
function openAddDoctorModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddDoctorModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
    document.getElementById('add_password_field').style.display = 'none';
    document.getElementById('add_password_input').required = false;
}

function toggleAddPasswordField() {
    const checkbox = document.getElementById('add_create_user_checkbox');
    const passwordField = document.getElementById('add_password_field');
    const passwordInput = document.getElementById('add_password_input');
    
    if (checkbox.checked) {
        passwordField.style.display = 'block';
        passwordInput.required = true;
    } else {
        passwordField.style.display = 'none';
        passwordInput.required = false;
        passwordInput.value = '';
    }
}

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
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function viewDoctorDetails(doctor) {
    window.location.href = '/superadmin/doctors?view=' + doctor.doc_id;
}

// Category tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const category = this.dataset.category;
            filterByCategory(category);
        });
    });
});

function filterByCategory(category) {
    if (category === 'all') {
        window.location.href = '/superadmin/doctors';
    } else {
        window.location.href = '/superadmin/doctors?spec_id=' + category;
    }
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
    
    <!-- Specialization Filter -->
    <?php if (!empty($specializations)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('specialization')">
            <h4 class="filter-section-title">Specialization</h4>
            <button type="button" class="filter-section-toggle" id="specializationToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="specializationContent">
            <input type="text" class="filter-search-input" placeholder="Search Specialization" id="specializationSearch">
            <div class="filter-radio-group" id="specializationList">
                <?php foreach ($specializations as $spec): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_specialization" id="spec_<?= $spec['spec_id'] ?>" value="<?= $spec['spec_id'] ?>">
                        <label for="spec_<?= $spec['spec_id'] ?>"><?= htmlspecialchars($spec['spec_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Status Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('status')">
            <h4 class="filter-section-title">Status</h4>
            <button type="button" class="filter-section-toggle" id="statusToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="statusContent">
            <div class="filter-radio-group">
                <div class="filter-radio-item">
                    <input type="radio" name="filter_status" id="status_active" value="active">
                    <label for="status_active">Active</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="filter_status" id="status_inactive" value="inactive">
                    <label for="status_inactive">Inactive</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyDoctorFilters()">Apply all filter</button>
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
    const specializationSearch = document.getElementById('specializationSearch');
    if (specializationSearch) {
        specializationSearch.value = '';
    }
}

function applyDoctorFilters() {
    const filters = {
        specialization: document.querySelector('input[name="filter_specialization"]:checked')?.value || '',
        status: document.querySelector('input[name="filter_status"]:checked')?.value || ''
    };
    
    const params = new URLSearchParams();
    if (filters.specialization) params.append('spec_id', filters.specialization);
    if (filters.status) params.append('status', filters.status);
    
    const url = '/superadmin/doctors' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

// Specialization search functionality
document.addEventListener('DOMContentLoaded', function() {
    const specializationSearch = document.getElementById('specializationSearch');
    if (specializationSearch) {
        specializationSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const specializationItems = document.querySelectorAll('#specializationList .filter-radio-item');
            specializationItems.forEach(item => {
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
