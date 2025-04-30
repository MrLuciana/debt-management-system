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
    <title>แดชบอร์ด - ระบบจัดการหนี้</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">แดชบอร์ด</h1>
            <div id="dashboard" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold text-gray-700">ยอดหนี้ทั้งหมด</h2>
                    <p id="total-debt" class="text-2xl font-bold text-blue-600">0 บาท</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold text-gray-700">จำนวนลูกหนี้</h2>
                    <p id="debt-count" class="text-2xl font-bold text-blue-600">0 รายการ</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold text-gray-700">ลูกหนี้ที่ต้องชำระ</h2>
                    <p id="paid-off-count" class="text-2xl font-bold text-blue-600">0 รายการ</p>
                </div>
            </div>
            <div class="mt-6 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">การชำระล่าสุด</h2>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3">วันที่ชำระ</th>
                            <th class="p-3">จำนวนเงิน</th>
                            <th class="p-3">ประเภท</th>
                            <th class="p-3">ชื่อลูกหนี้</th>
                        </tr>
                    </thead>
                    <tbody id="recent-payments"></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/public/scripts.js"></script>
</body>
</html>