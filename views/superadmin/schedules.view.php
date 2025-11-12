<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Schedules</span>
        </div>
        <h1 class="page-title">Manage All Doctor Schedules</h1>
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
                   placeholder="Search Schedule...">
        </div>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <button type="button" class="category-tab" data-category="today">Today</button>
        <button type="button" class="category-tab" data-category="upcoming">Upcoming</button>
        <button type="button" class="category-tab" data-category="available">Available</button>
    </div>
</div>

<!-- Today's Schedules -->
<?php if (!empty($today_schedules)): ?>
    <div class="card" style="border-left: 4px solid var(--primary-blue);">
        <div class="card-header">
            <h2 class="card-title">Today's Schedules (<?= date('F d, Y') ?>)</h2>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Max Appointments</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($today_schedules as $sched): ?>
                        <tr>
                            <td><strong>Dr. <?= htmlspecialchars($sched['doc_first_name'] . ' ' . $sched['doc_last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($sched['spec_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($sched['start_time']) ?></td>
                            <td><?= htmlspecialchars($sched['end_time']) ?></td>
                            <td><?= htmlspecialchars($sched['max_appointments']) ?></td>
                            <td>
                                <span class="status-badge <?= $sched['is_available'] ? 'active' : 'inactive' ?>">
                                    <?= $sched['is_available'] ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- All Schedules -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Doctor Schedules</h2>
    </div>
    <div class="info-box" style="margin: 1.5rem;">
        <i class="fas fa-info-circle"></i>
        <p><strong>Note:</strong> Schedules are created and managed by doctors. As superadmin, you can view and delete schedules.</p>
    </div>
    <?php if (empty($schedules)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="empty-state-text">No schedules found.</div>
        </div>
    <?php else: ?>
        <p style="margin: 0 1.5rem 1rem 1.5rem; color: var(--text-secondary); font-size: 0.875rem;">Total: <?= count($schedules) ?> schedules</p>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Max Appointments</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $sched): ?>
                        <tr>
                            <td><?= htmlspecialchars($sched['schedule_id']) ?></td>
                            <td><strong><?= htmlspecialchars($sched['schedule_date']) ?></strong></td>
                            <td>Dr. <?= htmlspecialchars($sched['doc_first_name'] . ' ' . $sched['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars($sched['spec_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($sched['start_time']) ?></td>
                            <td><?= htmlspecialchars($sched['end_time']) ?></td>
                            <td><?= htmlspecialchars($sched['max_appointments']) ?></td>
                            <td>
                                <span class="status-badge <?= $sched['is_available'] ? 'active' : 'inactive' ?>">
                                    <?= $sched['is_available'] ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $sched['schedule_id'] ?>">
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

<script>
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
        window.location.href = '/superadmin/schedules';
    } else {
        window.location.href = '/superadmin/schedules?filter=' + category;
    }
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});

function applyScheduleFilters() {
    const filters = {
        doctor: document.querySelector('input[name="filter_doctor"]:checked')?.value || '',
        available: document.querySelector('input[name="filter_available"]:checked')?.value || ''
    };
    const params = new URLSearchParams();
    if (filters.doctor) params.append('doctor', filters.doctor);
    if (filters.available) params.append('available', filters.available);
    const url = '/superadmin/schedules' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function clearAllFilters() {
    document.querySelectorAll('.filter-sidebar input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    const doctorSearch = document.getElementById('doctorSearch');
    if (doctorSearch) doctorSearch.value = '';
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
    
    <!-- Doctor Filter -->
    <?php if (!empty($filter_doctors)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('doctor')">
            <h4 class="filter-section-title">Doctor</h4>
            <button type="button" class="filter-section-toggle" id="doctorToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="doctorContent">
            <input type="text" class="filter-search-input" placeholder="Search Doctor" id="doctorSearch">
            <div class="filter-radio-group" id="doctorList">
                <?php foreach ($filter_doctors as $doctor): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_doctor" id="doctor_<?= $doctor['doc_id'] ?>" value="<?= $doctor['doc_id'] ?>">
                        <label for="doctor_<?= $doctor['doc_id'] ?>">Dr. <?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Availability Filter -->
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('available')">
            <h4 class="filter-section-title">Availability</h4>
            <button type="button" class="filter-section-toggle" id="availableToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="availableContent">
            <div class="filter-radio-group">
                <div class="filter-radio-item">
                    <input type="radio" name="filter_available" id="available_yes" value="yes">
                    <label for="available_yes">Available</label>
                </div>
                <div class="filter-radio-item">
                    <input type="radio" name="filter_available" id="available_no" value="no">
                    <label for="available_no">Not Available</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyScheduleFilters()">Apply all filter</button>
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
    const doctorSearch = document.getElementById('doctorSearch');
    if (doctorSearch) {
        doctorSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const doctorItems = document.querySelectorAll('#doctorList .filter-radio-item');
            doctorItems.forEach(item => {
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
