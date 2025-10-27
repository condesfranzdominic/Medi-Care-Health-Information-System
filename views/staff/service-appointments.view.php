<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1><?= $service ? htmlspecialchars($service['service_name']) . ' - Appointments' : 'Service Appointments' ?></h1>
    <p><a href="/staff/services" class="btn">← Back to Services</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($service): ?>
    <div style="background: #e3f2fd; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #2196f3;">
        <h3 style="margin: 0 0 10px 0;">Service Details</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <p style="margin: 5px 0; color: #555;"><strong>Service:</strong> <?= htmlspecialchars($service['service_name']) ?></p>
                <p style="margin: 5px 0; color: #555;"><strong>Category:</strong> <?= htmlspecialchars($service['service_category'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p style="margin: 5px 0; color: #555;"><strong>Price:</strong> ₱<?= number_format($service['service_price'] ?? 0, 2) ?></p>
                <p style="margin: 5px 0; color: #555;"><strong>Duration:</strong> <?= htmlspecialchars($service['service_duration_minutes'] ?? 30) ?> minutes</p>
            </div>
            <div>
                <p style="margin: 5px 0; color: #555;"><strong>Total Appointments:</strong> <?= count($appointments) ?></p>
            </div>
        </div>
        <?php if (!empty($service['service_description'])): ?>
        <p style="margin: 10px 0 0 0; color: #555; font-style: italic;">
            <?= htmlspecialchars($service['service_description']) ?>
        </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Appointments List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Appointments for <?= htmlspecialchars($service['service_name'] ?? 'This Service') ?></h2>
        
        <?php if (empty($appointments)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">No appointments found for this service.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Doctor</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $apt): ?>
                        <tr>
                            <td><?= htmlspecialchars($apt['appointment_id']) ?></td>
                            <td><?= htmlspecialchars($apt['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($apt['appointment_time'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($apt['pat_first_name'] . ' ' . $apt['pat_last_name']) ?></td>
                            <td><?= htmlspecialchars($apt['pat_phone'] ?? 'N/A') ?></td>
                            <td>Dr. <?= htmlspecialchars($apt['doc_first_name'] . ' ' . $apt['doc_last_name']) ?></td>
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
