<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/staff/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Staff</span>
        </div>
        <h1 class="page-title">Manage Staff</h1>
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
                   placeholder="Search Staff...">
        </div>
        <?php if (!empty($search_query)): ?>
            <a href="/staff/staff" class="btn btn-sm btn-secondary">
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
        <p>Found <?= count($staff_members) ?> result(s) for "<?= htmlspecialchars($search_query) ?>"</p>
    </div>
<?php endif; ?>

<!-- Add Staff Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddStaffModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Staff Member</span>
    </button>
</div>

<!-- Staff List -->
                    <input type="number" name="salary" step="0.01" min="0" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Staff Member</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Staff List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?= !empty($search_query) ? 'Search Results' : 'All Staff Members' ?></h2>
    </div>
    <div class="info-box" style="margin: 1.5rem;">
        <i class="fas fa-info-circle"></i>
        <p><strong>Note:</strong> Only Super Admin can delete staff members.</p>
    </div>
    <?php if (empty($staff_members)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-user-tie"></i></div>
            <div class="empty-state-text"><?= !empty($search_query) ? 'No staff members found matching your search.' : 'No staff members found.' ?></div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Hire Date</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff_members as $staff): ?>
                        <tr>
                            <td><?= htmlspecialchars($staff['staff_id']) ?></td>
                            <td><strong><?= htmlspecialchars($staff['staff_first_name'] . ' ' . $staff['staff_last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($staff['staff_email']) ?></td>
                            <td><?= htmlspecialchars($staff['staff_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($staff['staff_position'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($staff['staff_hire_date'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($staff['staff_salary'] ?? 0, 2) ?></td>
                            <td>
                                <span class="status-badge <?= ($staff['staff_status'] ?? 'active') === 'active' ? 'active' : 'inactive' ?>">
                                    <?= ucfirst($staff['staff_status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editStaff(<?= htmlspecialchars(json_encode($staff)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-controls">
                <button class="pagination-btn" disabled>
                    <i class="fas fa-angle-double-left"></i>
                </button>
                <button class="pagination-btn" disabled>
                    <i class="fas fa-angle-left"></i>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">
                    <i class="fas fa-angle-right"></i>
                </button>
                <button class="pagination-btn">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Edit Staff Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Staff Member</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
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
                    <label>Position:</label>
                    <input type="text" name="position" id="edit_position" class="form-control">
                </div>
                <div class="form-group">
                    <label>Hire Date:</label>
                    <input type="date" name="hire_date" id="edit_hire_date" class="form-control">
                </div>
                <div class="form-group">
                    <label>Salary:</label>
                    <input type="number" name="salary" id="edit_salary" step="0.01" min="0" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Staff Member</span>
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
function editStaff(staff) {
    document.getElementById('edit_id').value = staff.staff_id;
    document.getElementById('edit_first_name').value = staff.staff_first_name;
    document.getElementById('edit_last_name').value = staff.staff_last_name;
    document.getElementById('edit_email').value = staff.staff_email;
    document.getElementById('edit_phone').value = staff.staff_phone || '';
    document.getElementById('edit_position').value = staff.staff_position || '';
    document.getElementById('edit_hire_date').value = staff.staff_hire_date || '';
    document.getElementById('edit_salary').value = staff.staff_salary || '';
    document.getElementById('edit_status').value = staff.staff_status || 'active';
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
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
        window.location.href = '/staff/staff';
    } else {
        window.location.href = '/staff/staff?status=' + category;
    }
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});
</script>

<?php require_once __DIR__ . '/../partials/filter-sidebar.php'; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
