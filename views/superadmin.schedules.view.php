<?php require_once __DIR__ . '/partials/header.php'; ?>

<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <h1>Manage All Doctor Schedules</h1>
    <p><a href="/superadmin/dashboard" class="btn">← Back to Dashboard</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="background: #efe; color: #3c3; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <!-- Today's Schedules -->
    <?php if (!empty($today_schedules)): ?>
    <div style="background: #e3f2fd; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #2196f3;">
        <h3>Today's Schedules (<?= date('F d, Y') ?>)</h3>
        <table class="table" style="background: white; margin-top: 15px;">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Max Appointments</th>
                    <th>Available</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($today_schedules as $sched): ?>
                    <tr>
                        <td>Dr. <?= htmlspecialchars($sched['doc_first_name'] . ' ' . $sched['doc_last_name']) ?></td>
                        <td><?= htmlspecialchars($sched['spec_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($sched['start_time']) ?></td>
                        <td><?= htmlspecialchars($sched['end_time']) ?></td>
                        <td><?= htmlspecialchars($sched['max_appointments']) ?></td>
                        <td><?= $sched['is_available'] ? '<span style="color: green;">✓ Yes</span>' : '<span style="color: red;">✗ No</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- All Schedules -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Doctor Schedules</h2>
        <p style="background: #fff3cd; padding: 10px; border-radius: 5px; color: #856404; font-size: 14px;">
            <strong>Note:</strong> Schedules are created and managed by doctors. As superadmin, you can view and delete schedules.
        </p>
        <?php if (empty($schedules)): ?>
            <p>No schedules found.</p>
        <?php else: ?>
            <p style="margin: 15px 0; color: #666;">Total: <?= count($schedules) ?> schedules</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Max Appointments</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $sched): ?>
                        <tr>
                            <td><?= htmlspecialchars($sched['schedule_id']) ?></td>
                            <td><?= htmlspecialchars($sched['schedule_date']) ?></td>
                            <td>Dr. <?= htmlspecialchars($sched['doc_first_name'] . ' ' . $sched['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars($sched['spec_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($sched['start_time']) ?></td>
                            <td><?= htmlspecialchars($sched['end_time']) ?></td>
                            <td><?= htmlspecialchars($sched['max_appointments']) ?></td>
                            <td><?= $sched['is_available'] ? '<span style="color: green;">✓ Yes</span>' : '<span style="color: red;">✗ No</span>' ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $sched['schedule_id'] ?>">
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

<?php require_once __DIR__ . '/partials/footer.php'; ?>
