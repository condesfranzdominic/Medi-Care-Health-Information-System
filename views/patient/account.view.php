<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/patient/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Account</span>
        </div>
        <h1 class="page-title">My Account</h1>
    </div>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?= htmlspecialchars($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($patient)): ?>
    <!-- Account Information -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Account Information</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name: <span style="color: var(--status-error);">*</span></label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($patient['pat_first_name']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Last Name: <span style="color: var(--status-error);">*</span></label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($patient['pat_last_name']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email: <span style="color: var(--status-error);">*</span></label>
                        <input type="email" name="email" value="<?= htmlspecialchars($patient['pat_email'] ?? '') ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($patient['pat_phone'] ?? '') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input type="date" name="date_of_birth" value="<?= htmlspecialchars($patient['pat_date_of_birth'] ?? '') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Gender:</label>
                        <select name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="Male" <?= ($patient['pat_gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($patient['pat_gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($patient['pat_gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-grid-full">
                    <label>Address:</label>
                    <textarea name="address" rows="3" class="form-control"><?= htmlspecialchars($patient['pat_address'] ?? '') ?></textarea>
                </div>
                <div class="action-buttons" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Change Password</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="action" value="change_password">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Current Password: <span style="color: var(--status-error);">*</span></label>
                        <input type="password" name="current_password" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>New Password: <span style="color: var(--status-error);">*</span></label>
                        <input type="password" name="new_password" required class="form-control" minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password: <span style="color: var(--status-error);">*</span></label>
                        <input type="password" name="confirm_password" required class="form-control" minlength="6">
                    </div>
                </div>
                <div class="action-buttons" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-key"></i>
                        <span>Change Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

