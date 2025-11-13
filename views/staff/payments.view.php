<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/staff/dashboard">
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
                                    <button onclick="viewPayment(<?= htmlspecialchars(json_encode($payment)) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editPayment(<?= htmlspecialchars(json_encode($payment)) ?>)" class="btn btn-sm" title="Edit">
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
function openAddPaymentModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddPaymentModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
}

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
        window.location.href = '/staff/payments';
    } else {
        window.location.href = '/staff/payments?status=' + category;
    }
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});

function applyPaymentFilters() {
    const filters = {
        status: document.querySelector('input[name="filter_status"]:checked')?.value || '',
        method: document.querySelector('input[name="filter_method"]:checked')?.value || ''
    };
    const params = new URLSearchParams();
    if (filters.status) params.append('status', filters.status);
    if (filters.method) params.append('method', filters.method);
    const url = '/staff/payments' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function clearAllFilters() {
    document.querySelectorAll('.filter-sidebar input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    const methodSearch = document.getElementById('methodSearch');
    if (methodSearch) methodSearch.value = '';
}
</script>

<!-- Filter Sidebar -->
<div class="filter-sidebar" id="filterSidebar">
    <div class="filter-sidebar-header">
        <h3>Filters</h3>
        <button type="button" class="filter-sidebar-close" onclick="toggleFilterSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Payment Status Filter -->
    <?php if (!empty($payment_statuses)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('status')">
            <h4 class="filter-section-title">Payment Status</h4>
            <button type="button" class="filter-section-toggle" id="statusToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="statusContent">
            <div class="filter-radio-group">
                <?php foreach ($payment_statuses as $status): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_status" id="status_<?= $status['payment_status_id'] ?>" value="<?= $status['payment_status_id'] ?>">
                        <label for="status_<?= $status['payment_status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Payment Method Filter -->
    <?php if (!empty($filter_methods)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('method')">
            <h4 class="filter-section-title">Payment Method</h4>
            <button type="button" class="filter-section-toggle" id="methodToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="methodContent">
            <input type="text" class="filter-search-input" placeholder="Search Method" id="methodSearch">
            <div class="filter-radio-group" id="methodList">
                <?php foreach ($filter_methods as $method): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_method" id="method_<?= $method['method_id'] ?>" value="<?= $method['method_id'] ?>">
                        <label for="method_<?= $method['method_id'] ?>"><?= htmlspecialchars($method['method_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyPaymentFilters()">Apply all filter</button>
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

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const methodSearch = document.getElementById('methodSearch');
    if (methodSearch) {
        methodSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const methodItems = document.querySelectorAll('#methodList .filter-radio-item');
            methodItems.forEach(item => {
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
