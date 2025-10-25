<?php
$pageTitle = "User Management - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin']);
$db = Database::getInstance();

$users = $db->fetchAll("
    SELECT u.*, 
           p.pat_first_name, p.pat_last_name,
           s.staff_first_name, s.staff_last_name,
           d.doc_first_name, d.doc_last_name
    FROM users u
    LEFT JOIN patients p ON u.pat_id = p.pat_id
    LEFT JOIN staff s ON u.staff_id = s.staff_id
    LEFT JOIN doctors d ON u.doc_id = d.doc_id
    ORDER BY u.user_id DESC
");
?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-users-cog mr-2"></i>User Management
        </h1>
        <a href="/users/create.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fas fa-plus mr-2"></i>Create User
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                        <?php
                        $role = 'Unknown';
                        $name = 'N/A';
                        if ($user['user_is_superadmin']) {
                            $role = 'Super Admin';
                        } elseif ($user['staff_id']) {
                            $role = 'Staff';
                            $name = $user['staff_first_name'] . ' ' . $user['staff_last_name'];
                        } elseif ($user['doc_id']) {
                            $role = 'Doctor';
                            $name = 'Dr. ' . $user['doc_first_name'] . ' ' . $user['doc_last_name'];
                        } elseif ($user['pat_id']) {
                            $role = 'Patient';
                            $name = $user['pat_first_name'] . ' ' . $user['pat_last_name'];
                        }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $user['user_id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($user['user_email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($role); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo formatDateTime($user['created_at'], 'M d, Y'); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/users/delete.php?id=<?php echo $user['user_id']; ?>" 
                                   class="text-red-600 hover:text-red-900"
                                   data-confirm="Are you sure you want to delete this user?">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
