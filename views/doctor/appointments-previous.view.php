<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Previous Appointments</h1>
    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
        <a href="/doctor/appointments/today" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Today</span>
        </a>
        <a href="/doctor/appointments/future" class="btn btn-secondary">
            <i class="fas fa-calendar-check"></i>
            <span>View Future</span>
        </a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<!-- Statistics -->
<div class="stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-history"></i>
                    <span>Total Past</span>
                </div>
                <div class="stat-value"><?= count($appointments) ?></div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>
</div>

<!-- Previous Appointments Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Past Appointments</h2>
    </div>
    <?php if (empty($appointments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="empty-state-text">No previous appointments found.</div>
        </div>
    <?php else: ?>
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
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
                    $statusName = strtolower($apt['status_name'] ?? 'completed');
                    $isCompleted = $statusName === 'completed';
                    $isCanceled = $statusName === 'canceled' || $statusName === 'cancelled';
                    $statusClass = $isCompleted ? 'badge-success' : ($isCanceled ? 'badge-error' : 'badge-warning');
                    $appointmentDate = isset($apt['appointment_date']) ? date('M d, Y', strtotime($apt['appointment_date'])) : 'N/A';
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
                        <td><strong><?= $appointmentDate ?></strong></td>
                        <td><?= $appointmentTime ?></td>
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
