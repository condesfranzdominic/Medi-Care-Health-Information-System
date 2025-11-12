<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/superadmin/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Appointments</span>
        </div>
        <h1 class="page-title">Manage Appointments</h1>
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
                   placeholder="Search Appointment...">
        </div>
        <?php if (!empty($search_query)): ?>
            <a href="/superadmin/appointments" class="btn btn-sm btn-secondary">
                <i class="fas fa-times"></i>
                <span>Clear</span>
            </a>
        <?php endif; ?>
    </form>
    <div class="category-tabs">
        <button type="button" class="category-tab active" data-category="all">All</button>
        <?php if (isset($statuses)): ?>
            <?php foreach (array_slice($statuses, 0, 5) as $status): ?>
                <button type="button" class="category-tab" data-category="<?= $status['status_id'] ?>">
                    <?= htmlspecialchars($status['status_name']) ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($search_query)): ?>
    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p>Showing results for: <strong><?= htmlspecialchars($search_query) ?></strong> (<?= count($appointments) ?> result(s) found)</p>
    </div>
<?php endif; ?>

<!-- Add Appointment Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddAppointmentModal()">
        <i class="fas fa-plus"></i>
        <span>Create New Appointment</span>
    </button>
</div>

<!-- All Appointments -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Appointments</h2>
    </div>
    <?php if (empty($appointments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="empty-state-text">No appointments found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $apt): ?>
                        <tr>
                            <td><?= isset($apt['appointment_date']) ? date('M j, Y', strtotime($apt['appointment_date'])) : 'N/A' ?></td>
                            <td><?= isset($apt['appointment_time']) ? date('g:i A', strtotime($apt['appointment_time'])) : 'N/A' ?></td>
                            <td><?= htmlspecialchars(($apt['pat_first_name'] ?? '') . ' ' . ($apt['pat_last_name'] ?? '')) ?></td>
                            <td>Dr. <?= htmlspecialchars(($apt['doc_first_name'] ?? '') . ' ' . ($apt['doc_last_name'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($apt['service_name'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge" style="background: <?= htmlspecialchars($apt['status_color'] ?? '#3B82F6') ?>;">
                                    <?= htmlspecialchars($apt['status_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="editAppointment(<?= htmlspecialchars(json_encode($apt)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="viewAppointmentDetails(<?= htmlspecialchars(json_encode($apt)) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $apt['appointment_id'] ?>">
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
                Showing <?= $offset + 1 ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> appointments
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Add Appointment Modal -->
<div id="addModal" class="modal">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="modal-title">Create New Appointment</h2>
            <button type="button" class="modal-close" onclick="closeAddAppointmentModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Patient: <span style="color: var(--status-error);">*</span></label>
                    <select name="patient_id" required class="form-control">
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['pat_id'] ?>"><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doctor: <span style="color: var(--status-error);">*</span></label>
                    <select name="doctor_id" required class="form-control">
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor['doc_id'] ?>"><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Service:</label>
                    <select name="service_id" class="form-control">
                        <option value="">Select Service (Optional)</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['service_id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="appointment_date" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Time:</label>
                    <input type="time" name="appointment_time" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status_id" class="form-control">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Duration (Minutes):</label>
                    <input type="number" name="duration" min="1" value="30" class="form-control">
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Notes:</label>
                <textarea name="notes" rows="3" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Create Appointment</span>
                </button>
                <button type="button" onclick="closeAddAppointmentModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Appointment</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Patient: <span style="color: var(--status-error);">*</span></label>
                    <select name="patient_id" id="edit_patient_id" required class="form-control">
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['pat_id'] ?>"><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doctor: <span style="color: var(--status-error);">*</span></label>
                    <select name="doctor_id" id="edit_doctor_id" required class="form-control">
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor['doc_id'] ?>"><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Service:</label>
                    <select name="service_id" id="edit_service_id" class="form-control">
                        <option value="">Select Service (Optional)</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['service_id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="appointment_date" id="edit_appointment_date" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Time:</label>
                    <input type="time" name="appointment_time" id="edit_appointment_time" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status_id" id="edit_status_id" class="form-control">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Duration (Minutes):</label>
                    <input type="number" name="duration" id="edit_duration" min="1" class="form-control">
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Notes:</label>
                <textarea name="notes" id="edit_notes" rows="3" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Appointment</span>
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
function openAddAppointmentModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddAppointmentModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
}

function editAppointment(apt) {
    document.getElementById('edit_id').value = apt.appointment_id;
    document.getElementById('edit_patient_id').value = apt.pat_id || '';
    document.getElementById('edit_doctor_id').value = apt.doc_id || '';
    document.getElementById('edit_service_id').value = apt.service_id || '';
    document.getElementById('edit_appointment_date').value = apt.appointment_date || '';
    document.getElementById('edit_appointment_time').value = apt.appointment_time || '';
    document.getElementById('edit_status_id').value = apt.status_id || '';
    document.getElementById('edit_duration').value = apt.appointment_duration_minutes || 30;
    document.getElementById('edit_notes').value = apt.appointment_notes || '';
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function viewAppointmentDetails(appointment) {
    window.location.href = '/superadmin/appointments?view=' + appointment.appointment_id;
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
        window.location.href = '/superadmin/appointments';
    } else {
        window.location.href = '/superadmin/appointments?status_id=' + category;
    }
}

// Listen for filter events
window.addEventListener('filtersApplied', function(e) {
    const filters = e.detail;
    console.log('Applying filters:', filters);
    // Implement filter logic
});
</script>

<!-- Filter Sidebar -->
<div class="filter-sidebar" id="filterSidebar">
    <div class="filter-sidebar-header">
        <h3 class="filter-sidebar-title">Filters</h3>
        <button type="button" class="filter-sidebar-close" onclick="toggleFilterSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Status Filter -->
    <?php if (!empty($statuses)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('status')">
            <h4 class="filter-section-title">Status</h4>
            <button type="button" class="filter-section-toggle" id="statusToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="statusContent">
            <div class="filter-radio-group">
                <?php foreach ($statuses as $status): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_status" id="status_<?= $status['status_id'] ?>" value="<?= $status['status_id'] ?>">
                        <label for="status_<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
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
    
    <!-- Patient Filter -->
    <?php if (!empty($filter_patients)): ?>
    <div class="filter-section">
        <div class="filter-section-header" onclick="toggleFilterSection('patient')">
            <h4 class="filter-section-title">Patient</h4>
            <button type="button" class="filter-section-toggle" id="patientToggle">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="filter-section-content" id="patientContent">
            <input type="text" class="filter-search-input" placeholder="Search Patient" id="patientSearch">
            <div class="filter-radio-group" id="patientList">
                <?php foreach ($filter_patients as $patient): ?>
                    <div class="filter-radio-item">
                        <input type="radio" name="filter_patient" id="patient_<?= $patient['pat_id'] ?>" value="<?= $patient['pat_id'] ?>">
                        <label for="patient_<?= $patient['pat_id'] ?>"><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Filter Actions -->
    <div class="filter-sidebar-actions">
        <button type="button" class="filter-clear-btn" onclick="clearAllFilters()">Clear all</button>
        <button type="button" class="filter-apply-btn" onclick="applyAppointmentFilters()">Apply all filter</button>
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

function clearAllFilters() {
    document.querySelectorAll('.filter-sidebar input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    const doctorSearch = document.getElementById('doctorSearch');
    const patientSearch = document.getElementById('patientSearch');
    if (doctorSearch) doctorSearch.value = '';
    if (patientSearch) patientSearch.value = '';
}

function applyAppointmentFilters() {
    const filters = {
        status: document.querySelector('input[name="filter_status"]:checked')?.value || '',
        doctor: document.querySelector('input[name="filter_doctor"]:checked')?.value || '',
        patient: document.querySelector('input[name="filter_patient"]:checked')?.value || ''
    };
    
    const params = new URLSearchParams();
    if (filters.status) params.append('status', filters.status);
    if (filters.doctor) params.append('doctor', filters.doctor);
    if (filters.patient) params.append('patient', filters.patient);
    
    const url = '/superadmin/appointments' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
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
    
    const patientSearch = document.getElementById('patientSearch');
    if (patientSearch) {
        patientSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const patientItems = document.querySelectorAll('#patientList .filter-radio-item');
            patientItems.forEach(item => {
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
