<?php require_once __DIR__ . '/partials/header.php'; ?>

<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <h1>Manage Payments</h1>
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
    
    <!-- Create New Payment -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Add New Payment Record</h2>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Appointment ID: *</label>
                    <input type="text" name="appointment_id" required placeholder="e.g., 2025-10-0000001">
                </div>
                <div class="form-group">
                    <label>Amount (₱): *</label>
                    <input type="number" name="amount" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Payment Date: *</label>
                    <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Payment Method: *</label>
                    <select name="payment_method_id" required>
                        <option value="">Select Method</option>
                        <?php foreach ($payment_methods as $method): ?>
                            <option value="<?= $method['method_id'] ?>"><?= htmlspecialchars($method['method_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Status: *</label>
                    <select name="payment_status_id" required>
                        <option value="">Select Status</option>
                        <?php foreach ($payment_statuses as $status): ?>
                            <option value="<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="notes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Add Payment Record</button>
        </form>
    </div>
    
    <!-- Payments List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Payment Records</h2>
        <?php if (empty($payments)): ?>
            <p>No payment records found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                            <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                            <td><?= htmlspecialchars($payment['appointment_id']) ?></td>
                            <td><?= htmlspecialchars(($payment['pat_first_name'] ?? '') . ' ' . ($payment['pat_last_name'] ?? '')) ?></td>
                            <td><strong>₱<?= number_format($payment['amount'], 2) ?></strong></td>
                            <td><?= htmlspecialchars($payment['method_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($payment['status_name'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="viewPayment(<?= htmlspecialchars(json_encode($payment)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">View</button>
                                <button onclick="editPayment(<?= htmlspecialchars(json_encode($payment)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $payment['payment_id'] ?>">
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

<!-- View Modal -->
<div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: #fff; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Payment Details</h2>
        <div id="viewContent"></div>
        <button type="button" onclick="closeViewModal()" class="btn">Close</button>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 700px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Edit Payment Record</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Amount (₱): *</label>
                    <input type="number" name="amount" id="edit_amount" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Payment Date: *</label>
                    <input type="date" name="payment_date" id="edit_payment_date" required>
                </div>
                <div class="form-group">
                    <label>Payment Method: *</label>
                    <select name="payment_method_id" id="edit_payment_method_id" required>
                        <?php foreach ($payment_methods as $method): ?>
                            <option value="<?= $method['method_id'] ?>"><?= htmlspecialchars($method['method_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Status: *</label>
                    <select name="payment_status_id" id="edit_payment_status_id" required>
                        <?php foreach ($payment_statuses as $status): ?>
                            <option value="<?= $status['status_id'] ?>"><?= htmlspecialchars($status['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="notes" id="edit_notes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update Payment</button>
            <button type="button" onclick="closeEditModal()" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script>
function viewPayment(payment) {
    const content = `
        <p><strong>Payment ID:</strong> ${payment.payment_id}</p>
        <p><strong>Appointment ID:</strong> ${payment.appointment_id}</p>
        <p><strong>Patient:</strong> ${payment.pat_first_name || ''} ${payment.pat_last_name || ''}</p>
        <p><strong>Amount:</strong> ₱${parseFloat(payment.amount).toFixed(2)}</p>
        <p><strong>Payment Date:</strong> ${payment.payment_date}</p>
        <p><strong>Payment Method:</strong> ${payment.method_name || 'N/A'}</p>
        <p><strong>Payment Status:</strong> ${payment.status_name || 'N/A'}</p>
        <p><strong>Notes:</strong> ${payment.notes || 'None'}</p>
    `;
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function editPayment(payment) {
    document.getElementById('edit_id').value = payment.payment_id;
    document.getElementById('edit_amount').value = payment.amount;
    document.getElementById('edit_payment_date').value = payment.payment_date;
    document.getElementById('edit_payment_method_id').value = payment.payment_method_id;
    document.getElementById('edit_payment_status_id').value = payment.payment_status_id;
    document.getElementById('edit_notes').value = payment.notes || '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
