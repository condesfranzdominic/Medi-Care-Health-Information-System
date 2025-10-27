<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1><?= $specialization ? htmlspecialchars($specialization['spec_name']) . ' Doctors' : 'Doctors' ?></h1>
    <p><a href="/staff/specializations" class="btn">← Back to Specializations</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($specialization): ?>
    <div style="background: #e3f2fd; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #2196f3;">
        <h3 style="margin: 0 0 10px 0;">About <?= htmlspecialchars($specialization['spec_name']) ?></h3>
        <p style="margin: 0; color: #555;">
            <?= htmlspecialchars($specialization['spec_description'] ?? 'No description available') ?>
        </p>
    </div>
    <?php endif; ?>
    
    <!-- Doctors List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Doctors Specializing in <?= htmlspecialchars($specialization['spec_name'] ?? 'This Field') ?></h2>
        
        <?php if (empty($doctors)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">No doctors found for this specialization.</p>
        <?php else: ?>
            <p style="margin-bottom: 20px; color: #666;">Total: <?= count($doctors) ?> doctor(s)</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
                <?php foreach ($doctors as $doctor): ?>
                    <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #fafafa;">
                        <h3 style="margin: 0 0 10px 0; color: #333;">
                            Dr. <?= htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']) ?>
                        </h3>
                        
                        <div style="margin: 10px 0; font-size: 14px; color: #666;">
                            <p style="margin: 5px 0;">
                                <strong>Email:</strong> <?= htmlspecialchars($doctor['doc_email']) ?>
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>Phone:</strong> <?= htmlspecialchars($doctor['doc_phone'] ?? 'N/A') ?>
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>License:</strong> <?= htmlspecialchars($doctor['doc_license_number'] ?? 'N/A') ?>
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>Experience:</strong> <?= htmlspecialchars($doctor['doc_experience_years'] ?? '0') ?> years
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>Consultation Fee:</strong> ₱<?= number_format($doctor['doc_consultation_fee'] ?? 0, 2) ?>
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>Total Appointments:</strong> <?= $doctor['total_appointments'] ?>
                            </p>
                            <p style="margin: 5px 0;">
                                <strong>Status:</strong> 
                                <span style="color: <?= $doctor['doc_status'] === 'active' ? 'green' : 'red' ?>;">
                                    <?= ucfirst($doctor['doc_status'] ?? 'active') ?>
                                </span>
                            </p>
                        </div>
                        
                        <?php if (!empty($doctor['doc_bio'])): ?>
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                            <p style="margin: 0; font-size: 13px; color: #555; font-style: italic;">
                                <?= htmlspecialchars($doctor['doc_bio']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
