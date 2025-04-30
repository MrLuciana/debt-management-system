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
    <title>เพิ่มการชำระ - ระบบจัดการหนี้</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">เพิ่มการชำระ</h1>
            <div id="add-payment" class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
                <form id="addPaymentForm" class="space-y-4">
                    <div>
                        <label for="debt_id" class="block text-sm font-medium text-gray-600">เลือกหนี้</label>
                        <select id="debt_id" name="debt_id" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- เลือกหนี้ --</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-600">ประเภทการชำระ</label>
                        <select id="payment_type" name="payment_type" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="principal">เงินต้น</option>
                            <option value="interest">ดอกเบี้ย</option>
                            <option value="both">ทั้งเงินต้นและดอกเบี้ย</option>
                        </select>
                    </div>
                    <div id="principal_interest_fields" class="hidden space-y-4">
                        <div>
                            <label for="principal_amount" class="block text-sm font-medium text-gray-600">จำนวนเงินต้น (บาท)</label>
                            <input type="number" id="principal_amount" name="principal_amount" step="0.01" class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="interest_amount" class="block text-sm font-medium text-gray-600">จำนวนดอกเบี้ย (บาท)</label>
                            <input type="number" id="interest_amount" name="interest_amount" step="0.01" class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-gray-600">ยอดชำระ (บาท)</label>
                        <input type="number" id="payment_amount" name="payment_amount" step="0.01" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-600">วันที่ชำระ</label>
                        <input type="date" id="payment_date" name="payment_date" required class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors">บันทึกการชำระ</button>
                </form>
                <div id="paymentError" class="text-red-500 text-sm mt-2"></div>
            </div>
        </div>
    </div>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/public/scripts.js"></script>
</body>
</html>