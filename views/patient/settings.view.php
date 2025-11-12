<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <div class="page-header-top">
        <div class="breadcrumbs">
            <a href="/patient/dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <i class="fas fa-chevron-right"></i>
            <span>Settings</span>
        </div>
        <h1 class="page-title">Settings</h1>
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

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Preferences</h2>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="notifications" value="1" checked style="width: auto;">
                    <span>Enable Notifications</span>
                </label>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="email_notifications" value="1" checked style="width: auto;">
                    <span>Email Notifications</span>
                </label>
            </div>
            <div class="form-group">
                <label>Language:</label>
                <select name="language" class="form-control">
                    <option value="en">English</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                </select>
            </div>
            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

