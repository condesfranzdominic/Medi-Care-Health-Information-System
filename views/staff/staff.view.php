<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Manage Staff</h1>
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
    
    <!-- Search Bar -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3>Search Staff</h3>
        <form method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" 
                   placeholder="Search by first name or last name..." 
                   style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <button type="submit" class="btn">Search</button>
            <?php if ($search_query): ?>
                <a href="/staff/staff" class="btn">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Create New Staff -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Staff Member</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>First Name: *</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name: *</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group">
                    <label>Email: *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Position:</label>
                    <input type="text" name="position" placeholder="e.g., Receptionist, Nurse">
                </div>
                <div class="form-group">
                    <label>Hire Date:</label>
                    <input type="date" name="hire_date">
                </div>
                <div class="form-group">
                    <label>Salary:</label>
                    <input type="number" name="salary" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Add Staff Member</button>
        </form>
    </div>
    
    <!-- Staff List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2><?= $search_query ? 'Search Results' : 'All Staff Members' ?></h2>
        <p style="background: #fff3cd; padding: 10px; border-radius: 5px; color: #856404; font-size: 14px;">
            <strong>Note:</strong> Only Super Admin can delete staff members.
        </p>
        <?php if (empty($staff_members)): ?>
            <p><?= $search_query ? 'No staff members found matching your search.' : 'No staff members found.' ?></p>
        <?php else: ?>
            <?php if ($search_query): ?>
                <p style="margin-bottom: 15px; color: #666;">Found <?= count($staff_members) ?> result(s) for "<?= htmlspecialchars($search_query) ?>"</p>
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Hire Date</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff_members as $staff): ?>
                        <tr>
                            <td><?= htmlspecialchars($staff['staff_id']) ?></td>
                            <td><strong><?= htmlspecialchars($staff['staff_first_name'] . ' ' . $staff['staff_last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($staff['staff_email']) ?></td>
                            <td><?= htmlspecialchars($staff['staff_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($staff['staff_position'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($staff['staff_hire_date'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($staff['staff_salary'] ?? 0, 2) ?></td>
                            <td>
                                <span style="color: <?= $staff['staff_status'] === 'active' ? 'green' : 'red' ?>;">
                                    <?= ucfirst($staff['staff_status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td>
                                <button onclick="editStaff(<?= htmlspecialchars(json_encode($staff)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
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
        <h2>Edit Staff Member</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>First Name: *</label>
                    <input type="text" name="first_name" id="edit_first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name: *</label>
                    <input type="text" name="last_name" id="edit_last_name" required>
                </div>
                <div class="form-group">
                    <label>Email: *</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" id="edit_phone">
                </div>
                <div class="form-group">
                    <label>Position:</label>
                    <input type="text" name="position" id="edit_position">
                </div>
                <div class="form-group">
                    <label>Hire Date:</label>
                    <input type="date" name="hire_date" id="edit_hire_date">
                </div>
                <div class="form-group">
                    <label>Salary:</label>
                    <input type="number" name="salary" id="edit_salary" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="edit_status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Update Staff Member</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editStaff(staff) {
    document.getElementById('edit_id').value = staff.staff_id;
    document.getElementById('edit_first_name').value = staff.staff_first_name;
    document.getElementById('edit_last_name').value = staff.staff_last_name;
    document.getElementById('edit_email').value = staff.staff_email;
    document.getElementById('edit_phone').value = staff.staff_phone || '';
    document.getElementById('edit_position').value = staff.staff_position || '';
    document.getElementById('edit_hire_date').value = staff.staff_hire_date || '';
    document.getElementById('edit_salary').value = staff.staff_salary || '';
    document.getElementById('edit_status').value = staff.staff_status || 'active';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
