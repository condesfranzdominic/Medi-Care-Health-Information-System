<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 20px;">
    <h1>Manage Users</h1>
    <a href="/superadmin/dashboard" style="color: blue;">← Back to Dashboard</a>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin: 15px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 0;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <!-- Create User Form -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Create New User</h2>
        <p style="color: #666; margin-bottom: 15px;">Select a role to create the user account. You will be redirected to create the corresponding profile.</p>
        <form id="createUserForm" onsubmit="return redirectToRoleCreation(event)">
            <div class="form-group">
                <label>Select Role: <span style="color: red;">*</span></label>
                <select id="role_select" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="">-- Select Role --</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="staff">Staff</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Continue to Create Profile →</button>
        </form>
    </div>
    
    <!-- Users List -->
    <div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Users</h2>
        
        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Linked Profile</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php
                            // Determine role
                            $role = 'None';
                            $roleColor = '#999';
                            $linkedProfile = 'N/A';
                            
                            if ($user['user_is_superadmin']) {
                                $role = 'Super Admin';
                                $roleColor = '#e74c3c';
                            } elseif ($user['staff_id']) {
                                $role = 'Staff';
                                $roleColor = '#3498db';
                                $linkedProfile = 'Staff ID: ' . $user['staff_id'];
                            } elseif ($user['doc_id']) {
                                $role = 'Doctor';
                                $roleColor = '#2ecc71';
                                $linkedProfile = 'Doctor ID: ' . $user['doc_id'];
                            } elseif ($user['pat_id']) {
                                $role = 'Patient';
                                $roleColor = '#9b59b6';
                                $linkedProfile = 'Patient ID: ' . $user['pat_id'];
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_id']) ?></td>
                            <td><?= htmlspecialchars($user['user_email']) ?></td>
                            <td><strong style="color: <?= $roleColor ?>"><?= $role ?></strong></td>
                            <td><?= $linkedProfile ?></td>
                            <td><?= htmlspecialchars($user['created_at'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $user['user_id'] ?>">
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

<!-- Edit User Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px; max-height: 90vh; overflow-y: auto;">
        <h2>Edit User</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            
            <div class="form-group">
                <label>Password (leave blank to keep current):</label>
                <input type="password" name="password" id="edit_password">
            </div>
            
            <div class="form-group">
                <label>Change Role:</label>
                <select name="role" id="edit_role" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="none">No Role</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="staff">Staff</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">Current role will be displayed when you open this form</small>
            </div>
            
            <div id="role_link_section" class="form-group" style="display: none;">
                <label id="role_link_label">Link to Profile ID:</label>
                <input type="number" name="role_id" id="edit_role_id" min="1" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <small style="color: #666; display: block; margin-top: 5px;">Enter the existing Staff/Doctor/Patient ID to link this user account</small>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                <strong>⚠️ Important:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Changing role will update the user's access level</li>
                    <li>Make sure the profile (Staff/Doctor/Patient) exists before linking</li>
                    <li>Super Admin role doesn't require a profile ID</li>
                </ul>
            </div>
            
            <button type="submit" class="btn btn-success">Update User</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function redirectToRoleCreation(event) {
    event.preventDefault();
    const role = document.getElementById('role_select').value;
    
    if (!role) {
        alert('Please select a role');
        return false;
    }
    
    // Redirect to appropriate creation page
    switch(role) {
        case 'superadmin':
            // For superadmin, we can create directly
            if (confirm('Create a Super Admin account? This will have full system access.')) {
                const email = prompt('Enter email for Super Admin:');
                const password = prompt('Enter password for Super Admin:');
                if (email && password) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="action" value="create">
                        <input type="hidden" name="email" value="${email}">
                        <input type="hidden" name="password" value="${password}">
                        <input type="hidden" name="is_superadmin" value="1">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            }
            break;
        case 'staff':
            window.location.href = '/superadmin/staff?create_user=1';
            break;
        case 'doctor':
            window.location.href = '/superadmin/doctors?create_user=1';
            break;
        case 'patient':
            window.location.href = '/superadmin/patients?create_user=1';
            break;
    }
    
    return false;
}

function editUser(user) {
    document.getElementById('edit_id').value = user.user_id;
    document.getElementById('edit_email').value = user.user_email;
    document.getElementById('edit_password').value = '';
    
    // Determine current role
    let currentRole = 'none';
    if (user.user_is_superadmin == true || user.user_is_superadmin == 1) {
        currentRole = 'superadmin';
    } else if (user.staff_id) {
        currentRole = 'staff';
        document.getElementById('edit_role_id').value = user.staff_id;
    } else if (user.doc_id) {
        currentRole = 'doctor';
        document.getElementById('edit_role_id').value = user.doc_id;
    } else if (user.pat_id) {
        currentRole = 'patient';
        document.getElementById('edit_role_id').value = user.pat_id;
    }
    
    document.getElementById('edit_role').value = currentRole;
    toggleRoleLinkSection();
    
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Show/hide role link section based on selected role
document.getElementById('edit_role').addEventListener('change', toggleRoleLinkSection);

function toggleRoleLinkSection() {
    const role = document.getElementById('edit_role').value;
    const linkSection = document.getElementById('role_link_section');
    const linkLabel = document.getElementById('role_link_label');
    
    if (role === 'staff' || role === 'doctor' || role === 'patient') {
        linkSection.style.display = 'block';
        if (role === 'staff') {
            linkLabel.textContent = 'Link to Staff ID:';
        } else if (role === 'doctor') {
            linkLabel.textContent = 'Link to Doctor ID:';
        } else if (role === 'patient') {
            linkLabel.textContent = 'Link to Patient ID:';
        }
    } else {
        linkSection.style.display = 'none';
        document.getElementById('edit_role_id').value = '';
    }
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
