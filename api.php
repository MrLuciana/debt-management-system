<?php
require_once __DIR__ . '/controllers/DebtController.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $action = htmlspecialchars(strip_tags(filter_input(INPUT_GET, 'action') ?? ''), ENT_QUOTES, 'UTF-8');
    if (empty($action)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ต้องระบุการดำเนินการ']);
        exit;
    }

    $controller = new DebtController();
    $controller->handleRequest($action);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'ข้อผิดพลาดของเซิร์ฟเวอร์: ' . $e->getMessage()]);
}
?>