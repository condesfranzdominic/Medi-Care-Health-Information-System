<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Manage Services</h1>
    <p><a href="/staff/dashboard" class="btn">← Back to Dashboard</a></p>
    
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
    
    <!-- Create New Service -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Service</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Service Name: *</label>
                    <input type="text" name="service_name" required placeholder="e.g., Consultation, Laboratory Test">
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <input type="text" name="service_category" placeholder="e.g., Medical, Diagnostic">
                </div>
                <div class="form-group">
                    <label>Price (₱):</label>
                    <input type="number" name="service_price" step="0.01" min="0" value="0">
                </div>
                <div class="form-group">
                    <label>Duration (Minutes):</label>
                    <input type="number" name="service_duration" min="1" value="30">
                </div>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="service_description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Add Service</button>
        </form>
    </div>
    
    <!-- Services List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Services</h2>
        <p style="background: #fff3cd; padding: 10px; border-radius: 5px; color: #856404; font-size: 14px;">
            <strong>Note:</strong> Only Super Admin can delete services.
        </p>
        <?php if (empty($services)): ?>
            <p>No services found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Appointments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['service_id']) ?></td>
                            <td><strong><?= htmlspecialchars($service['service_name']) ?></strong></td>
                            <td><?= htmlspecialchars($service['service_category'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($service['service_price'] ?? 0, 2) ?></td>
                            <td><?= htmlspecialchars($service['service_duration_minutes'] ?? 30) ?> min</td>
                            <td>
                                <?php if ($service['appointment_count'] > 0): ?>
                                    <a href="/staff/service-appointments?id=<?= $service['service_id'] ?>" class="btn" style="font-size: 12px; padding: 5px 10px;">
                                        <?= $service['appointment_count'] ?> Appointment(s)
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">0 Appointments</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button onclick="editService(<?= htmlspecialchars(json_encode($service)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 700px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Service</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Service Name: *</label>
                    <input type="text" name="service_name" id="edit_service_name" required>
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <input type="text" name="service_category" id="edit_service_category">
                </div>
                <div class="form-group">
                    <label>Price (₱):</label>
                    <input type="number" name="service_price" id="edit_service_price" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Duration (Minutes):</label>
                    <input type="number" name="service_duration" id="edit_service_duration" min="1">
                </div>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="service_description" id="edit_service_description" rows="3"></textarea>
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
    document.getElementById('edit_service_category').value = service.service_category || '';
    document.getElementById('edit_service_price').value = service.service_price || 0;
    document.getElementById('edit_service_duration').value = service.service_duration_minutes || 30;
    document.getElementById('edit_service_description').value = service.service_description || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
