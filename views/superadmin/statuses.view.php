<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Manage Appointment Statuses</h1>
    <p><a href="/superadmin/dashboard" class="btn">← Back to Dashboard</a></p>
    
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
    
    <!-- Create New Status -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Status</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: 1fr 1fr 150px; gap: 15px;">
                <div class="form-group">
                    <label>Status Name: *</label>
                    <input type="text" name="status_name" required placeholder="e.g., Scheduled, Completed, Cancelled">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="status_description" placeholder="Brief description">
                </div>
                <div class="form-group">
                    <label>Color:</label>
                    <input type="color" name="status_color" value="#3B82F6" style="width: 100%; height: 40px; cursor: pointer;">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Add Status</button>
        </form>
    </div>
    
    <!-- Statuses List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Appointment Statuses</h2>
        <?php if (empty($statuses)): ?>
            <p>No statuses found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status Name</th>
                        <th>Description</th>
                        <th>Preview</th>
                        <th>Appointments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statuses as $status): ?>
                        <tr>
                            <td><?= htmlspecialchars($status['status_id']) ?></td>
                            <td><strong><?= htmlspecialchars($status['status_name']) ?></strong></td>
                            <td><?= htmlspecialchars($status['status_description'] ?? 'N/A') ?></td>
                            <td>
                                <span style="background: <?= htmlspecialchars($status['status_color']) ?>; color: white; padding: 5px 12px; border-radius: 4px; font-size: 12px;">
                                    <?= htmlspecialchars($status['status_name']) ?>
                                </span>
                            </td>
                            <td><?= $status['appointment_count'] ?> appointment(s)</td>
                            <td>
                                <button onclick="editStatus(<?= htmlspecialchars(json_encode($status)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will affect <?= $status['appointment_count'] ?> appointment(s).');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $status['status_id'] ?>">
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
        <h2>Edit Status</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Status Name: *</label>
                <input type="text" name="status_name" id="edit_status_name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <input type="text" name="status_description" id="edit_status_description">
            </div>
            <div class="form-group">
                <label>Color:</label>
                <input type="color" name="status_color" id="edit_status_color" style="width: 100%; height: 50px; cursor: pointer;">
            </div>
            <button type="submit" class="btn btn-success">Update Status</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editStatus(status) {
    document.getElementById('edit_id').value = status.status_id;
    document.getElementById('edit_status_name').value = status.status_name;
    document.getElementById('edit_status_description').value = status.status_description || '';
    document.getElementById('edit_status_color').value = status.status_color || '#3B82F6';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
