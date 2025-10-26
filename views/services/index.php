<?php
$pageTitle = "Services Management - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['superadmin', 'staff']);
require_once __DIR__ . '/../../classes/Service.php';

$service = new Service();
$services = $service->getAll();
?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-concierge-bell mr-2"></i>Services Management
        </h1>
        <?php if ($auth->isSuperAdmin()): ?>
            <a href="/views/services/create.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                <i class="fas fa-plus mr-2"></i>Add New Service
            </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($services as $service): ?>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($service['service_name']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($service['service_description'] ?? 'No description'); ?></p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Price:</span>
                        <span class="text-lg font-bold text-blue-600"><?php echo formatCurrency($service['service_price']); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Duration:</span>
                        <span class="text-gray-900"><?php echo $service['service_duration_minutes']; ?> min</span>
                    </div>
                    <?php if ($service['service_category']): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Category:</span>
                            <span class="text-gray-900"><?php echo htmlspecialchars($service['service_category']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($auth->isSuperAdmin()): ?>
                    <div class="flex space-x-2">
                        <a href="/views/services/edit.php?id=<?php echo $service['service_id']; ?>" 
                           class="flex-1 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition duration-300 text-center">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="/views/services/delete.php?id=<?php echo $service['service_id']; ?>" 
                           class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 text-center"
                           data-confirm="Are you sure you want to delete this service?">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($services)): ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
            <i class="fas fa-concierge-bell text-6xl mb-4"></i>
            <p>No services found. Add your first service to get started.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
