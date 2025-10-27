<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1>Browse Specializations</h1>
    <p><a href="/staff/dashboard" class="btn">← Back to Dashboard</a></p>
    
    <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <!-- Specializations List -->
    <div style="background: #fff; padding: 25px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>All Specializations</h2>
        <?php if (empty($specializations)): ?>
            <p>No specializations found.</p>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php foreach ($specializations as $spec): ?>
                    <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #f9f9f9;">
                        <h3 style="margin: 0 0 10px 0; color: #667eea;"><?= htmlspecialchars($spec['spec_name']) ?></h3>
                        <p style="color: #666; font-size: 14px; margin: 10px 0;">
                            <?= htmlspecialchars($spec['spec_description'] ?? 'No description available') ?>
                        </p>
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                            <p style="margin: 5px 0; font-size: 14px;">
                                <strong>Doctors:</strong> <?= $spec['doctor_count'] ?>
                            </p>
                            <?php if ($spec['doctor_count'] > 0): ?>
                                <a href="/staff/specialization-doctors?id=<?= $spec['spec_id'] ?>" class="btn" style="margin-top: 10px; display: inline-block; font-size: 13px; padding: 8px 15px;">
                                    Browse <?= htmlspecialchars($spec['spec_name']) ?> Doctors →
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
