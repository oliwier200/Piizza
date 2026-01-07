<?php
class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        $query = "SELECT * FROM admins WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Proste sprawdzanie hasła (w produkcji warto użyć password_verify)
        if ($admin && $admin['password'] === $password) {
            $_SESSION['admin_logged_in'] = true;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Błędny login lub hasło']);
        }
    }

    public function getOrders() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['admin_logged_in'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Brak dostępu']);
            return;
        }

        $orderModel = new Order($this->db);
        $orders = $orderModel->getAll();
        echo json_encode($orders);
    }

    public function deleteOrder() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['admin_logged_in'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Brak dostępu']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        $orderModel = new Order($this->db);
        $orderModel->delete($id);
        echo json_encode(['success' => true]);
    }
}
?>