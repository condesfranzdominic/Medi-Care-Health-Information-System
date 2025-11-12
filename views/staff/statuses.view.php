<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/staff/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Statuses</span>
        </div>
        <h1 class="page-title">Manage Appointment Statuses</h1>
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
                   placeholder="Search Status...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
    </div>
</div>

<!-- Create New Status -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Add New Status</h2>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Status Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="status_name" required placeholder="e.g., Scheduled, Completed, Cancelled" class="form-control">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="status_description" placeholder="Brief description" class="form-control">
                </div>
                <div class="form-group">
                    <label>Color:</label>
                    <input type="color" name="status_color" value="#3B82F6" style="width: 100%; height: 42px; cursor: pointer; border: 1px solid var(--border-medium); border-radius: var(--radius-md);">
                </div>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Status</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statuses List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Appointment Statuses</h2>
    </div>
    <div class="info-box" style="margin: 1.5rem;">
        <i class="fas fa-info-circle"></i>
        <p><strong>Note:</strong> Only Super Admin can delete statuses.</p>
    </div>
    <?php if (empty($statuses)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="empty-state-text">No statuses found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status Name</th>
                        <th>Description</th>
                        <th>Preview</th>
                        <th>Appointments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statuses as $status): ?>
                        <tr>
                            <td><?= htmlspecialchars($status['status_id']) ?></td>
                            <td><strong><?= htmlspecialchars($status['status_name']) ?></strong></td>
                            <td><?= htmlspecialchars($status['status_description'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge" style="background: <?= htmlspecialchars($status['status_color']) ?>; color: white;">
                                    <?= htmlspecialchars($status['status_name']) ?>
                                </span>
                            </td>
                            <td><?= isset($status['appointment_count']) ? $status['appointment_count'] : 0 ?> appointment(s)</td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editStatus(<?= htmlspecialchars(json_encode($status)) ?>)" class="btn btn-sm" title="Edit">
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

<!-- Edit Status Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Status</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Status Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="status_name" id="edit_status_name" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="status_description" id="edit_status_description" class="form-control">
                </div>
                <div class="form-group">
                    <label>Color:</label>
                    <input type="color" name="status_color" id="edit_status_color" style="width: 100%; height: 42px; cursor: pointer; border: 1px solid var(--border-medium); border-radius: var(--radius-md);">
                </div>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Status</span>
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
function editStatus(status) {
    document.getElementById('edit_id').value = status.status_id;
    document.getElementById('edit_status_name').value = status.status_name;
    document.getElementById('edit_status_description').value = status.status_description || '';
    document.getElementById('edit_status_color').value = status.status_color || '#3B82F6';
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
        window.location.href = '/staff/statuses';
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
