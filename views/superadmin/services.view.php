<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Services</h1>
    <a href="/superadmin/dashboard" style="color: blue;">← Back to Dashboard</a>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin: 15px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 0;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Service</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>Service Name:</label>
                <input type="text" name="service_name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" step="0.01" min="0" value="0">
            </div>
            <div class="form-group">
                <label>Duration (Minutes):</label>
                <input type="number" name="duration" min="1" value="30">
            </div>
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category">
            </div>
            <button type="submit" class="btn btn-success">Add Service</button>
        </form>
    </div>
    
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Services</h2>
        <?php if (empty($services)): ?>
            <p>No services found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['service_id']) ?></td>
                            <td><?= htmlspecialchars($service['service_name']) ?></td>
                            <td><?= htmlspecialchars($service['service_description'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($service['service_price'] ?? 0, 2) ?></td>
                            <td><?= htmlspecialchars($service['service_duration_minutes'] ?? 30) ?> min</td>
                            <td><?= htmlspecialchars($service['service_category'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="editService(<?= htmlspecialchars(json_encode($service)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $service['service_id'] ?>">
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

<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Service</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Service Name:</label>
                <input type="text" name="service_name" id="edit_service_name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" id="edit_description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" id="edit_price" step="0.01" min="0">
            </div>
            <div class="form-group">
                <label>Duration (Minutes):</label>
                <input type="number" name="duration" id="edit_duration" min="1">
            </div>
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category" id="edit_category">
            </div>
            <button type="submit" class="btn btn-success">Update Service</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editService(service) {
    document.getElementById('edit_id').value = service.service_id;
    document.getElementById('edit_service_name').value = service.service_name;
    document.getElementById('edit_description').value = service.service_description || '';
    document.getElementById('edit_price').value = service.service_price || 0;
    document.getElementById('edit_duration').value = service.service_duration_minutes || 30;
    document.getElementById('edit_category').value = service.service_category || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
