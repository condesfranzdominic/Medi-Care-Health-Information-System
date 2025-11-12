<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

<!-- Statistics Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-user"></i>
                    <span>Total Patients</span>
                </div>
                <div class="stat-value"><?= $stats['total_patients'] ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span><?= $stats['total_patients'] > 0 ? round($stats['total_patients'] * 0.1) : 0 ?> patients</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-users"></i>
                    <span>Overall Visitors</span>
                </div>
                <div class="stat-value"><?= $stats['total_users'] ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span><?= $stats['total_users'] > 0 ? round($stats['total_users'] * 0.15) : 0 ?> visitors</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointment</span>
                </div>
                <div class="stat-value"><?= $stats['total_appointments'] ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span><?= $stats['total_appointments'] > 0 ? round($stats['total_appointments'] * 0.08) : 0 ?> appointment</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
</div>

<!-- Chart and Tables Row -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Patient Overview Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <h2 class="chart-title">Patient Overview</h2>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-dot blue"></div>
                    <span>2024 Patient health</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot light-blue"></div>
                    <span>Patient death</span>
                </div>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="patientChart"></canvas>
        </div>
    </div>
    
    <!-- Quick Stats or Additional Info -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Quick Stats</h2>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Total Doctors</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);"><?= $stats['total_doctors'] ?></div>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Total Staff</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);"><?= $stats['total_staff'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row - Tables -->
<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem;">
    <!-- Patients Appointment Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Patients Appointment</h2>
        </div>
        <?php if (empty($recent_appointments)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
                <div class="empty-state-text">No appointments found.</div>
            </div>
        <?php else: ?>
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Age</th>
                        <th>Date of birth</th>
                        <th>Insurance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_appointments as $apt): ?>
                        <?php
                        $patInitial = strtoupper(substr($apt['pat_first_name'] ?? 'P', 0, 1));
                        $patName = htmlspecialchars(($apt['pat_first_name'] ?? '') . ' ' . ($apt['pat_last_name'] ?? ''));
                        $patAge = isset($apt['pat_date_of_birth']) ? date_diff(date_create($apt['pat_date_of_birth']), date_create('today'))->y : 'N/A';
                        $patDOB = isset($apt['pat_date_of_birth']) ? date('d/m/Y', strtotime($apt['pat_date_of_birth'])) : 'N/A';
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
                            <td><?= isset($apt['appointment_date']) ? date('d/m/Y', strtotime($apt['appointment_date'])) : 'N/A' ?></td>
                            <td><?= $patAge ?> years old</td>
                            <td><?= $patDOB ?></td>
                            <td>
                                <span class="badge badge-primary"><?= isset($apt['pat_insurance_provider']) && $apt['pat_insurance_provider'] ? htmlspecialchars($apt['pat_insurance_provider']) : 'Non-Insurance' ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Doctor's Schedule List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Doctor's Schedule</h2>
        </div>
        <?php if (empty($doctors_schedule)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="empty-state-text">No schedules found.</div>
            </div>
        <?php else: ?>
            <div class="doctor-schedule-list">
                <?php foreach ($doctors_schedule as $schedule): ?>
                    <?php
                    $docInitial = strtoupper(substr($schedule['doc_first_name'] ?? 'D', 0, 1));
                    $docName = htmlspecialchars(($schedule['doc_first_name'] ?? '') . ' ' . ($schedule['doc_last_name'] ?? ''));
                    $specName = htmlspecialchars($schedule['spec_name'] ?? 'General Practice');
                    
                    // Format schedule date
                    if (isset($schedule['schedule_date']) && !empty($schedule['schedule_date'])) {
                        $scheduleDateObj = new DateTime($schedule['schedule_date']);
                        $today = new DateTime();
                        $today->setTime(0, 0, 0);
                        $scheduleDateObj->setTime(0, 0, 0);
                        
                        if ($scheduleDateObj == $today) {
                            $scheduleDate = 'Today';
                        } else {
                            $scheduleDate = $scheduleDateObj->format('l');
                        }
                    } else {
                        $scheduleDate = 'Today';
                    }
                    
                    $startTime = isset($schedule['start_time']) ? date('H:i', strtotime($schedule['start_time'])) : '08:00';
                    $endTime = isset($schedule['end_time']) ? date('H:i', strtotime($schedule['end_time'])) : '09:30';
                    ?>
                    <div class="schedule-item">
                        <div class="schedule-doctor-avatar"><?= $docInitial ?></div>
                        <div class="schedule-info">
                            <div class="schedule-doctor-name"><?= $docName ?: 'Unknown Doctor' ?></div>
                            <div class="schedule-specialty"><?= $specName ?></div>
                        </div>
                        <div class="schedule-time"><?= $scheduleDate ?> <?= $startTime ?>-<?= $endTime ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Patient Overview Chart
const ctx = document.getElementById('patientChart').getContext('2d');
const patientChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: '2024 Patient health',
                data: [<?= isset($chart_data['health']) ? implode(',', $chart_data['health']) : '100,150,200,180,250,300,280' ?>],
                backgroundColor: '#3b82f6',
                borderRadius: 4
            },
            {
                label: 'Patient death',
                data: [<?= isset($chart_data['death']) ? implode(',', $chart_data['death']) : '10,15,20,18,25,30,28' ?>],
                backgroundColor: '#60a5fa',
                borderRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 250,
                    callback: function(value) {
                        return value;
                    }
                },
                grid: {
                    color: '#e5e7eb'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
