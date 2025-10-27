<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
    <h2 style="margin: 0 0 5px 0;">Welcome, <?= htmlspecialchars($patient['pat_first_name'] ?? 'Patient') ?> <?= htmlspecialchars($patient['pat_last_name'] ?? '') ?>!</h2>
    <p style="margin: 0; opacity: 0.9;">Manage your appointments and health information</p>
</div>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

<!-- Quick Action Button -->
<div style="margin-bottom: 30px;">
    <a href="/patient/appointments/create" style="display: inline-block; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 15px rgba(67, 233, 123, 0.4);">
        📅 Book New Appointment
    </a>
</div>

<!-- Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Appointments</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= count($appointments) ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">📅</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Upcoming</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= count($upcoming_appointments) ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">⏰</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Past</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= count($past_appointments) ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">✅</div>
        </div>
    </div>
</div>

<!-- Upcoming Appointments -->
<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Upcoming Appointments</h2>
        <?php if (empty($upcoming_appointments)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">
                No upcoming appointments. <a href="/patient/appointments/create">Book one now!</a>
            </p>
        <?php else: ?>
            <div style="display: grid; gap: 15px;">
                <?php foreach ($upcoming_appointments as $apt): ?>
                    <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #f9f9f9;">
                        <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: start;">
                            <div style="text-align: center; min-width: 80px;">
                                <div style="background: #667eea; color: white; padding: 10px; border-radius: 8px;">
                                    <div style="font-size: 24px; font-weight: bold;"><?= date('d', strtotime($apt['appointment_date'])) ?></div>
                                    <div style="font-size: 12px;"><?= date('M Y', strtotime($apt['appointment_date'])) ?></div>
                                </div>
                            </div>
                            <div>
                                <h3 style="margin: 0 0 10px 0;">Dr. <?= htmlspecialchars($apt['doc_first_name'] . ' ' . $apt['doc_last_name']) ?></h3>
                                <p style="margin: 5px 0; color: #666;"><strong>Specialization:</strong> <?= htmlspecialchars($apt['spec_name'] ?? 'N/A') ?></p>
                                <p style="margin: 5px 0; color: #666;"><strong>Service:</strong> <?= htmlspecialchars($apt['service_name'] ?? 'N/A') ?></p>
                                <p style="margin: 5px 0; color: #666;"><strong>Time:</strong> <?= htmlspecialchars($apt['appointment_time'] ?? 'N/A') ?></p>
                                <p style="margin: 5px 0; color: #666;"><strong>Duration:</strong> <?= htmlspecialchars($apt['appointment_duration'] ?? 30) ?> minutes</p>
                                <p style="margin: 5px 0; color: #666;"><strong>Appointment ID:</strong> <?= htmlspecialchars($apt['appointment_id']) ?></p>
                                <?php if ($apt['appointment_notes']): ?>
                                    <p style="margin: 5px 0; color: #666;"><strong>Notes:</strong> <?= htmlspecialchars($apt['appointment_notes']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div style="text-align: right;">
                                <span style="background: <?= $apt['status_color'] ?? '#3B82F6' ?>; color: white; padding: 6px 12px; border-radius: 4px; font-size: 13px; display: inline-block;">
                                    <?= htmlspecialchars($apt['status_name'] ?? 'N/A') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<!-- Past Appointments -->
<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Past Appointments</h2>
        <?php if (empty($past_appointments)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">No past appointments.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Appointment ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($past_appointments as $apt): ?>
                        <tr>
                            <td><?= htmlspecialchars($apt['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($apt['appointment_time'] ?? 'N/A') ?></td>
                            <td>Dr. <?= htmlspecialchars($apt['doc_first_name'] . ' ' . $apt['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars($apt['service_name'] ?? 'N/A') ?></td>
                            <td>
                                <span style="background: <?= $apt['status_color'] ?? '#3B82F6' ?>; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">
                                    <?= htmlspecialchars($apt['status_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($apt['appointment_id']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
