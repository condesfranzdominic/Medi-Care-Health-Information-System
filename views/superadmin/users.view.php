<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Users</span>
        </div>
        <h1 class="page-title">Manage Users</h1>
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
                   placeholder="Search User...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab <?= empty($filter_role) ? 'active' : '' ?>" data-category="all">All</button>
        <button type="button" class="category-tab <?= $filter_role === 'superadmin' ? 'active' : '' ?>" data-category="superadmin">Super Admin</button>
        <button type="button" class="category-tab <?= $filter_role === 'staff' ? 'active' : '' ?>" data-category="staff">Staff</button>
        <button type="button" class="category-tab <?= $filter_role === 'doctor' ? 'active' : '' ?>" data-category="doctor">Doctor</button>
        <button type="button" class="category-tab <?= $filter_role === 'patient' ? 'active' : '' ?>" data-category="patient">Patient</button>
    </div>
</div>

<!-- Add User Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddUserModal()">
        <i class="fas fa-plus"></i>
        <span>Create New User</span>
    </button>
</div>

<!-- Users List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Users</h2>
    </div>
    <?php if (empty($users)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-users"></i></div>
            <div class="empty-state-text">No users found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php
                            // Determine role
                            $role = 'None';
                            $roleColor = '#999';
                            
                            if ($user['user_is_superadmin']) {
                                $role = 'Super Admin';
                                $roleColor = '#e74c3c';
                            } elseif ($user['staff_id']) {
                                $role = 'Staff';
                                $roleColor = '#3498db';
                            } elseif ($user['doc_id']) {
                                $role = 'Doctor';
                                $roleColor = '#2ecc71';
                            } elseif ($user['pat_id']) {
                                $role = 'Patient';
                                $roleColor = '#9b59b6';
                            }
                            
                            // Format phone number
                            $phone_display = !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A';
                            
                            // Format date
                            $date_created = !empty($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A';
                            
                            // Status
                            $status = $user['status'] ?? 'active';
                            $statusColor = ($status === 'active' || $status === 'Super Admin') ? '#10B981' : '#EF4444';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['user_email']) ?></td>
                            <td><?= $phone_display ?></td>
                            <td><?= $date_created ?></td>
                            <td>
                                <span class="badge" style="background: <?= $statusColor ?>20; color: <?= $statusColor ?>;">
                                    <?= htmlspecialchars(ucfirst($status)) ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return handleDelete(event, 'Are you sure you want to delete this user?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $user['user_id'] ?>">
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
                Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> users
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Add User Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create New User</h2>
            <button type="button" class="modal-close" onclick="closeAddUserModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Select a role to create the user account. You will be redirected to create the corresponding profile.</p>
            <form id="createUserForm" onsubmit="return redirectToRoleCreation(event)">
                <div class="form-group">
                    <label>Select Role: <span style="color: var(--status-error);">*</span></label>
                    <select id="role_select" required class="form-control">
                        <option value="">-- Select Role --</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="staff">Staff</option>
                        <option value="doctor">Doctor</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>
                
                <div class="action-buttons" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-arrow-right"></i>
                        <span>Continue to Create Profile</span>
                    </button>
                    <button type="button" onclick="closeAddUserModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit User</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="edit_email" required class="form-control">
            </div>
            
            <div class="form-group">
                <label>Password (leave blank to keep current):</label>
                <input type="password" name="password" id="edit_password" class="form-control">
            </div>
            
            <div class="form-group">
                <label>Change Role:</label>
                <select name="role" id="edit_role" class="form-control">
                    <option value="none">No Role</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="staff">Staff</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>
                <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Current role will be displayed when you open this form</small>
            </div>
            
            <div id="role_link_section" class="form-group" style="display: none;">
                <label id="role_link_label">Link to Profile ID:</label>
                <input type="number" name="role_id" id="edit_role_id" min="1" class="form-control">
                <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Enter the existing Staff/Doctor/Patient ID to link this user account</small>
            </div>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>
                    <strong>Important:</strong> Changing role will update the user's access level. Make sure the profile (Staff/Doctor/Patient) exists before linking. Super Admin role doesn't require a profile ID.
                </p>
            </div>
            
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update User</span>
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
function openAddUserModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddUserModal() {
    document.getElementById('addModal').classList.remove('active');
    document.getElementById('role_select').value = '';
}

function redirectToRoleCreation(event) {
    event.preventDefault();
    const role = document.getElementById('role_select').value;
    
    if (!role) {
        alert('Please select a role');
        return false;
    }
    
    // Redirect to appropriate creation page
    switch(role) {
        case 'superadmin':
            showConfirm(
                'Create a Super Admin account? This will have full system access.',
                'Create Super Admin',
                'Yes, Create',
                'Cancel',
                'warning'
            ).then(confirmed => {
                if (confirmed) {
                    const email = prompt('Enter email for Super Admin:');
                    const password = prompt('Enter password for Super Admin:');
                    if (email && password) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="action" value="create">
                        <input type="hidden" name="email" value="${email}">
                        <input type="hidden" name="password" value="${password}">
                        <input type="hidden" name="is_superadmin" value="1">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            }
            break;
        case 'staff':
            window.location.href = '/superadmin/staff?create_user=1';
            break;
        case 'doctor':
            window.location.href = '/superadmin/doctors?create_user=1';
            break;
        case 'patient':
            window.location.href = '/superadmin/patients?create_user=1';
            break;
    }
    
    return false;
}

function editUser(user) {
    document.getElementById('edit_id').value = user.user_id;
    document.getElementById('edit_email').value = user.user_email;
    document.getElementById('edit_password').value = '';
    
    // Determine current role
    let currentRole = 'none';
    if (user.user_is_superadmin == true || user.user_is_superadmin == 1) {
        currentRole = 'superadmin';
    } else if (user.staff_id) {
        currentRole = 'staff';
        document.getElementById('edit_role_id').value = user.staff_id;
    } else if (user.doc_id) {
        currentRole = 'doctor';
        document.getElementById('edit_role_id').value = user.doc_id;
    } else if (user.pat_id) {
        currentRole = 'patient';
        document.getElementById('edit_role_id').value = user.pat_id;
    }
    
    document.getElementById('edit_role').value = currentRole;
    toggleRoleLinkSection();
    
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

// Show/hide role link section based on selected role
document.getElementById('edit_role').addEventListener('change', toggleRoleLinkSection);

function toggleRoleLinkSection() {
    const role = document.getElementById('edit_role').value;
    const linkSection = document.getElementById('role_link_section');
    const linkLabel = document.getElementById('role_link_label');
    
    if (role === 'staff' || role === 'doctor' || role === 'patient') {
        linkSection.style.display = 'block';
        if (role === 'staff') {
            linkLabel.textContent = 'Link to Staff ID:';
        } else if (role === 'doctor') {
            linkLabel.textContent = 'Link to Doctor ID:';
        } else if (role === 'patient') {
            linkLabel.textContent = 'Link to Patient ID:';
        }
    } else {
        linkSection.style.display = 'none';
        document.getElementById('edit_role_id').value = '';
    }
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
        window.location.href = '/superadmin/users';
    } else {
        window.location.href = '/superadmin/users?role=' + category;
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
    
    <!-- Role Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('role')">
            <h4 class="filter-section-title">Role</h4>
            <button type="button" class="filter-section-toggle" id="roleToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="roleContent">
            <div class="filter-radio-group">
                <div class="filter-radio-item">
                    <input type="radio" name="filter_role" id="role_all" value="all" <?= empty($filter_role) ? 'checked' : '' ?>>
                    <label for="role_all">All</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="filter_role" id="role_superadmin" value="superadmin" <?= $filter_role === 'superadmin' ? 'checked' : '' ?>>
                    <label for="role_superadmin">Super Admin</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="filter_role" id="role_staff" value="staff" <?= $filter_role === 'staff' ? 'checked' : '' ?>>
                    <label for="role_staff">Staff</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="filter_role" id="role_doctor" value="doctor" <?= $filter_role === 'doctor' ? 'checked' : '' ?>>
                    <label for="role_doctor">Doctor</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="filter_role" id="role_patient" value="patient" <?= $filter_role === 'patient' ? 'checked' : '' ?>>
                    <label for="role_patient">Patient</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyUserFilters()">Apply all filter</button>
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
    document.getElementById('role_all').checked = true;
}

function applyUserFilters() {
    const role = document.querySelector('input[name="filter_role"]:checked')?.value;
    const search = document.querySelector('input[name="search"]')?.value || '';
    
    const params = new URLSearchParams();
    if (search) {
        params.append('search', search);
    }
    if (role && role !== 'all') {
        params.append('role', role);
    }
    
    window.location.href = '/superadmin/users' + (params.toString() ? '?' + params.toString() : '');
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
