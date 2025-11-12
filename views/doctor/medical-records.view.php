<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/doctor/appointments/today">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Medical Records</span>
        </div>
        <h1 class="page-title">Medical Records</h1>
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
                   placeholder="Search Medical Record...">
        </div>
    </form>
</div>

<!-- Add Medical Record Button -->
<div class="page-actions">
    <button type="button" class="btn btn-success" onclick="openAddMedicalRecordModal()">
        <i class="fas fa-plus"></i>
        <span>Create New Medical Record</span>
    </button>
</div>

<!-- Medical Records List -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">My Medical Records</h2>
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
                            <td><?= htmlspecialchars(substr($record['diagnosis'] ?? '', 0, 50)) ?><?= strlen($record['diagnosis'] ?? '') > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars(substr($record['treatment'] ?? '', 0, 50)) ?><?= strlen($record['treatment'] ?? '') > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars($record['follow_up_date'] ?? 'N/A') ?></td>
                            <td>
                                <div class="table-actions">
                                    <button onclick="viewRecord(<?= htmlspecialchars(json_encode($record)) ?>)" class="btn btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editRecord(<?= htmlspecialchars(json_encode($record)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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

<!-- Add Medical Record Modal -->
<div id="addModal" class="modal">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="modal-title">Create New Medical Record</h2>
            <button type="button" class="modal-close" onclick="closeAddMedicalRecordModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Patient: <span style="color: var(--status-error);">*</span></label>
                    <select name="pat_id" required class="form-control">
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['pat_id'] ?>">
                                <?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Appointment ID (Optional):</label>
                    <input type="text" name="appointment_id" placeholder="e.g., 2025-10-0000001" class="form-control">
                </div>
                <div class="form-group">
                    <label>Record Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="record_date" value="<?= date('Y-m-d') ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Follow-up Date:</label>
                    <input type="date" name="follow_up_date" class="form-control">
                </div>
            </div>
            <div class="form-group form-grid-full">
                <label>Diagnosis: <span style="color: var(--status-error);">*</span></label>
                <textarea name="diagnosis" rows="3" required class="form-control"></textarea>
            </div>
            <div class="form-group form-grid-full">
                <label>Treatment: <span style="color: var(--status-error);">*</span></label>
                <textarea name="treatment" rows="3" required class="form-control"></textarea>
            </div>
            <div class="form-group form-grid-full">
                <label>Prescription:</label>
                <textarea name="prescription" rows="3" class="form-control"></textarea>
            </div>
            <div class="form-group form-grid-full">
                <label>Notes:</label>
                <textarea name="notes" rows="2" class="form-control"></textarea>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Create Medical Record</span>
                </button>
                <button type="button" onclick="closeAddMedicalRecordModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2 class="modal-title">Edit Medical Record</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group form-grid-full">
                <label>Diagnosis: <span style="color: var(--status-error);">*</span></label>
                <textarea name="diagnosis" id="edit_diagnosis" rows="3" required class="form-control"></textarea>
            </div>
            <div class="form-group form-grid-full">
                <label>Treatment: <span style="color: var(--status-error);">*</span></label>
                <textarea name="treatment" id="edit_treatment" rows="3" required class="form-control"></textarea>
            </div>
            <div class="form-group form-grid-full">
                <label>Prescription:</label>
                <textarea name="prescription" id="edit_prescription" rows="3" class="form-control"></textarea>
            </div>
            <div class="form-group form-grid-full">
                <label>Notes:</label>
                <textarea name="notes" id="edit_notes" rows="2" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Follow-up Date:</label>
                <input type="date" name="follow_up_date" id="edit_follow_up_date" class="form-control">
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Record</span>
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
function openAddMedicalRecordModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddMedicalRecordModal() {
    document.getElementById('addModal').classList.remove('active');
    document.querySelector('#addModal form').reset();
}

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
                        <p style="margin: 0.5rem 0;"><strong>Appointment ID:</strong> ${record.appointment_id || 'N/A'}</p>
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

function editRecord(record) {
    document.getElementById('edit_id').value = record.record_id;
    document.getElementById('edit_diagnosis').value = record.diagnosis;
    document.getElementById('edit_treatment').value = record.treatment;
    document.getElementById('edit_prescription').value = record.prescription || '';
    document.getElementById('edit_notes').value = record.notes || '';
    document.getElementById('edit_follow_up_date').value = record.follow_up_date || '';
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function applyMedicalRecordFilters() {
    const filters = {
        patient: document.querySelector('input[name="filter_patient"]:checked')?.value || ''
    };
    const params = new URLSearchParams();
    if (filters.patient) params.append('patient', filters.patient);
    const url = '/doctor/medical-records' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function clearAllFilters() {
    document.querySelectorAll('.filter-sidebar input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    const patientSearch = document.getElementById('patientSearch');
    if (patientSearch) patientSearch.value = '';
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
        <button type="button" class="filter-apply-btn" onclick="applyMedicalRecordFilters()">Apply all filter</button>
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
