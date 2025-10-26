<?php
$pageTitle = "Edit Service - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['superadmin']);
$db = Database::getInstance();

$serviceId = intval($_GET['id'] ?? 0);
$errors = [];
$service = $db->fetchOne("SELECT * FROM services WHERE service_id = :id", ['id' => $serviceId]);

if (!$service) {
    setFlashMessage('error', 'Service not found.');
    redirect('/views/services/index.php');  // Fixed path
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'service_name' => sanitize($_POST['service_name'] ?? ''),
        'service_description' => sanitize($_POST['service_description'] ?? ''),
        'service_price' => sanitize($_POST['service_price'] ?? ''),
        'service_duration_minutes' => intval($_POST['service_duration_minutes'] ?? 30),
        'service_category' => sanitize($_POST['service_category'] ?? '')
    ];
    
    if (empty($formData['service_name'])) $errors[] = 'Service name is required.';
    if (empty($formData['service_price'])) $errors[] = 'Service price is required.';
    
    if (empty($errors)) {
        try {
            $sql = "UPDATE services SET service_name = :name, service_description = :description, 
                    service_price = :price, service_duration_minutes = :duration, 
                    service_category = :category WHERE service_id = :id";
            
            $db->execute($sql, [
                'name' => $formData['service_name'],
                'description' => $formData['service_description'] ?: null,
                'price' => $formData['service_price'],
                'duration' => $formData['service_duration_minutes'],
                'category' => $formData['service_category'] ?: null,
                'id' => $serviceId
            ]);
            
            setFlashMessage('success', 'Service updated successfully.');
            redirect('/views/services/index.php');  // Fixed path
        } catch (Exception $e) {
            $errors[] = 'Failed to update service: ' . $e->getMessage();
        }
    }
    $service = array_merge($service, $formData);
}
?>

<div class="container mx-auto max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-edit mr-2"></i>Edit Service
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Service Name *</label>
                <input type="text" name="service_name" required value="<?php echo htmlspecialchars($service['service_name']); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="service_description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($service['service_description'] ?? ''); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Price (₱) *</label>
                    <input type="number" name="service_price" step="0.01" min="0" required
                           value="<?php echo htmlspecialchars($service['service_price']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Duration (minutes)</label>
                    <input type="number" name="service_duration_minutes" min="15" step="15"
                           value="<?php echo htmlspecialchars($service['service_duration_minutes']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Category</label>
                <input type="text" name="service_category" value="<?php echo htmlspecialchars($service['service_category'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/views/services/index.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Update Service
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
