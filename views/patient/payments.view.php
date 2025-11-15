<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
    .payments-page {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .summary-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid var(--primary-blue);
    }
    
    .summary-card.paid {
        border-left-color: #10b981;
    }
    
    .summary-card.pending {
        border-left-color: #f59e0b;
    }
    
    .summary-card-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .summary-card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .search-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        background: white;
        padding: 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .search-input-wrapper {
        flex: 1;
        position: relative;
    }
    
    .search-input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
    }
    
    .search-input-wrapper input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }
    
    .payments-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .payment-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }
    
    .payment-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        border-color: var(--primary-blue);
    }
    
    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .payment-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .payment-status {
        padding: 0.5rem 1rem;
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
    
    .status-refunded {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .payment-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .detail-item i {
        color: #9ca3af;
        width: 16px;
    }
    
    .detail-item strong {
        color: #374151;
        font-weight: 600;
    }
    
    .payment-actions {
        display: flex;
        gap: 0.5rem;
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
    
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state-text {
        font-size: 1.125rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
</style>

<div class="payments-page">
    <div class="page-header" style="margin-bottom: 2rem;">
        <h1 class="page-title" style="margin: 0;">My Payments</h1>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>
    
    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #8b5cf6;"></div>
                <span style="font-size: 0.875rem; color: var(--text-secondary);">Total Payments</span>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);"><?= $stats['total'] ?? 0 ?></div>
        </div>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></div>
                <span style="font-size: 0.875rem; color: var(--text-secondary);">Paid</span>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);"><?= $stats['paid'] ?? 0 ?></div>
        </div>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></div>
                <span style="font-size: 0.875rem; color: var(--text-secondary);">Pending</span>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);"><?= $stats['pending'] ?? 0 ?></div>
        </div>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6;"></div>
                <span style="font-size: 0.875rem; color: var(--text-secondary);">Total Amount</span>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary);">₱<?= number_format($stats['total_amount'] ?? 0, 0) ?></div>
        </div>
    </div>
    
    <?php if (empty($payments)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-credit-card"></i></div>
            <div class="empty-state-text">No payment records found</div>
        </div>
    <?php else: ?>
        <div class="payments-list">
            <?php foreach ($payments as $payment): ?>
                <?php
                $statusName = strtolower($payment['status_name'] ?? 'pending');
                $statusClass = 'status-' . $statusName;
                ?>
                <div class="payment-card">
                    <div class="payment-header">
                        <div class="payment-amount">₱<?= number_format($payment['payment_amount'], 2) ?></div>
                        <span class="payment-status <?= $statusClass ?>"><?= htmlspecialchars($payment['status_name'] ?? 'Pending') ?></span>
                    </div>
                    
                    <div class="payment-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Date:</strong> <?= date('M j, Y', strtotime($payment['payment_date'])) ?></span>
                        </div>
                        <?php if ($payment['appointment_id']): ?>
                        <div class="detail-item">
                            <i class="fas fa-file-alt"></i>
                            <span><strong>Appointment:</strong> <?= htmlspecialchars($payment['appointment_id']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($payment['method_name']): ?>
                        <div class="detail-item">
                            <i class="fas fa-credit-card"></i>
                            <span><strong>Method:</strong> <?= htmlspecialchars($payment['method_name']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($payment['payment_reference']): ?>
                        <div class="detail-item">
                            <i class="fas fa-hashtag"></i>
                            <span><strong>Reference:</strong> <?= htmlspecialchars($payment['payment_reference']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($payment['payment_notes']): ?>
                    <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                        <strong>Notes:</strong> <?= htmlspecialchars($payment['payment_notes']) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="payment-actions">
                        <?php if ($statusName === 'paid'): ?>
                        <button class="btn-action btn-primary" onclick="downloadReceipt(<?= $payment['payment_id'] ?>)">
                            <i class="fas fa-download"></i> Download Receipt
                        </button>
                        <button class="btn-action btn-secondary" onclick="printReceipt(<?= $payment['payment_id'] ?>)">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <?php elseif ($statusName === 'pending'): ?>
                        <button class="btn-action btn-primary" onclick="makePayment(<?= $payment['payment_id'] ?>)">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </button>
                        <?php endif; ?>
                        <button class="btn-action btn-secondary" onclick="viewDetails(<?= $payment['payment_id'] ?>)">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function downloadReceipt(paymentId) {
    // TODO: Implement receipt download
    window.location.href = '/patient/payments?receipt=' + paymentId + '&download=1';
}

function printReceipt(paymentId) {
    // TODO: Implement receipt printing
    window.location.href = '/patient/payments?receipt=' + paymentId + '&print=1';
}

function makePayment(paymentId) {
    // TODO: Implement payment processing
    showConfirm(
        'Proceed to payment?',
        'Confirm Payment',
        'Proceed',
        'Cancel',
        'info'
    ).then(confirmed => {
        if (confirmed) {
            alert('Payment processing will be implemented here for payment ID: ' + paymentId);
        }
    });
}

function viewDetails(paymentId) {
    // TODO: Implement view details modal
    alert('View details for payment ID: ' + paymentId);
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

