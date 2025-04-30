<?php
require_once __DIR__ . '/../../config.php';
?>
<aside id="sidebar" class="bg-gray-800 text-white w-64 min-h-screen p-4 fixed md:static md:block hidden md:h-auto transition-transform duration-300">
    <h2 class="text-2xl font-bold mb-6">ระบบจัดการหนี้</h2>
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="<?php echo BASE_URL; ?>/views/dashboard.php" data-section="dashboard" class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    แดชบอร์ด
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/views/add_debt.php" data-section="add-debt" class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    เพิ่มหนี้ใหม่
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/views/add_payment.php" data-section="add-payment" class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    เพิ่มการชำระ
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/views/debt_history.php" data-section="debt-history" class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    ประวัติหนี้
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/views/payment_history.php" data-section="payment-history" class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ประวัติการชำระ
                </a>
            </li>
        </ul>
    </nav>
</aside>