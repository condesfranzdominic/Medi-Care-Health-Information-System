<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Appointments</h1>
    <a href="/superadmin/dashboard" style="color: blue;">← Back to Dashboard</a>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin: 15px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 0;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <!-- Search Bar -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3>Search Appointment</h3>
        <form method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" 
                   placeholder="Search by Appointment ID (e.g., 2025-10-0000001)" 
                   style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <button type="submit" class="btn">Search</button>
            <?php if ($search_query): ?>
                <a href="/superadmin/appointments" class="btn">Clear</a>
            <?php endif; ?>
        </form>
        <?php if ($search_query): ?>
            <p style="margin-top: 10px; color: #666;">
                Showing results for: <strong><?= htmlspecialchars($search_query) ?></strong> 
                (<?= count($appointments) ?> result(s) found)
            </p>
        <?php endif; ?>
    </div>
    
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Create New Appointment</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>Patient:</label>
                <select name="patient_id" required>
                    <option value="">Select Patient</option>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?= $patient['pat_id'] ?>"><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Doctor:</label>
                <select name="doctor_id" required>
                    <option value="">Select Doctor</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor['doc_id'] ?>"><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Service:</label>
                <select name="service_id">
                    <option value="">Select Service (Optional)</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['service_id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="appointment_date" required>
            </div>
            <div class="form-group">
                <label>Time:</label>
                <input type="time" name="appointment_time">
            </div>
            <div class="form-group">
                <label>Status:</label>
                <select name="status_id">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Duration (Minutes):</label>
                <input type="number" name="duration" min="1" value="30">
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="notes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Create Appointment</button>
        </form>
    </div>
    
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Appointments</h2>
        <?php if (empty($appointments)): ?>
            <p>No appointments found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $apt): ?>
                        <tr>
                            <td><?= htmlspecialchars($apt['appointment_id']) ?></td>
                            <td><?= htmlspecialchars($apt['pat_first_name'] . ' ' . $apt['pat_last_name']) ?></td>
                            <td><?= htmlspecialchars($apt['doc_first_name'] . ' ' . $apt['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars($apt['service_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['appointment_date'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['appointment_time'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['appointment_duration'] ?? 30) ?> min</td>
                            <td><span style="background: <?= $apt['status_color'] ?? '#3B82F6' ?>; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px;"><?= htmlspecialchars($apt['status_name'] ?? 'N/A') ?></span></td>
                            <td>
                                <button onclick="editAppointment(<?= htmlspecialchars(json_encode($apt)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $apt['appointment_id'] ?>">
                                    <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 5px 10px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Appointment</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Patient:</label>
                <select name="patient_id" id="edit_patient_id" required>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?= $patient['pat_id'] ?>"><?= htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Doctor:</label>
                <select name="doctor_id" id="edit_doctor_id" required>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor['doc_id'] ?>"><?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Service:</label>
                <select name="service_id" id="edit_service_id">
                    <option value="">Select Service (Optional)</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['service_id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="appointment_date" id="edit_appointment_date" required>
            </div>
            <div class="form-group">
                <label>Time:</label>
                <input type="time" name="appointment_time" id="edit_appointment_time">
            </div>
            <div class="form-group">
                <label>Status:</label>
                <select name="status_id" id="edit_status_id">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Duration (Minutes):</label>
                <input type="number" name="duration" id="edit_duration" min="1">
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="notes" id="edit_notes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update Appointment</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editAppointment(apt) {
    document.getElementById('edit_id').value = apt.appointment_id;
    document.getElementById('edit_patient_id').value = apt.pat_id;
    document.getElementById('edit_doctor_id').value = apt.doc_id;
    document.getElementById('edit_service_id').value = apt.service_id || '';
    document.getElementById('edit_appointment_date').value = apt.appointment_date || '';
    document.getElementById('edit_appointment_time').value = apt.appointment_time || '';
    document.getElementById('edit_duration').value = apt.appointment_duration || 30;
    document.getElementById('edit_status_id').value = apt.status_id || 1;
    document.getElementById('edit_notes').value = apt.appointment_notes || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
