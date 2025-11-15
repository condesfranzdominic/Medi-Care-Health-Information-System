<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Payments</span>
        </div>
        <h1 class="page-title">Manage Payments</h1>
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
                   placeholder="Search Payment...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <?php if (isset($payment_statuses)): ?>
            <?php foreach (array_slice($payment_statuses, 0, 4) as $status): ?>
                <button type="button" class="category-tab" data-category="<?= $status['payment_status_id'] ?>">
                    <?= htmlspecialchars($status['status_name']) ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add Payment Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddPaymentModal()">
        <i class="fas fa-plus"></i>
        <span>Add New Payment Record</span>
    </button>
</div>

<!-- Payments List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Payment Records</h2>
    </div>
    <?php if (empty($payments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="empty-state-text">No payment records found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                            <td><?= htmlspecialchars($payment['appointment_id']) ?></td>
                            <td><?= htmlspecialchars(($payment['pat_first_name'] ?? '') . ' ' . ($payment['pat_last_name'] ?? '')) ?></td>
                            <td><strong style="color: var(--status-success);">₱<?= number_format($payment['amount'], 2) ?></strong></td>
                            <td><?= htmlspecialchars($payment['method_name'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge" style="background: var(--primary-blue);">
                                    <?= htmlspecialchars($payment['status_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="viewPayment(<?= json_encode($payment) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editPayment(<?= json_encode($payment) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return handleDelete(event, 'Are you sure you want to delete this payment record?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $payment['payment_id'] ?>">
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
                Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> payments
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Add Payment Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Payment Record</h2>
            <button type="button" class="modal-close" onclick="closeAddPaymentModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Appointment ID: <span style="color: var(--status-error);">*</span></label>
                    <input type="text" name="appointment_id" required placeholder="e.g., 2025-10-0000001" class="form-control">
                </div>
                <div class="form-group">
                    <label>Amount (₱): <span style="color: var(--status-error);">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Payment Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Payment Method: <span style="color: var(--status-error);">*</span></label>
                    <select name="payment_method_id" required class="form-control">
                        <option value="">Select Method</option>
                        <?php foreach ($payment_methods as $method): ?>
                            <option value="<?= $method['method_id'] ?>"><?= htmlspecialchars($method['method_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Status: <span style="color: var(--status-error);">*</span></label>
                    <select name="payment_status_id" required class="form-control">
                        <option value="">Select Status</option>
                        <?php foreach ($payment_statuses as $status): ?>
                            <option value="<?= $status['payment_status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Notes:</label>
                <textarea name="notes" rows="2" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Payment Record</span>
                </button>
                <button type="button" onclick="closeAddPaymentModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Payment Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Payment Details</h2>
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

<!-- Edit Payment Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Payment Record</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Amount (₱): <span style="color: var(--status-error);">*</span></label>
                    <input type="number" name="amount" id="edit_amount" step="0.01" min="0" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Payment Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="payment_date" id="edit_payment_date" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Payment Method: <span style="color: var(--status-error);">*</span></label>
                    <select name="payment_method_id" id="edit_payment_method_id" required class="form-control">
                        <option value="">Select Method</option>
                        <?php foreach ($payment_methods as $method): ?>
                            <option value="<?= $method['method_id'] ?>"><?= htmlspecialchars($method['method_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Status: <span style="color: var(--status-error);">*</span></label>
                    <select name="payment_status_id" id="edit_payment_status_id" required class="form-control">
                        <option value="">Select Status</option>
                        <?php foreach ($payment_statuses as $status): ?>
                            <option value="<?= $status['payment_status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Notes:</label>
                <textarea name="notes" id="edit_notes" rows="2" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Payment</span>
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
function viewPayment(payment) {
    const content = `
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
                <div class="form-grid">
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Payment ID:</strong> ${payment.payment_id}</p>
                        <p style="margin: 0.5rem 0;"><strong>Appointment ID:</strong> ${payment.appointment_id}</p>
                    </div>
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Patient:</strong> ${payment.pat_first_name || ''} ${payment.pat_last_name || ''}</p>
                        <p style="margin: 0.5rem 0;"><strong>Amount:</strong> <strong style="color: var(--status-success);">₱${parseFloat(payment.amount).toFixed(2)}</strong></p>
                    </div>
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Payment Date:</strong> ${payment.payment_date}</p>
                        <p style="margin: 0.5rem 0;"><strong>Payment Method:</strong> ${payment.method_name || 'N/A'}</p>
                    </div>
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Payment Status:</strong> 
                            <span class="badge" style="background: var(--primary-blue);">${payment.status_name || 'N/A'}</span>
                        </p>
                    </div>
                </div>
                ${payment.notes ? `<div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);"><p style="margin: 0;"><strong>Notes:</strong> ${payment.notes}</p></div>` : ''}
            </div>
        </div>
    `;
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').classList.add('active');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('active');
}

function openAddPaymentModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddPaymentModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
}

function editPayment(payment) {
    document.getElementById('edit_id').value = payment.payment_id;
    document.getElementById('edit_amount').value = payment.amount;
    document.getElementById('edit_payment_date').value = payment.payment_date;
    document.getElementById('edit_payment_method_id').value = payment.payment_method_id;
    document.getElementById('edit_payment_status_id').value = payment.payment_status_id;
    document.getElementById('edit_notes').value = payment.notes || '';
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
        window.location.href = '/superadmin/payments';
    } else {
        window.location.href = '/superadmin/payments?status_id=' + category;
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
