<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/staff/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Medical Records</span>
        </div>
        <h1 class="page-title">Medical Records (View Only)</h1>
    </div>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
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
                   placeholder="Search Medical Record...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <button type="button" class="category-tab" data-category="recent">Recent</button>
        <button type="button" class="category-tab" data-category="followup">Follow-up</button>
    </div>
</div>

<div class="info-box">
    <i class="fas fa-info-circle"></i>
    <p><strong>Note:</strong> Staff can view medical records but cannot create, edit, or delete them. Only doctors can create/edit records, and only superadmin can delete them.</p>
</div>

<!-- Medical Records List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Medical Records</h2>
    </div>
    <?php if (empty($records)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-file-medical"></i></div>
            <div class="empty-state-text">No medical records found.</div>
        </div>
    <?php else: ?>
        <p style="margin: 0 1.5rem 1rem 1.5rem; color: var(--text-secondary); font-size: 0.875rem;">Total: <?= count($records) ?> records</p>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($record['record_id']) ?></strong></td>
                            <td><?= htmlspecialchars($record['record_date']) ?></td>
                            <td><?= htmlspecialchars($record['pat_first_name'] . ' ' . $record['pat_last_name']) ?></td>
                            <td>Dr. <?= htmlspecialchars($record['doc_first_name'] . ' ' . $record['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars(substr($record['diagnosis'] ?? '', 0, 50)) ?><?= strlen($record['diagnosis'] ?? '') > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars(substr($record['treatment'] ?? '', 0, 50)) ?><?= strlen($record['treatment'] ?? '') > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars($record['follow_up_date'] ?? 'N/A') ?></td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="viewRecord(<?= htmlspecialchars(json_encode($record)) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
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

<!-- View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2 class="modal-title">Medical Record Details</h2>
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

<script>
function viewRecord(record) {
    const content = `
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Record Information</h3>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Record ID:</strong> ${record.record_id}</p>
                        <p style="margin: 0.5rem 0;"><strong>Date:</strong> ${record.record_date}</p>
                    </div>
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Patient:</strong> ${record.pat_first_name} ${record.pat_last_name}</p>
                        <p style="margin: 0.5rem 0;"><strong>Doctor:</strong> Dr. ${record.doc_first_name} ${record.doc_last_name}</p>
                    </div>
                    <div>
                        <p style="margin: 0.5rem 0;"><strong>Appointment ID:</strong> ${record.appointment_id || 'N/A'}</p>
                        <p style="margin: 0.5rem 0;"><strong>Appointment Date:</strong> ${record.appointment_date || 'N/A'}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title" style="color: var(--primary-blue);">Diagnosis</h3>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap; margin: 0;">${record.diagnosis || 'N/A'}</p>
            </div>
        </div>
        
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title" style="color: var(--primary-blue);">Treatment</h3>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap; margin: 0;">${record.treatment || 'N/A'}</p>
            </div>
        </div>
        
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title" style="color: var(--primary-blue);">Prescription</h3>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap; margin: 0;">${record.prescription || 'None'}</p>
            </div>
        </div>
        
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title" style="color: var(--primary-blue);">Notes</h3>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap; margin: 0;">${record.notes || 'None'}</p>
            </div>
        </div>
        
        <div class="info-box">
            <i class="fas fa-calendar-check"></i>
            <p><strong>Follow-up Date:</strong> ${record.follow_up_date || 'Not scheduled'}</p>
        </div>
    `;
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').classList.add('active');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('active');
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
        window.location.href = '/staff/medical-records';
    } else {
        window.location.href = '/staff/medical-records?filter=' + category;
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
