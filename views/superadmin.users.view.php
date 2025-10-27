<?php require_once __DIR__ . '/partials/header.php'; ?>

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
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_superadmin" value="1">
                    Is Super Admin
                </label>
            </div>
            
            <button type="submit" class="btn btn-success">Create User</button>
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
                        <th>Type</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_id']) ?></td>
                            <td><?= htmlspecialchars($user['user_email']) ?></td>
                            <td><?= $user['user_is_superadmin'] ? '<strong style="color: #e74c3c;">Super Admin</strong>' : 'Regular User' ?></td>
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
    <div style="background: #fff; max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 8px;">
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
                <label>
                    <input type="checkbox" name="is_superadmin" id="edit_is_superadmin" value="1">
                    Is Super Admin
                </label>
            </div>
            
            <button type="submit" class="btn btn-success">Update User</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function editUser(user) {
    document.getElementById('edit_id').value = user.user_id;
    document.getElementById('edit_email').value = user.user_email;
    document.getElementById('edit_is_superadmin').checked = user.user_is_superadmin == true || user.user_is_superadmin == 1;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
