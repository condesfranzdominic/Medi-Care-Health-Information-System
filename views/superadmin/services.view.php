<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Services</span>
        </div>
        <h1 class="page-title">Manage Services</h1>
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
                   placeholder="Search Service...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
    </div>
</div>

<!-- Add Service Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddServiceModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Service</span>
    </button>
</div>

<!-- Services List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Services</h2>
    </div>
    <?php if (empty($services)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-flask"></i></div>
            <div class="empty-state-text">No services found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['service_id']) ?></td>
                            <td><strong><?= htmlspecialchars($service['service_name']) ?></strong></td>
                            <td><?= htmlspecialchars($service['service_description'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($service['service_price'] ?? 0, 2) ?></td>
                            <td><?= htmlspecialchars($service['service_duration_minutes'] ?? 30) ?> min</td>
                            <td><?= htmlspecialchars($service['service_category'] ?? 'N/A') ?></td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editService(<?= htmlspecialchars(json_encode($service)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $service['service_id'] ?>">
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

<!-- Add Service Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Service</h2>
            <button type="button" class="modal-close" onclick="closeAddServiceModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Service Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="service_name" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <input type="text" name="category" class="form-control">
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" name="price" step="0.01" min="0" value="0" class="form-control">
                </div>
                <div class="form-group">
                    <label>Duration (Minutes):</label>
                    <input type="number" name="duration" min="1" value="30" class="form-control">
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Description:</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Service</span>
                </button>
                <button type="button" onclick="closeAddServiceModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Service Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Service</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Service Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="service_name" id="edit_service_name" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <input type="text" name="category" id="edit_category" class="form-control">
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" name="price" id="edit_price" step="0.01" min="0" class="form-control">
                </div>
                <div class="form-group">
                    <label>Duration (Minutes):</label>
                    <input type="number" name="duration" id="edit_duration" min="1" class="form-control">
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Description:</label>
                <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Service</span>
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
function openAddServiceModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddServiceModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
}

function editService(service) {
    document.getElementById('edit_id').value = service.service_id;
    document.getElementById('edit_service_name').value = service.service_name;
    document.getElementById('edit_description').value = service.service_description || '';
    document.getElementById('edit_price').value = service.service_price || 0;
    document.getElementById('edit_duration').value = service.service_duration_minutes || 30;
    document.getElementById('edit_category').value = service.service_category || '';
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
        window.location.href = '/superadmin/services';
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
