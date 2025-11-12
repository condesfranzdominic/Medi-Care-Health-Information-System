<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Payment Statuses</span>
        </div>
        <h1 class="page-title">Manage Payment Statuses</h1>
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
                   placeholder="Search Payment Status...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
    </div>
</div>

<!-- Add Payment Status Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddPaymentStatusModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Payment Status</span>
    </button>
</div>

<!-- Payment Statuses List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Payment Statuses</h2>
    </div>
    <?php if (empty($payment_statuses)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-coins"></i></div>
            <div class="empty-state-text">No payment statuses found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status Name</th>
                        <th>Description</th>
                        <th>Payments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payment_statuses as $status): ?>
                        <tr>
                            <td><?= htmlspecialchars($status['payment_status_id']) ?></td>
                            <td><strong><?= htmlspecialchars($status['status_name']) ?></strong></td>
                            <td><?= htmlspecialchars($status['status_description'] ?? 'N/A') ?></td>
                            <td><?= isset($status['payment_count']) ? $status['payment_count'] : 0 ?> payment(s)</td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editStatus(<?= htmlspecialchars(json_encode($status)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will affect <?= isset($status['payment_count']) ? $status['payment_count'] : 0 ?> payment(s).');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $status['payment_status_id'] ?>">
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

<!-- Add Payment Status Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Payment Status</h2>
            <button type="button" class="modal-close" onclick="closeAddPaymentStatusModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Status Name: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="status_name" required placeholder="e.g., Paid, Pending, Refunded" class="form-control">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="status_description" placeholder="Brief description" class="form-control">
                </div>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Payment Status</span>
                </button>
                <button type="button" onclick="closeAddPaymentStatusModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Status Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Payment Status</h2>
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
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Payment Status</span>
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
function openAddPaymentStatusModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddPaymentStatusModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
}

function editStatus(status) {
    document.getElementById('edit_id').value = status.payment_status_id;
    document.getElementById('edit_status_name').value = status.status_name;
    document.getElementById('edit_status_description').value = status.status_description || '';
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
        window.location.href = '/superadmin/payment-statuses';
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
