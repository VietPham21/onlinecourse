<?php
require_once 'models/User.php';

class AdminController {
    private $userModel;

    public function __construct() {
        // 1. Kiểm tra đăng nhập và quyền Admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        // 2. Kết nối CSDL
        $database = new Database();
        $db = $database->connect();
        $this->userModel = new User($db);
    }

    // Action: Trang Dashboard thống kê
    public function dashboard() {
        $totalUsers = $this->userModel->countUsers();
        // Sau này khi có Model Course thì gọi thêm $totalCourses ở đây
        
        include 'views/admin/dashboard.php';
    }

    // Action: Xem danh sách User [cite: 88]
    public function users() {
        $users = $this->userModel->getAllUsers();
        include 'views/admin/users/manage.php';
    }

    // Action: Khóa/Mở khóa User [cite: 88]
    public function toggleUserStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['user_id'];
            $currentStatus = $_POST['current_status'];
            
            // Nếu đang là 1 (active) thì chuyển thành 0 (banned) và ngược lại
            $newStatus = ($currentStatus == 1) ? 0 : 1;
            
            $this->userModel->updateStatus($id, $newStatus);
            
            header("Location: index.php?controller=admin&action=users");
            exit();
        }
    }
}
?>