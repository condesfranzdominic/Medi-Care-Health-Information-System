<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>My Schedules</h1>
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
    
    <!-- Today's Schedules -->
    <?php if (!empty($today_schedules)): ?>
    <div style="background: #e3f2fd; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #2196f3;">
        <h3>Today's Schedule (<?= date('F d, Y') ?>)</h3>
        <table class="table" style="background: white;">
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
                        <td><?= $sched['is_available'] ? '<span style="color: green;">✓ Yes</span>' : '<span style="color: red;">✗ No</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Create New Schedule -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Schedule</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div class="form-group">
                    <label>Date: *</label>
                    <input type="date" name="schedule_date" required>
                </div>
                <div class="form-group">
                    <label>Start Time: *</label>
                    <input type="time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label>End Time: *</label>
                    <input type="time" name="end_time" required>
                </div>
                <div class="form-group">
                    <label>Max Appointments:</label>
                    <input type="number" name="max_appointments" min="1" value="10">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_available" value="1" checked>
                    Available for appointments
                </label>
            </div>
            <button type="submit" class="btn btn-success">Add Schedule</button>
        </form>
    </div>
    
    <!-- All Schedules -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All My Schedules</h2>
        <?php if (empty($schedules)): ?>
            <p>No schedules found.</p>
        <?php else: ?>
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
                            <td><?= htmlspecialchars($sched['schedule_date']) ?></td>
                            <td><?= htmlspecialchars($sched['start_time']) ?></td>
                            <td><?= htmlspecialchars($sched['end_time']) ?></td>
                            <td><?= htmlspecialchars($sched['max_appointments']) ?></td>
                            <td><?= $sched['is_available'] ? '<span style="color: green;">✓ Yes</span>' : '<span style="color: red;">✗ No</span>' ?></td>
                            <td>
                                <button onclick="editSchedule(<?= htmlspecialchars(json_encode($sched)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $sched['schedule_id'] ?>">
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

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Schedule</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Date: *</label>
                <input type="date" name="schedule_date" id="edit_schedule_date" required>
            </div>
            <div class="form-group">
                <label>Start Time: *</label>
                <input type="time" name="start_time" id="edit_start_time" required>
            </div>
            <div class="form-group">
                <label>End Time: *</label>
                <input type="time" name="end_time" id="edit_end_time" required>
            </div>
            <div class="form-group">
                <label>Max Appointments:</label>
                <input type="number" name="max_appointments" id="edit_max_appointments" min="1">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_available" id="edit_is_available" value="1">
                    Available for appointments
                </label>
            </div>
            <button type="submit" class="btn btn-success">Update Schedule</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
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
    document.getElementById('edit_is_available').checked = sched.is_available == 1;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
