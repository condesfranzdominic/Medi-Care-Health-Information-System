<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Previous Appointments</h1>
    <p><a href="/doctor/appointments/today" class="btn">← Back to Today's Appointments</a></p>
    
    <?php if (isset($error)): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <!-- Previous Appointments List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Past Appointments</h2>
        
        <?php if (empty($appointments)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">No previous appointments found.</p>
        <?php else: ?>
            <p style="margin-bottom: 20px; color: #666;">Total: <?= count($appointments) ?> appointments</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Service</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $apt): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($apt['appointment_date']) ?></strong></td>
                            <td><?= htmlspecialchars($apt['appointment_time'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['appointment_id']) ?></td>
                            <td><?= htmlspecialchars($apt['pat_first_name'] . ' ' . $apt['pat_last_name']) ?></td>
                            <td><?= htmlspecialchars($apt['pat_phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['service_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['appointment_duration'] ?? 30) ?> min</td>
                            <td>
                                <span style="background: <?= $apt['status_color'] ?? '#3B82F6' ?>; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">
                                    <?= htmlspecialchars($apt['status_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($apt['appointment_notes'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
