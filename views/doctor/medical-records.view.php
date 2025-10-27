<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Medical Records</h1>
    <p><a href="/doctor/appointments/today" class="btn">← Back to Dashboard</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="background: #efe; color: #3c3; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <!-- Create New Medical Record -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Create New Medical Record</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <div class="form-group">
                    <label>Patient: *</label>
                    <select name="pat_id" required>
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
                    <input type="text" name="appointment_id" placeholder="e.g., 2025-10-0000001">
                </div>
                <div class="form-group">
                    <label>Record Date: *</label>
                    <input type="date" name="record_date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Follow-up Date:</label>
                    <input type="date" name="follow_up_date">
                </div>
            </div>
            <div class="form-group">
                <label>Diagnosis: *</label>
                <textarea name="diagnosis" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Treatment: *</label>
                <textarea name="treatment" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Prescription:</label>
                <textarea name="prescription" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="notes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Create Medical Record</button>
        </form>
    </div>
    
    <!-- Medical Records List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>My Medical Records</h2>
        <?php if (empty($records)): ?>
            <p>No medical records found.</p>
        <?php else: ?>
            <p style="margin-bottom: 20px; color: #666;">Total: <?= count($records) ?> records</p>
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
                            <td><?= htmlspecialchars($record['record_id']) ?></td>
                            <td><?= htmlspecialchars($record['record_date']) ?></td>
                            <td><?= htmlspecialchars($record['pat_first_name'] . ' ' . $record['pat_last_name']) ?></td>
                            <td><?= htmlspecialchars(substr($record['diagnosis'], 0, 50)) ?><?= strlen($record['diagnosis']) > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars(substr($record['treatment'], 0, 50)) ?><?= strlen($record['treatment']) > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars($record['follow_up_date'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="viewRecord(<?= htmlspecialchars(json_encode($record)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">View</button>
                                <button onclick="editRecord(<?= htmlspecialchars(json_encode($record)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 800px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Medical Record Details</h2>
        <div id="viewContent"></div>
        <button type="button" onclick="closeViewModal()" class="btn">Close</button>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 800px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Medical Record</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Diagnosis: *</label>
                <textarea name="diagnosis" id="edit_diagnosis" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Treatment: *</label>
                <textarea name="treatment" id="edit_treatment" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Prescription:</label>
                <textarea name="prescription" id="edit_prescription" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="notes" id="edit_notes" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>Follow-up Date:</label>
                <input type="date" name="follow_up_date" id="edit_follow_up_date">
            </div>
            <button type="submit" class="btn btn-success">Update Record</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function viewRecord(record) {
    const content = `
        <p><strong>Record ID:</strong> ${record.record_id}</p>
        <p><strong>Date:</strong> ${record.record_date}</p>
        <p><strong>Patient:</strong> ${record.pat_first_name} ${record.pat_last_name}</p>
        <p><strong>Appointment ID:</strong> ${record.appointment_id || 'N/A'}</p>
        <hr>
        <p><strong>Diagnosis:</strong></p>
        <p>${record.diagnosis}</p>
        <p><strong>Treatment:</strong></p>
        <p>${record.treatment}</p>
        <p><strong>Prescription:</strong></p>
        <p>${record.prescription || 'None'}</p>
        <p><strong>Notes:</strong></p>
        <p>${record.notes || 'None'}</p>
        <p><strong>Follow-up Date:</strong> ${record.follow_up_date || 'N/A'}</p>
    `;
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function editRecord(record) {
    document.getElementById('edit_id').value = record.record_id;
    document.getElementById('edit_diagnosis').value = record.diagnosis;
    document.getElementById('edit_treatment').value = record.treatment;
    document.getElementById('edit_prescription').value = record.prescription || '';
    document.getElementById('edit_notes').value = record.notes || '';
    document.getElementById('edit_follow_up_date').value = record.follow_up_date || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
