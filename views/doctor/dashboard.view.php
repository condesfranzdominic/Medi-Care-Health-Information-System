<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="padding: 30px;">
    <!-- Welcome Section -->
    <div style="margin-bottom: 30px;">
        <h1 style="margin: 0 0 10px 0;">Welcome, Dr. <?= htmlspecialchars($doctor['doc_last_name'] ?? 'Doctor') ?>! 👋</h1>
        <p style="margin: 0; color: #666; font-size: 16px;">
            <?= htmlspecialchars($doctor['spec_name'] ?? 'General Practice') ?> | 
            <?= date('l, F j, Y') ?>
        </p>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Total Appointments -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Appointments</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['total_appointments'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">📅</div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Today's Appointments</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['today_appointments'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">📊</div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Upcoming</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['upcoming_appointments'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">🗓️</div>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(67, 233, 123, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Completed</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['completed_appointments'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">✅</div>
            </div>
        </div>

        <!-- Total Patients -->
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Patients</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['total_patients'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">🏥</div>
            </div>
        </div>

        <!-- My Schedules -->
        <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(168, 237, 234, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.8; font-size: 14px;">My Schedules</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['my_schedules'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">⏰</div>
            </div>
        </div>

        <!-- All Schedules -->
        <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(252, 182, 159, 0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.8; font-size: 14px;">All Schedules</p>
                    <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['all_schedules'] ?></h2>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">🗓️</div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Today's Appointments -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">📊 Today's Appointments</h2>
                <a href="/doctor/appointments/today" class="btn btn-primary btn-sm">View All</a>
            </div>
            
            <?php if (empty($today_appointments)): ?>
                <p style="color: #999; text-align: center; padding: 20px;">No appointments today</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($today_appointments as $apt): ?>
                                <tr>
                                    <td><?= date('g:i A', strtotime($apt['appointment_time'])) ?></td>
                                    <td><?= htmlspecialchars($apt['pat_first_name'] . ' ' . $apt['pat_last_name']) ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                                            background: <?= $apt['status_color'] ?? '#e0e0e0' ?>20; 
                                            color: <?= $apt['status_color'] ?? '#666' ?>;">
                                            <?= htmlspecialchars($apt['status_name']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Today's Schedule -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">⏰ My Schedule Today</h2>
                <a href="/doctor/schedules" class="btn btn-primary btn-sm">Manage</a>
            </div>
            
            <?php if (empty($today_schedule)): ?>
                <p style="color: #999; text-align: center; padding: 20px;">No schedule for today</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($today_schedule as $schedule): ?>
                                <tr>
                                    <td><?= date('g:i A', strtotime($schedule['start_time'])) ?></td>
                                    <td><?= date('g:i A', strtotime($schedule['end_time'])) ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                                            background: <?= $schedule['is_available'] ? '#d4edda' : '#f8d7da' ?>; 
                                            color: <?= $schedule['is_available'] ? '#155724' : '#721c24' ?>;">
                                            <?= $schedule['is_available'] ? 'Yes' : 'No' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">🗓️ Upcoming Appointments</h2>
            <a href="/doctor/appointments/future" class="btn btn-primary btn-sm">View All</a>
        </div>
        
        <?php if (empty($upcoming_appointments)): ?>
            <p style="color: #999; text-align: center; padding: 20px;">No upcoming appointments</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcoming_appointments as $apt): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($apt['appointment_date'])) ?></td>
                                <td><?= date('g:i A', strtotime($apt['appointment_time'])) ?></td>
                                <td><?= htmlspecialchars($apt['pat_first_name'] . ' ' . $apt['pat_last_name']) ?></td>
                                <td><?= htmlspecialchars($apt['status_name']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 30px;">
        <a href="/doctor/appointments/today" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-decoration: none; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
            <div style="font-size: 32px; margin-bottom: 10px;">📊</div>
            <div style="font-size: 16px; font-weight: 600;">Today's Appointments</div>
        </a>
        
        <a href="/doctor/schedules" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px; text-decoration: none; text-align: center; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);">
            <div style="font-size: 32px; margin-bottom: 10px;">⏰</div>
            <div style="font-size: 16px; font-weight: 600;">My Schedules</div>
        </a>
        
        <a href="/doctor/schedules/manage" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px; text-decoration: none; text-align: center; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);">
            <div style="font-size: 32px; margin-bottom: 10px;">🗓️</div>
            <div style="font-size: 16px; font-weight: 600;">All Schedules</div>
        </a>
        
        <a href="/doctor/doctors" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px; text-decoration: none; text-align: center; box-shadow: 0 4px 15px rgba(67, 233, 123, 0.3);">
            <div style="font-size: 32px; margin-bottom: 10px;">👨‍⚕️</div>
            <div style="font-size: 16px; font-weight: 600;">Manage Doctors</div>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
