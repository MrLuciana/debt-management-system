<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controllers/DebtController.php';

$controller = new DebtController();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหนี้</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/styles.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <?php include __DIR__ . '/layouts/sidebar.php'; ?>
        <div class="flex-1 p-6">
            <button id="toggle-sidebar" class="md:hidden p-2 bg-gray-800 text-white rounded mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-3xl font-bold text-gray-800 mb-6">ยินดีต้อนรับสู่ระบบจัดการหนี้</h1>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-gray-600">เลือกเมนูด้านซ้ายเพื่อจัดการหนี้หรือดูรายงาน</p>
            </div>
        </div>
    </div>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/public/scripts.js"></script>
</body>
</html>