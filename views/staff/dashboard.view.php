<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Staff</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['total_staff'] ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">👔</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Services</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['total_services'] ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">🔬</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Specializations</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['total_specializations'] ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">🎓</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(67, 233, 123, 0.4);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Payment Methods</p>
                <h2 style="margin: 10px 0 0 0; font-size: 36px;"><?= $stats['total_payment_methods'] ?></h2>
            </div>
            <div style="font-size: 40px; opacity: 0.3;">💳</div>
        </div>
    </div>
</div>

<!-- Recent Services -->
<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Recent Services</h2>
        <?php if (empty($recent_services)): ?>
            <p>No services found.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['service_id']) ?></td>
                            <td><?= htmlspecialchars($service['service_name']) ?></td>
                            <td><?= htmlspecialchars($service['service_category'] ?? 'N/A') ?></td>
                            <td>₱<?= number_format($service['service_price'] ?? 0, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
