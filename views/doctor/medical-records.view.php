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

<!-- Create New Medical Record -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Create New Medical Record</h2>
    </div>
    <div class="card-body">
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
            </div>
        </form>
    </div>
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
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
