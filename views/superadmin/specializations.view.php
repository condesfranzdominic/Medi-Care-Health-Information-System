<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Specializations</span>
        </div>
        <h1 class="page-title">Manage Specializations</h1>
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
                   placeholder="Search Specialization...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
    </div>
</div>

<!-- Create New Specialization -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Add New Specialization</h2>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>Specialization Name: <span style="color: var(--status-error);">*</span></label>
                <input type="text" name="spec_name" required placeholder="e.g., Family Medicine, Cardiology" class="form-control">
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="spec_description" rows="3" placeholder="Brief description of this specialization" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Specialization</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Specializations List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Specializations</h2>
    </div>
    <?php if (empty($specializations)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="empty-state-text">No specializations found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Specialization Name</th>
                        <th>Description</th>
                        <th>Doctors</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specializations as $spec): ?>
                        <tr>
                            <td><?= htmlspecialchars($spec['spec_id']) ?></td>
                            <td><strong><?= htmlspecialchars($spec['spec_name']) ?></strong></td>
                            <td><?= htmlspecialchars($spec['spec_description'] ?? 'N/A') ?></td>
                            <td>
                                <?php if (isset($spec['doctor_count']) && $spec['doctor_count'] > 0): ?>
                                    <a href="/superadmin/doctors?spec_id=<?= $spec['spec_id'] ?>" class="btn btn-sm">
                                        <i class="fas fa-user-md"></i>
                                        <span><?= $spec['doctor_count'] ?> Doctor(s)</span>
                                    </a>
                                <?php else: ?>
                                    <span style="color: var(--text-secondary);">0 Doctors</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editSpecialization(<?= htmlspecialchars(json_encode($spec)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $spec['spec_id'] ?>">
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

<!-- Edit Specialization Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Specialization</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Specialization Name: <span style="color: var(--status-error);">*</span></label>
                <input type="text" name="spec_name" id="edit_spec_name" required class="form-control">
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="spec_description" id="edit_spec_description" rows="3" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Specialization</span>
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
function editSpecialization(spec) {
    document.getElementById('edit_id').value = spec.spec_id;
    document.getElementById('edit_spec_name').value = spec.spec_name;
    document.getElementById('edit_spec_description').value = spec.spec_description || '';
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
        window.location.href = '/superadmin/specializations';
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
