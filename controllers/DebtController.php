<?php
require_once __DIR__ . '/../models/DebtModel.php';

class DebtController {
    private $model;

    public function __construct() {
        $this->model = new DebtModel();
    }

    public function handleRequest($action) {
        header('Content-Type: application/json; charset=utf-8');

        try {
            switch ($action) {
                case 'getDebts':
                    $debts = $this->model->getAllDebts();
                    echo json_encode(['success' => true, 'message' => 'ดึงข้อมูลหนี้สำเร็จ', 'data' => $debts]);
                    break;

                case 'getPayments':
                    $debt_id = filter_input(INPUT_GET, 'debt_id', FILTER_SANITIZE_NUMBER_INT);
                    $payments = $this->model->getPaymentHistory($debt_id ? (int)$debt_id : 0);
                    echo json_encode(['success' => true, 'message' => 'ดึงประวัติการชำระสำเร็จ', 'data' => $payments]);
                    break;

                case 'addDebt':
                    $data = [
                        'debtor_name'   => strip_tags(trim(filter_input(INPUT_POST, 'debtor_name'))),
                        'total_amount'  => filter_input(INPUT_POST, 'total_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                        'interest_rate' => filter_input(INPUT_POST, 'interest_rate', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? 0,
                        'start_date'    => strip_tags(trim(filter_input(INPUT_POST, 'start_date')))
                    ];                    

                    if (empty($data['debtor_name']) || empty($data['total_amount']) || empty($data['start_date'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
                        return;
                    }

                    if (!is_numeric($data['total_amount']) || $data['total_amount'] <= 0) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ยอดหนี้ต้องมากกว่า 0']);
                        return;
                    }

                    if (!DateTime::createFromFormat('Y-m-d', $data['start_date'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'รูปแบบวันที่ไม่ถูกต้อง']);
                        return;
                    }

                    $result = $this->model->addDebt(
                        $data['debtor_name'],
                        $data['total_amount'],
                        $data['interest_rate'],
                        $data['start_date']
                    );
                    echo json_encode(['success' => $result, 'message' => $result ? 'บันทึกหนี้สำเร็จ' : 'เกิดข้อผิดพลาดในการบันทึกหนี้']);
                    break;

                case 'addPayment':
                    $data = [
                        'debt_id'          => filter_input(INPUT_POST, 'debt_id', FILTER_SANITIZE_NUMBER_INT),
                        'payment_amount'   => filter_input(INPUT_POST, 'payment_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                        'payment_date'     => strip_tags(trim(filter_input(INPUT_POST, 'payment_date'))),
                        'payment_type'     => strip_tags(trim(filter_input(INPUT_POST, 'payment_type'))),
                        'principal_amount' => filter_input(INPUT_POST, 'principal_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                        'interest_amount'  => filter_input(INPUT_POST, 'interest_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
                    ];                    

                    if (empty($data['debt_id']) || empty($data['payment_amount']) || empty($data['payment_date']) || empty($data['payment_type'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
                        return;
                    }

                    if (!is_numeric($data['payment_amount']) || $data['payment_amount'] <= 0) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ยอดชำระต้องมากกว่า 0']);
                        return;
                    }

                    if (!in_array($data['payment_type'], ['principal', 'interest', 'both'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ประเภทการชำระไม่ถูกต้อง']);
                        return;
                    }

                    if ($data['payment_type'] === 'both' && (empty($data['principal_amount']) || empty($data['interest_amount']) || $data['principal_amount'] <= 0 || $data['interest_amount'] <= 0)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'กรุณากรอกเงินต้นและดอกเบี้ยให้ครบถ้วนและมากกว่า 0']);
                        return;
                    }

                    if (!DateTime::createFromFormat('Y-m-d', $data['payment_date'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'รูปแบบวันที่ไม่ถูกต้อง']);
                        return;
                    }

                    $result = $this->model->recordPayment(
                        $data['debt_id'],
                        $data['payment_amount'],
                        $data['payment_date'],
                        $data['payment_type'],
                        $data['payment_type'] === 'both' ? $data['principal_amount'] : null,
                        $data['payment_type'] === 'both' ? $data['interest_amount'] : null
                    );
                    echo json_encode(['success' => $result, 'message' => $result ? 'บันทึกการชำระสำเร็จ' : 'เกิดข้อผิดพลาดในการบันทึกการชำระ']);
                    break;

                case 'remainingDebt':
                    $debt_id = filter_input(INPUT_GET, 'debt_id', FILTER_SANITIZE_NUMBER_INT);
                    if (empty($debt_id) || !is_numeric($debt_id)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ต้องระบุ ID หนี้ที่ถูกต้อง']);
                        return;
                    }
                    $remaining = $this->model->calculateRemainingDebt($debt_id);
                    echo json_encode(['success' => true, 'message' => 'คำนวณยอดหนี้คงเหลือสำเร็จ', 'data' => ['remaining' => $remaining]]);
                    break;

                case 'getDashboard':
                    $summary = $this->model->getDashboardSummary();
                    echo json_encode(['success' => true, 'message' => 'ดึงข้อมูลแดชบอร์ดสำเร็จ', 'data' => $summary]);
                    break;

                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'การดำเนินการไม่ถูกต้อง']);
                    break;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'ข้อผิดพลาดของเซิร์ฟเวอร์: ' . $e->getMessage()]);
        }
    }

    public function renderMainPage() {
        require_once __DIR__ . '/../views/index.php';
    }

    public function renderDashboardPage() {
        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function renderAddDebtPage() {
        require_once __DIR__ . '/../views/add_debt.php';
    }

    public function renderAddPaymentPage() {
        require_once __DIR__ . '/../views/add_payment.php';
    }

    public function renderDebtHistoryPage() {
        require_once __DIR__ . '/../views/debt_history.php';
    }

    public function renderPaymentHistoryPage() {
        require_once __DIR__ . '/../views/payment_history.php';
    }
}
?>