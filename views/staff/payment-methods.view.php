<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/staff/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Payment Methods</span>
        </div>
        <h1 class="page-title">Manage Payment Methods</h1>
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
                   placeholder="Search Payment Method...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <button type="button" class="category-tab" data-category="active">Active</button>
        <button type="button" class="category-tab" data-category="inactive">Inactive</button>
    </div>
</div>

<!-- Add Payment Method Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddPaymentMethodModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Payment Method</span>
    </button>
</div>

<!-- Payment Methods List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Payment Methods</h2>
    </div>
    <div class="info-box" style="margin: 1.5rem;">
        <i class="fas fa-info-circle"></i>
        <p><strong>Note:</strong> Only Super Admin can delete payment methods.</p>
    </div>
    <?php if (empty($payment_methods)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-credit-card"></i></div>
            <div class="empty-state-text">No payment methods found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Method Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Payments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payment_methods as $method): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($method['method_name']) ?></strong></td>
                            <td><?= htmlspecialchars($method['method_description'] ?? 'N/A') ?></td>
                            <td>
                                <span class="status-badge <?= $method['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $method['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td><?= isset($method['payment_count']) ? $method['payment_count'] : 0 ?> payment(s)</td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editMethod(<?= htmlspecialchars(json_encode($method)) ?>)" class="btn btn-sm" title="Edit">
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

<!-- Add Payment Method Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Payment Method</h2>
            <button type="button" class="modal-close" onclick="closeAddPaymentMethodModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Method Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="method_name" required placeholder="e.g., Cash, Credit Card, Mobile Payment" class="form-control">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="method_description" placeholder="Brief description" class="form-control">
                </div>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: auto;">
                    <span>Active (available for use)</span>
                </label>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Payment Method</span>
                </button>
                <button type="button" onclick="closeAddPaymentMethodModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Method Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Payment Method</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Method Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="method_name" id="edit_method_name" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="method_description" id="edit_method_description" class="form-control">
                </div>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width: auto;">
                    <span>Active (available for use)</span>
                </label>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Payment Method</span>
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
function openAddPaymentMethodModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddPaymentMethodModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
    document.querySelector('#addModal input[name="is_active"]').checked = true;
}

function editMethod(method) {
    document.getElementById('edit_id').value = method.method_id;
    document.getElementById('edit_method_name').value = method.method_name;
    document.getElementById('edit_method_description').value = method.method_description || '';
    document.getElementById('edit_is_active').checked = method.is_active == 1 || method.is_active === true;
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
        window.location.href = '/staff/payment-methods';
    } else {
        window.location.href = '/staff/payment-methods?status=' + category;
    }
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
