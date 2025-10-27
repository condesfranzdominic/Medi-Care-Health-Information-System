<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <h1>Medical Records (View Only)</h1>
    <p><a href="/staff/dashboard" class="btn">← Back to Dashboard</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; color: #856404;">
        <p style="margin: 0;"><strong>Note:</strong> Staff can view medical records but cannot create, edit, or delete them. Only doctors can create/edit records, and only superadmin can delete them.</p>
    </div>
    
    <!-- Medical Records List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Medical Records</h2>
        <?php if (empty($records)): ?>
            <p>No medical records found.</p>
        <?php else: ?>
            <p style="margin-bottom: 20px; color: #666;">Total: <?= count($records) ?> records</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['record_id']) ?></td>
                            <td><?= htmlspecialchars($record['record_date']) ?></td>
                            <td><?= htmlspecialchars($record['pat_first_name'] . ' ' . $record['pat_last_name']) ?></td>
                            <td>Dr. <?= htmlspecialchars($record['doc_first_name'] . ' ' . $record['doc_last_name']) ?></td>
                            <td><?= htmlspecialchars(substr($record['diagnosis'], 0, 50)) ?><?= strlen($record['diagnosis']) > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars(substr($record['treatment'], 0, 50)) ?><?= strlen($record['treatment']) > 50 ? '...' : '' ?></td>
                            <td><?= htmlspecialchars($record['follow_up_date'] ?? 'N/A') ?></td>
                            <td>
                                <button onclick="viewRecord(<?= htmlspecialchars(json_encode($record)) ?>)" class="btn" style="font-size: 12px; padding: 5px 10px;">View Details</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: #fff; max-width: 800px; margin: 50px auto; padding: 30px; border-radius: 8px;">
        <h2>Medical Record Details</h2>
        <div id="viewContent"></div>
        <button type="button" onclick="closeViewModal()" class="btn">Close</button>
    </div>
</div>

<script>
function viewRecord(record) {
    const content = `
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 15px 0;">Record Information</h3>
            <p style="margin: 5px 0;"><strong>Record ID:</strong> ${record.record_id}</p>
            <p style="margin: 5px 0;"><strong>Date:</strong> ${record.record_date}</p>
            <p style="margin: 5px 0;"><strong>Patient:</strong> ${record.pat_first_name} ${record.pat_last_name}</p>
            <p style="margin: 5px 0;"><strong>Doctor:</strong> Dr. ${record.doc_first_name} ${record.doc_last_name}</p>
            <p style="margin: 5px 0;"><strong>Appointment ID:</strong> ${record.appointment_id || 'N/A'}</p>
            <p style="margin: 5px 0;"><strong>Appointment Date:</strong> ${record.appointment_date || 'N/A'}</p>
        </div>
        
        <div style="margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #667eea;">Diagnosis</h4>
            <p style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; white-space: pre-wrap;">${record.diagnosis}</p>
        </div>
        
        <div style="margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #667eea;">Treatment</h4>
            <p style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; white-space: pre-wrap;">${record.treatment}</p>
        </div>
        
        <div style="margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #667eea;">Prescription</h4>
            <p style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; white-space: pre-wrap;">${record.prescription || 'None'}</p>
        </div>
        
        <div style="margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #667eea;">Notes</h4>
            <p style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; white-space: pre-wrap;">${record.notes || 'None'}</p>
        </div>
        
        <div style="margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Follow-up Date:</strong> ${record.follow_up_date || 'Not scheduled'}</p>
        </div>
    `;
    document.getElementById('viewContent').innerHTML = content;
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
