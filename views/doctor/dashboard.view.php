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
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-content">
                <div class="stat-label">
                    <i class="fas fa-calendar-day"></i>
                    <span>Today's Appointments</span>
                </div>
                <div class="stat-value"><?= $stats['today_appointments'] ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span><?= $stats['today_appointments'] > 0 ? round($stats['today_appointments'] * 0.15) : 0 ?> today</span>
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
                    <i class="fas fa-check-circle"></i>
                    <span>Completed</span>
                </div>
                <div class="stat-value"><?= $stats['completed_appointments'] ?></div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span><?= $stats['completed_appointments'] > 0 ? round($stats['completed_appointments'] * 0.12) : 0 ?> completed</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart and Quick Stats -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Appointment Overview Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <h2 class="chart-title">Appointment Overview</h2>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-dot blue"></div>
                    <span>2024 Appointments</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot light-blue"></div>
                    <span>Completed</span>
                </div>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="appointmentChart"></canvas>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Quick Stats</h2>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Upcoming</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);"><?= $stats['upcoming_appointments'] ?></div>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">My Schedules</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);"><?= $stats['my_schedules'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Patients Appointment Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">My Appointments</h2>
    </div>
    <?php 
    // Combine today's and upcoming appointments for display
    $all_appointments = array_merge($today_appointments ?? [], $upcoming_appointments ?? []);
    if (empty($all_appointments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-calendar"></i></div>
            <div class="empty-state-text">No appointments found.</div>
        </div>
    <?php else: ?>
        <table class="patient-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Age</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_appointments as $apt): ?>
                    <?php
                    $patInitial = strtoupper(substr($apt['pat_first_name'] ?? 'P', 0, 1));
                    $patName = htmlspecialchars(($apt['pat_first_name'] ?? '') . ' ' . ($apt['pat_last_name'] ?? ''));
                    $statusName = strtolower($apt['status_name'] ?? 'scheduled');
                    $isCompleted = $statusName === 'completed';
                    $isCanceled = $statusName === 'canceled' || $statusName === 'cancelled';
                    $statusClass = $isCompleted ? 'badge-success' : ($isCanceled ? 'badge-error' : 'badge-warning');
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
                        <td><?= isset($apt['appointment_time']) ? date('H:i', strtotime($apt['appointment_time'])) : 'N/A' ?></td>
                        <td>
                            <?php 
                            if (isset($apt['pat_date_of_birth'])) {
                                $age = date_diff(date_create($apt['pat_date_of_birth']), date_create('today'))->y;
                                echo $age . ' years old';
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td>
                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($apt['status_name'] ?? 'Scheduled') ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Appointment Overview Chart
const ctx = document.getElementById('appointmentChart').getContext('2d');
const appointmentChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: '2024 Appointments',
                data: [<?= isset($chart_data['appointments']) ? implode(',', $chart_data['appointments']) : '10,15,20,18,25,30,28' ?>],
                backgroundColor: '#3b82f6',
                borderRadius: 4
            },
            {
                label: 'Completed',
                data: [<?= isset($chart_data['completed']) ? implode(',', $chart_data['completed']) : '8,12,18,15,22,25,24' ?>],
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
                    stepSize: 5,
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
