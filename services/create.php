<?php
$pageTitle = "Add New Service - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin']);
$db = Database::getInstance();

$errors = [];
$formData = [];

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
            $sql = "INSERT INTO services (service_name, service_description, service_price, 
                    service_duration_minutes, service_category) 
                    VALUES (:name, :description, :price, :duration, :category)";
            
            $db->execute($sql, [
                'name' => $formData['service_name'],
                'description' => $formData['service_description'] ?: null,
                'price' => $formData['service_price'],
                'duration' => $formData['service_duration_minutes'],
                'category' => $formData['service_category'] ?: null
            ]);
            
            setFlashMessage('success', 'Service added successfully.');
            redirect('/services/index.php');
        } catch (Exception $e) {
            $errors[] = 'Failed to add service: ' . $e->getMessage();
        }
    }
}
?>

<div class="container mx-auto max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-plus mr-2"></i>Add New Service
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
                <input type="text" name="service_name" required
                       value="<?php echo htmlspecialchars($formData['service_name'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="service_description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($formData['service_description'] ?? ''); ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Price (₱) *</label>
                    <input type="number" name="service_price" step="0.01" min="0" required
                           value="<?php echo htmlspecialchars($formData['service_price'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Duration (minutes)</label>
                    <input type="number" name="service_duration_minutes" min="15" step="15"
                           value="<?php echo htmlspecialchars($formData['service_duration_minutes'] ?? 30); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Category</label>
                <input type="text" name="service_category"
                       value="<?php echo htmlspecialchars($formData['service_category'] ?? ''); ?>"
                       placeholder="e.g., Consultation, Laboratory, Imaging"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/services/index.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Save Service
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
