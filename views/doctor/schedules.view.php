<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">My Schedules</h1>
    <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
        <a href="/doctor/appointments/today" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
        <a href="/doctor/schedules/manage" class="btn btn-primary">
            <i class="fas fa-calendar-alt"></i>
            <span>Manage All Doctor Schedules</span>
        </a>
        <a href="/doctor/doctors" class="btn btn-success">
            <i class="fas fa-user-md"></i>
            <span>Manage Doctors</span>
        </a>
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

<!-- Today's Schedules -->
<?php if (!empty($today_schedules)): ?>
    <div class="card" style="border-left: 4px solid var(--primary-blue);">
        <div class="card-header">
            <h2 class="card-title">Today's Schedule (<?= date('F d, Y') ?>)</h2>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Max Appointments</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($today_schedules as $sched): ?>
                        <tr>
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

<!-- Create New Schedule -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Add New Schedule</h2>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="schedule_date" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Start Time: <span style="color: var(--status-error);">*</span></label>
                    <input type="time" name="start_time" required class="form-control">
                </div>
                <div class="form-group">
                    <label>End Time: <span style="color: var(--status-error);">*</span></label>
                    <input type="time" name="end_time" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Max Appointments:</label>
                    <input type="number" name="max_appointments" min="1" value="10" class="form-control">
                </div>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_available" value="1" checked style="width: auto;">
                    <span>Available for appointments</span>
                </label>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Add Schedule</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- All Schedules -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All My Schedules</h2>
    </div>
    <?php if (empty($schedules)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="empty-state-text">No schedules found.</div>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
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
                            <td><strong><?= htmlspecialchars($sched['schedule_date']) ?></strong></td>
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
                                    <button onclick="editSchedule(<?= htmlspecialchars(json_encode($sched)) ?>)" class="btn btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
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
    <?php endif; ?>
</div>

<!-- Edit Schedule Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Schedule</h2>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Date: <span style="color: var(--status-error);">*</span></label>
                    <input type="date" name="schedule_date" id="edit_schedule_date" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Start Time: <span style="color: var(--status-error);">*</span></label>
                    <input type="time" name="start_time" id="edit_start_time" required class="form-control">
                </div>
                <div class="form-group">
                    <label>End Time: <span style="color: var(--status-error);">*</span></label>
                    <input type="time" name="end_time" id="edit_end_time" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Max Appointments:</label>
                    <input type="number" name="max_appointments" id="edit_max_appointments" min="1" class="form-control">
                </div>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_available" id="edit_is_available" value="1" style="width: auto;">
                    <span>Available for appointments</span>
                </label>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Update Schedule</span>
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
function editSchedule(sched) {
    document.getElementById('edit_id').value = sched.schedule_id;
    document.getElementById('edit_schedule_date').value = sched.schedule_date;
    document.getElementById('edit_start_time').value = sched.start_time;
    document.getElementById('edit_end_time').value = sched.end_time;
    document.getElementById('edit_max_appointments').value = sched.max_appointments;
    document.getElementById('edit_is_available').checked = sched.is_available == 1 || sched.is_available === true;
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
