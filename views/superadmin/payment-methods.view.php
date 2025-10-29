<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Manage Payment Methods</h1>
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
    
    <!-- Create New Payment Method -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Payment Method</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px;">
                <div class="form-group">
                    <label>Method Name: *</label>
                    <input type="text" name="method_name" required placeholder="e.g., Cash, Credit Card">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="method_description" placeholder="Brief description">
                </div>
            </div>
            <div class="form-group">
            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; white-space: nowrap;">
            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
            Active (available for use)
            </label>
            </div>
            <button type="submit" class="btn btn-success">Add Payment Method</button>
        </form>
    </div>
    
    <!-- Payment Methods List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Payment Methods</h2>
        <?php if (empty($payment_methods)): ?>
            <p>No payment methods found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Method Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Payments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payment_methods as $method): ?>
                        <tr>
                            <td><?= htmlspecialchars($method['method_id']) ?></td>
                            <td><strong><?= htmlspecialchars($method['method_name']) ?></strong></td>
                            <td><?= htmlspecialchars($method['method_description'] ?? 'N/A') ?></td>
                            <td>
                                <span style="color: <?= $method['is_active'] ? 'green' : 'red' ?>;">
                                    <?= $method['is_active'] ? '✓ Active' : '✗ Inactive' ?>
                                </span>
                            </td>
                            <td><?= $method['payment_count'] ?> payment(s)</td>
                            <td>
                                <button onclick="editMethod(<?= htmlspecialchars(json_encode($method)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will affect <?= $method['payment_count'] ?> payment(s).');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $method['method_id'] ?>">
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
        <h2>Edit Payment Method</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Method Name: *</label>
                <input type="text" name="method_name" id="edit_method_name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <input type="text" name="method_description" id="edit_method_description">
            </div>
            <div class="form-group">
            <label style=" display: inline-flex; align-items: center; gap: 8px; cursor: pointer; white-space: nowrap;">
            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
            Active (available for use)
            </label>
            </div>
            <button type="submit" class="btn btn-success">Update Payment Method</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editMethod(method) {
    document.getElementById('edit_id').value = method.method_id;
    document.getElementById('edit_method_name').value = method.method_name;
    document.getElementById('edit_method_description').value = method.method_description || '';
    document.getElementById('edit_is_active').checked = method.is_active == 1;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
