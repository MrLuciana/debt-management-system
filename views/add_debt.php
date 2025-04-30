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
    <title>เพิ่มหนี้ใหม่ - ระบบจัดการหนี้</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">เพิ่มหนี้ใหม่</h1>
            <div id="add-debt" class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
                <form id="addDebtForm" class="space-y-4">
                    <div>
                        <label for="debtor_name" class="block text-sm font-medium text-gray-600">ชื่อลูกหนี้</label>
                        <input type="text" id="debtor_name" name="debtor_name" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="total_amount" class="block text-sm font-medium text-gray-600">ยอดหนี้ทั้งหมด (บาท)</label>
                        <input type="number" id="total_amount" name="total_amount" step="0.01" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-600">อัตราดอกเบี้ย (%)</label>
                        <input type="number" id="interest_rate" name="interest_rate" step="0.01" value="0" class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-600">วันที่เริ่มต้น</label>
                        <input type="date" id="start_date" name="start_date" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors">บันทึกหนี้</button>
                </form>
                <div id="debtError" class="text-red-500 text-sm mt-2"></div>
            </div>
        </div>
    </div>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/public/scripts.js"></script>
</body>
</html>