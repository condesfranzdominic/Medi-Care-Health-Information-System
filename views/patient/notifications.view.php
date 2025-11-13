<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
    .notifications-page {
        padding: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .notification-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
        display: flex;
        gap: 1rem;
        align-items: start;
    }
    
    .notification-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        border-color: var(--primary-blue);
    }
    
    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .notification-message {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 0.75rem;
    }
    
    .notification-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.75rem;
        color: #9ca3af;
    }
    
    .notification-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: var(--primary-blue);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--primary-blue-dark);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state-text {
        font-size: 1.125rem;
        color: #6b7280;
    }
</style>

<div class="notifications-page">
    <div class="page-header">
        <h1 class="page-title">Notifications</h1>
        <p class="page-subtitle">Stay updated with your appointments and important updates</p>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (empty($notifications)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-bell-slash"></i></div>
            <div class="empty-state-text">No notifications</div>
        </div>
    <?php else: ?>
        <div class="notifications-list">
            <?php foreach ($notifications as $notif): ?>
                <?php
                $docName = 'Dr. ' . htmlspecialchars(($notif['doc_first_name'] ?? '') . ' ' . ($notif['doc_last_name'] ?? ''));
                $specName = htmlspecialchars($notif['spec_name'] ?? 'General Practice');
                $appointmentDate = date('M j, Y', strtotime($notif['appointment_date']));
                $appointmentTime = date('g:i A', strtotime($notif['appointment_time']));
                $daysUntil = (strtotime($notif['appointment_date']) - strtotime('today')) / 86400;
                
                if ($daysUntil == 0) {
                    $title = "Appointment Today";
                    $message = "You have an appointment with {$docName} today at {$appointmentTime}";
                } elseif ($daysUntil == 1) {
                    $title = "Appointment Tomorrow";
                    $message = "Reminder: Your appointment with {$docName} is tomorrow at {$appointmentTime}";
                } else {
                    $title = "Upcoming Appointment";
                    $message = "You have an appointment with {$docName} on {$appointmentDate} at {$appointmentTime}";
                }
                ?>
                <div class="notification-card">
                    <div class="notification-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title"><?= $title ?></div>
                        <div class="notification-message"><?= $message ?></div>
                        <div class="notification-meta">
                            <span><i class="fas fa-user-md"></i> <?= $docName ?> - <?= $specName ?></span>
                            <span><i class="fas fa-calendar"></i> <?= $appointmentDate ?></span>
                            <span><i class="fas fa-clock"></i> <?= $appointmentTime ?></span>
                        </div>
                        <div class="notification-actions">
                            <a href="/patient/appointments?view=<?= $notif['appointment_id'] ?>" class="btn-action btn-primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

