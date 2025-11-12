<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Today's Appointments</h1>
    <p style="color: var(--text-secondary); font-size: 0.9375rem; margin-top: 0.5rem;">
        <?= date('l, F d, Y') ?>
    </p>
</div>

<!-- Statistics Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-calendar-day"></i>
                    <span>Today's Appointments</span>
                </div>
                <div class="stat-value"><?= $today_count ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>Scheduled for today</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-history"></i>
                    <span>Past Appointments</span>
                </div>
                <div class="stat-value"><?= $past_count ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>Completed</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-calendar-check"></i>
                    <span>Future Appointments</span>
                </div>
                <div class="stat-value"><?= $future_count ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>Upcoming</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
</div>

<!-- Today's Appointments Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Appointments for <?= date('F d, Y') ?></h2>
    </div>
    <?php if (empty($appointments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="empty-state-text">No appointments scheduled for today.</div>
        </div>
    <?php else: ?>
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Contact</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                    <?php
                    $patInitial = strtoupper(substr($apt['pat_first_name'] ?? 'P', 0, 1));
                    $patName = htmlspecialchars(($apt['pat_first_name'] ?? '') . ' ' . ($apt['pat_last_name'] ?? ''));
                    $statusName = strtolower($apt['status_name'] ?? 'scheduled');
                    $isCompleted = $statusName === 'completed';
                    $isCanceled = $statusName === 'canceled' || $statusName === 'cancelled';
                    $statusClass = $isCompleted ? 'badge-success' : ($isCanceled ? 'badge-error' : 'badge-warning');
                    $appointmentTime = isset($apt['appointment_time']) ? date('g:i A', strtotime($apt['appointment_time'])) : 'N/A';
                    ?>
                    <tr class="patient-row">
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar"><?= $patInitial ?></div>
                                <div>
                                    <div class="patient-name"><?= $patName ?></div>
                                    <div class="patient-id">#<?= htmlspecialchars($apt['appointment_id']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><strong><?= $appointmentTime ?></strong></td>
                        <td><?= htmlspecialchars($apt['service_name'] ?? 'N/A') ?></td>
                        <td>
                            <?php if (!empty($apt['pat_phone'])): ?>
                                <a href="tel:<?= htmlspecialchars($apt['pat_phone']) ?>" style="color: var(--primary-blue); text-decoration: none;">
                                    <i class="fas fa-phone"></i> <?= htmlspecialchars($apt['pat_phone']) ?>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-secondary);">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($apt['appointment_duration'] ?? 30) ?> min</td>
                        <td>
                            <span class="badge <?= $statusClass ?>" style="background: <?= $apt['status_color'] ?? '#3B82F6' ?>; color: white;">
                                <?= htmlspecialchars($apt['status_name'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td>
                            <span style="color: var(--text-secondary);">
                                <?= !empty($apt['appointment_notes']) ? htmlspecialchars($apt['appointment_notes']) : '-' ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
