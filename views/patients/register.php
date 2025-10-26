<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Patient.php';
require_once __DIR__ . '/../../config/Database.php';

if (isset($_SESSION['user_type'])) {
    header('Location: ../../../dashboard/patient.php');
    exit();
}

$patient = new Patient();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Collect form data
    $rawPhone = trim($_POST['phone'] ?? '');
    $rawEmergencyPhone = trim($_POST['emergency_phone'] ?? '');

    $data = [
        'pat_first_name' => trim($_POST['first_name'] ?? ''),
        'pat_last_name' => trim($_POST['last_name'] ?? ''),
        'pat_email' => trim($_POST['email'] ?? ''),
        'pat_phone' => (formatPhoneNumber($rawPhone) ?? ''),
        'pat_date_of_birth' => $_POST['dob'] ?? '',
        'pat_gender' => $_POST['gender'] ?? '',
        'pat_address' => trim($_POST['address'] ?? ''),
        'pat_emergency_contact' => trim($_POST['emergency_contact'] ?? ''),
        'pat_emergency_phone' => (formatPhoneNumber($rawEmergencyPhone) ?? ''),
        'pat_medical_history' => trim($_POST['medical_history'] ?? ''),
        'pat_allergies' => trim($_POST['allergies'] ?? ''),
        'pat_insurance_provider' => trim($_POST['insurance_provider'] ?? ''),
        'pat_insurance_number' => trim($_POST['insurance_number'] ?? '')
    ];

    $password = $_POST['password'] ?? '';

    // Step 2: Create patient record
    $result = $patient->create($data);

    if ($result['success']) {
        $pat_id = $result['id'];

        try {
            // Step 3: Create linked user account
            $db = Database::getInstance();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (user_email, user_password, pat_id, user_is_superadmin)
                    VALUES (:email, :password, :pat_id, FALSE)";

            $db->execute($sql, [
                'email' => $data['pat_email'],
                'password' => $hashedPassword,
                'pat_id' => $pat_id
            ]);

            $success = 'Registration successful! You can now log in.';
        } catch (Exception $e) {
            $error = 'Patient created but failed to create user login: ' . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $result['errors']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - <?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-2xl">
        <div class="text-center mb-8">
            <i class="fas fa-user-plus text-6xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Patient Registration</h1>
            <p class="text-gray-600 mt-2">Fill out the form to create your account</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline"><?php echo $success; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Personal Info -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">First Name</label>
                <input type="text" name="first_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Last Name</label>
                <input type="text" name="last_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Phone</label>
                <input type="text" name="phone"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Date of Birth</label>
                <input type="date" name="dob"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Gender</label>
                <select name="gender"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Address</label>
                <input type="text" name="address"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Emergency Info -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Emergency Contact</label>
                <input type="text" name="emergency_contact"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Emergency Phone</label>
                <input type="text" name="emergency_phone"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Medical Info -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1">Medical History</label>
                <textarea name="medical_history" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1">Allergies</label>
                <textarea name="allergies" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Insurance -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Insurance Provider</label>
                <input type="text" name="insurance_provider"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Insurance Number</label>
                <input type="text" name="insurance_number"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2 mt-4">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>Register
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                Already have an account? 
                <a href="../../../login.php" class="text-blue-600 hover:text-blue-800 font-semibold">Sign In</a>
            </p>
        </div>
    </div>
</body>
</html>
