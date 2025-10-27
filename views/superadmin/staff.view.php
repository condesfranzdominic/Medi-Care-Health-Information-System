<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Staff</h1>
    <a href="/superadmin/dashboard" style="color: blue;">← Back to Dashboard</a>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin: 15px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 0;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Staff Member</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone">
            </div>
            <div class="form-group">
                <label>Position:</label>
                <input type="text" name="position">
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
            
            <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">
            
            <div style="background: #f0f8ff; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                <h3 style="margin: 0 0 10px 0; color: #1976d2;">🔐 User Account (Login Credentials)</h3>
                <p style="margin: 0; font-size: 14px; color: #666;">Check the box below to create a user account for this staff member to login to the system.</p>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="create_user" value="1" id="staff_create_user_checkbox" onchange="toggleStaffPasswordField()" style="margin-right: 10px; width: auto;">
                    <span>Create user account for login</span>
                </label>
            </div>
            
            <div class="form-group" id="staff_password_field" style="display: none;">
                <label>Password: <span style="color: red;">*</span></label>
                <input type="password" name="password" id="staff_password_input" minlength="6" placeholder="Minimum 6 characters">
                <small style="display: block; margin-top: 5px; color: #666;">The staff member will use their email and this password to login.</small>
            </div>
            
            <button type="submit" class="btn btn-success">Add Staff</button>
        </form>
    </div>
    
    <script>
    function toggleStaffPasswordField() {
        const checkbox = document.getElementById('staff_create_user_checkbox');
        const passwordField = document.getElementById('staff_password_field');
        const passwordInput = document.getElementById('staff_password_input');
        
        if (checkbox.checked) {
            passwordField.style.display = 'block';
            passwordInput.required = true;
        } else {
            passwordField.style.display = 'none';
            passwordInput.required = false;
            passwordInput.value = '';
        }
    }
    </script>
    
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Staff Members</h2>
        <?php if (empty($staff)): ?>
            <p>No staff members found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Hire Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <tr>
                            <td><?= htmlspecialchars($member['staff_id']) ?></td>
                            <td><?= htmlspecialchars($member['staff_first_name'] . ' ' . $member['staff_last_name']) ?></td>
                            <td><?= htmlspecialchars($member['staff_email']) ?></td>
                            <td><?= htmlspecialchars($member['staff_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($member['staff_position'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($member['staff_hire_date'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($member['staff_status'] ?? 'active') ?></td>
                            <td>
                                <button onclick="editStaff(<?= htmlspecialchars(json_encode($member)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $member['staff_id'] ?>">
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
        <h2>Edit Staff Member</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" id="edit_first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" id="edit_last_name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
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
            <button type="submit" class="btn btn-success">Update Staff</button>
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
