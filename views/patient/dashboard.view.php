<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
    .patient-dashboard {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .dashboard-header {
        margin-bottom: 2rem;
    }
    
    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .welcome-section h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .welcome-section p {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid var(--primary-blue);
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stat-card.appointments {
        border-left-color: #3b82f6;
    }
    
    .stat-card.completed {
        border-left-color: #10b981;
    }
    
    .stat-card.payments {
        border-left-color: #f59e0b;
    }
    
    .stat-card.pending {
        border-left-color: #ef4444;
    }
    
    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-card.appointments .stat-card-icon {
        background: #3b82f6;
    }
    
    .stat-card.completed .stat-card-icon {
        background: #10b981;
    }
    
    .stat-card.payments .stat-card-icon {
        background: #f59e0b;
    }
    
    .stat-card.pending .stat-card-icon {
        background: #ef4444;
    }
    
    .stat-card-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .stat-card-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-top: 0.5rem;
    }
    
    .dashboard-section {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--primary-blue);
    }
    
    .section-action {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: color 0.2s;
    }
    
    .section-action:hover {
        color: var(--primary-blue-dark);
        text-decoration: underline;
    }
    
    .appointment-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
        background: #f9fafb;
    }
    
    .appointment-card:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);
    }
    
    .appointment-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    
    .appointment-doctor {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .doctor-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
    }
    
    .doctor-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }
    
    .doctor-info p {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
    }
    
    .appointment-status {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-scheduled {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-confirmed {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .appointment-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }
    
    .detail-item i {
        color: #6b7280;
        width: 16px;
    }
    
    .detail-item span {
        color: #374151;
    }
    
    .appointment-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: var(--primary-blue);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--primary-blue-dark);
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
    }
    
    .btn-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-danger:hover {
        background: #fecaca;
    }
    
    .record-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: #f9fafb;
    }
    
    .record-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    
    .record-date {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .record-doctor {
        font-size: 0.875rem;
        color: #374151;
        font-weight: 600;
    }
    
    .record-summary {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
        line-height: 1.5;
    }
    
    .record-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .payment-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: #f9fafb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .payment-info {
        flex: 1;
    }
    
    .payment-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .payment-details {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .payment-status {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state-text {
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .quick-action-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        text-decoration: none;
        color: #1f2937;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    
    .quick-action-card:hover {
        border-color: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .quick-action-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .quick-action-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .patient-dashboard {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .appointment-details {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="patient-dashboard">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1>Welcome, <?= htmlspecialchars($patient['pat_first_name'] ?? 'Patient') ?>! 👋</h1>
        <p>Hello there! Welcome to Medi-Care. How can we assist you today?</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card appointments">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-label">Total Appointments</div>
                    <div class="stat-card-value"><?= $stats['total_appointments'] ?></div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card completed">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-label">Upcoming</div>
                    <div class="stat-card-value"><?= $stats['upcoming_appointments'] ?></div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card payments">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-label">Total Payments</div>
                    <div class="stat-card-value">₱<?= number_format($stats['total_payments'], 2) ?></div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-label">Pending Payments</div>
                    <div class="stat-card-value"><?= $stats['pending_payments'] ?></div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="/patient/book" class="quick-action-card" aria-label="Book New Appointment">
            <div class="quick-action-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3 class="quick-action-title">Book</h3>
        </a>
        
        <a href="/patient/appointments" class="quick-action-card" aria-label="View All Appointments">
            <div class="quick-action-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <h3 class="quick-action-title">My Appointments</h3>
        </a>
        
        <a href="/patient/medical-records" class="quick-action-card" aria-label="View Medical Records">
            <div class="quick-action-icon">
                <i class="fas fa-file-medical"></i>
            </div>
            <h3 class="quick-action-title">Medical Records</h3>
        </a>
        
        <a href="/patient/payments" class="quick-action-card" aria-label="View Payments">
            <div class="quick-action-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="quick-action-title">Payments</h3>
        </a>
    </div>
    
    <!-- Upcoming Appointments -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-calendar-check"></i>
                Upcoming Appointments
            </h2>
            <a href="/patient/appointments" class="section-action">View All</a>
        </div>
        
        <?php if (empty($upcoming_appointments)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
                <div class="empty-state-text">No upcoming appointments</div>
                <a href="/patient/appointments/create" class="btn-action btn-primary">Book Your First Appointment</a>
            </div>
        <?php else: ?>
            <?php foreach ($upcoming_appointments as $apt): ?>
                <?php
                $statusName = strtolower($apt['status_name'] ?? 'scheduled');
                $statusClass = 'status-' . $statusName;
                $docInitial = strtoupper(substr($apt['doc_first_name'] ?? 'D', 0, 1));
                $docName = 'Dr. ' . htmlspecialchars(($apt['doc_first_name'] ?? '') . ' ' . ($apt['doc_last_name'] ?? ''));
                $specName = htmlspecialchars($apt['spec_name'] ?? 'General Practice');
                ?>
                <div class="appointment-card">
                    <div class="appointment-card-header">
                        <div class="appointment-doctor">
                            <div class="doctor-avatar"><?= $docInitial ?></div>
                            <div class="doctor-info">
                                <h3><?= $docName ?></h3>
                                <p><?= $specName ?></p>
                            </div>
                        </div>
                        <span class="appointment-status <?= $statusClass ?>"><?= htmlspecialchars($apt['status_name'] ?? 'Scheduled') ?></span>
                    </div>
                    
                    <div class="appointment-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><?= date('M j, Y', strtotime($apt['appointment_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span><?= date('g:i A', strtotime($apt['appointment_time'])) ?></span>
                        </div>
                        <?php if ($apt['service_name']): ?>
                        <div class="detail-item">
                            <i class="fas fa-stethoscope"></i>
                            <span><?= htmlspecialchars($apt['service_name']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="appointment-actions">
                        <a href="/patient/appointments?view=<?= $apt['appointment_id'] ?>" class="btn-action btn-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <?php if ($statusName !== 'cancelled' && $statusName !== 'completed'): ?>
                        <a href="/patient/appointments/create?reschedule=<?= htmlspecialchars($apt['appointment_id']) ?>" class="btn-action btn-secondary">
                            <i class="fas fa-calendar-alt"></i> Reschedule
                        </a>
                        <button type="button" onclick="cancelAppointment('<?= htmlspecialchars($apt['appointment_id']) ?>')" class="btn-action btn-danger">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Recent Medical Records -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-file-medical"></i>
                Recent Medical Records
            </h2>
            <a href="/patient/medical-records" class="section-action">View All</a>
        </div>
        
        <?php if (empty($recent_records)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-file-medical"></i></div>
                <div class="empty-state-text">No medical records yet</div>
            </div>
        <?php else: ?>
            <?php foreach ($recent_records as $record): ?>
                <div class="record-card">
                    <div class="record-card-header">
                        <div>
                            <div class="record-date">
                                <i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($record['record_date'])) ?>
                            </div>
                            <div class="record-doctor">
                                Dr. <?= htmlspecialchars(($record['doc_first_name'] ?? '') . ' ' . ($record['doc_last_name'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($record['diagnosis']): ?>
                    <div class="record-summary">
                        <strong>Diagnosis:</strong> <?= htmlspecialchars(substr($record['diagnosis'], 0, 150)) ?><?= strlen($record['diagnosis']) > 150 ? '...' : '' ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="record-actions">
                        <a href="/patient/medical-records?view=<?= $record['record_id'] ?>" class="btn-action btn-primary">
                            <i class="fas fa-eye"></i> View Full Record
                        </a>
                        <?php if ($record['prescription']): ?>
                        <a href="/patient/medical-records?prescription=<?= $record['record_id'] ?>" class="btn-action btn-secondary">
                            <i class="fas fa-pills"></i> View Prescription
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Recent Payments -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-credit-card"></i>
                Recent Payments
            </h2>
            <a href="/patient/payments" class="section-action">View All</a>
        </div>
        
        <?php if (empty($recent_payments)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-credit-card"></i></div>
                <div class="empty-state-text">No payment history</div>
            </div>
        <?php else: ?>
            <?php foreach ($recent_payments as $payment): ?>
                <?php
                $statusName = strtolower($payment['status_name'] ?? 'pending');
                $statusClass = 'status-' . $statusName;
                ?>
                <div class="payment-card">
                    <div class="payment-info">
                        <div class="payment-amount">₱<?= number_format($payment['payment_amount'], 2) ?></div>
                        <div class="payment-details">
                            <i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($payment['payment_date'])) ?>
                            <?php if ($payment['method_name']): ?>
                                • <i class="fas fa-credit-card"></i> <?= htmlspecialchars($payment['method_name']) ?>
                            <?php endif; ?>
                            <?php if ($payment['appointment_id']): ?>
                                • <i class="fas fa-file-alt"></i> <?= htmlspecialchars($payment['appointment_id']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <span class="payment-status <?= $statusClass ?>"><?= htmlspecialchars($payment['status_name'] ?? 'Pending') ?></span>
                        <?php if ($statusName === 'paid'): ?>
                        <a href="/patient/payments?receipt=<?= $payment['payment_id'] ?>" class="btn-action btn-secondary" style="margin-top: 0.5rem; display: block;">
                            <i class="fas fa-download"></i> Receipt
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function cancelAppointment(appointmentId) {
    showConfirm(
        'Are you sure you want to cancel this appointment? This action cannot be undone.',
        'Cancel Appointment',
        'Yes, Cancel',
        'No, Keep It',
        'danger'
    ).then(confirmed => {
        if (confirmed) {
            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/patient/appointments';
            form.style.display = 'none';
            
            // Add action field
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'cancel';
            form.appendChild(actionInput);
            
            // Add appointment_id field
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'appointment_id';
            idInput.value = appointmentId;
            form.appendChild(idInput);
            
            // Append to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    });
    return false;
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

