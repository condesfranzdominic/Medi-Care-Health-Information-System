<?php require_once __DIR__ . '/partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Manage Specializations</h1>
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
    
    <!-- Create New Specialization -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Specialization</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>Specialization Name: *</label>
                <input type="text" name="spec_name" required placeholder="e.g., Family Medicine, Cardiology">
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="spec_description" rows="3" placeholder="Brief description of this specialization"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Add Specialization</button>
        </form>
    </div>
    
    <!-- Specializations List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Specializations</h2>
        <?php if (empty($specializations)): ?>
            <p>No specializations found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Specialization Name</th>
                        <th>Description</th>
                        <th>Doctors</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specializations as $spec): ?>
                        <tr>
                            <td><?= htmlspecialchars($spec['spec_id']) ?></td>
                            <td><strong><?= htmlspecialchars($spec['spec_name']) ?></strong></td>
                            <td><?= htmlspecialchars($spec['spec_description'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($spec['doctor_count'] > 0): ?>
                                    <a href="/superadmin/specializations/doctors/<?= $spec['spec_id'] ?>" class="btn" style="font-size: 12px; padding: 5px 10px;">
                                        <?= $spec['doctor_count'] ?> Doctor(s)
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">0 Doctors</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button onclick="editSpecialization(<?= htmlspecialchars(json_encode($spec)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will affect doctors with this specialization.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $spec['spec_id'] ?>">
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
        <h2>Edit Specialization</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Specialization Name: *</label>
                <input type="text" name="spec_name" id="edit_spec_name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="spec_description" id="edit_spec_description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update Specialization</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editSpecialization(spec) {
    document.getElementById('edit_id').value = spec.spec_id;
    document.getElementById('edit_spec_name').value = spec.spec_name;
    document.getElementById('edit_spec_description').value = spec.spec_description || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
