<?php
require_once __DIR__ . '/../config.php';

class DebtModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            throw new Exception('การเชื่อมต่อฐานข้อมูลล้มเหลว: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf8mb4');
    }

    public function addDebt($debtor_name, $total_amount, $interest_rate, $start_date) {
        $stmt = $this->conn->prepare("INSERT INTO debts (debtor_name, total_amount, interest_rate, start_date) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("sdds", $debtor_name, $total_amount, $interest_rate, $start_date);
        $result = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        if (!$result) {
            throw new Exception('Execute failed: ' . $error);
        }
        return $result;
    }

    public function recordPayment($debt_id, $payment_amount, $payment_date, $payment_type, $principal_amount = null, $interest_amount = null) {
        $stmt = $this->conn->prepare("SELECT debt_id FROM debts WHERE debt_id = ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("i", $debt_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows === 0) {
            throw new Exception('ไม่พบหนี้ที่มี ID: ' . $debt_id);
        }

        $stmt = $this->conn->prepare("INSERT INTO payments (debt_id, payment_amount, principal_amount, interest_amount, payment_date, payment_type) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("idddss", $debt_id, $payment_amount, $principal_amount, $interest_amount, $payment_date, $payment_type);
        $result = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        if (!$result) {
            throw new Exception('Execute failed: ' . $error);
        }
        return $result;
    }

    public function getAllDebts() {
        $stmt = $this->conn->prepare("SELECT debt_id, debtor_name, total_amount, interest_rate, start_date FROM debts ORDER BY created_at DESC");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $debts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $debts;
    }

    public function getPaymentHistory($debt_id = 0) {
        if ($debt_id) {
            $stmt = $this->conn->prepare("SELECT p.*, d.debtor_name FROM payments p JOIN debts d ON p.debt_id = d.debt_id WHERE p.debt_id = ? ORDER BY p.payment_date DESC");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
            $stmt->bind_param("i", $debt_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $stmt = $this->conn->prepare("SELECT p.*, d.debtor_name FROM payments p JOIN debts d ON p.debt_id = d.debt_id ORDER BY p.payment_date DESC");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
        }
        $payments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $payments;
    }

    public function calculateRemainingDebt($debt_id) {
        $stmt = $this->conn->prepare("SELECT total_amount FROM debts WHERE debt_id = ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("i", $debt_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $debt = $result->fetch_assoc();
        $stmt->close();
        if (!$debt) {
            throw new Exception('ไม่พบหนี้ที่มี ID: ' . $debt_id);
        }
        $total_debt = $debt['total_amount'];

        $stmt = $this->conn->prepare("SELECT SUM(principal_amount) as total_principal_paid FROM payments WHERE debt_id = ? AND (payment_type = 'principal' OR payment_type = 'both')");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("i", $debt_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $paid = $result->fetch_assoc();
        $stmt->close();
        $total_paid = $paid['total_principal_paid'] ?? 0;

        return max(0, $total_debt - $total_paid);
    }

    public function getDashboardSummary() {
        $summary = [];

        $stmt = $this->conn->prepare("SELECT SUM(total_amount) as total_debt FROM debts");
        $stmt->execute();
        $summary['total_debt'] = $stmt->get_result()->fetch_assoc()['total_debt'] ?? 0;
        $stmt->close();

        $stmt = $this->conn->prepare("SELECT COUNT(*) as debt_count FROM debts");
        $stmt->execute();
        $summary['debt_count'] = $stmt->get_result()->fetch_assoc()['debt_count'] ?? 0;
        $stmt->close();

        $stmt = $this->conn->prepare("SELECT p.*, d.debtor_name FROM payments p JOIN debts d ON p.debt_id = d.debt_id ORDER BY p.payment_date DESC LIMIT 5");
        $stmt->execute();
        $summary['recent_payments'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmt = $this->conn->prepare("SELECT COUNT(*) as paid_off_count FROM debts d LEFT JOIN (SELECT debt_id, SUM(principal_amount) as total_paid FROM payments WHERE payment_type IN ('principal', 'both') GROUP BY debt_id) p ON d.debt_id = p.debt_id WHERE p.total_paid >= d.total_amount OR p.total_paid IS NULL");
        $stmt->execute();
        $summary['paid_off_count'] = $stmt->get_result()->fetch_assoc()['paid_off_count'] ?? 0;
        $stmt->close();

        return $summary;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>