<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
    <h2 style="margin: 0 0 5px 0;">Today's Schedule</h2>
    <p style="margin: 0; opacity: 0.9;"><?= date('l, F d, Y') ?></p>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Today's Appointments</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $today_count ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">📅</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Past Appointments</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $past_count ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">✅</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Future Appointments</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $future_count ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">🗓️</div>
        </div>
    </div>
</div>

<!-- Today's Appointments List -->
<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Appointments for <?= date('F d, Y') ?></h2>
        
        <?php if (empty($appointments)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">No appointments scheduled for today.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
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
                            <td><strong><?= htmlspecialchars($apt['appointment_time'] ?? 'N/A') ?></strong></td>
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

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
