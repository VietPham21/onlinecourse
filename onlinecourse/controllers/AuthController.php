<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->userModel = new User($db);
    }

    // Hiển thị form đăng nhập
    public function login() {
        include 'views/auth/login.php';
    }

    // Xử lý dữ liệu đăng nhập gửi lên
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Gọi hàm login từ Model (đã sửa ở bước trước)
            $result = $this->userModel->login($email, $password);

            if ($result === "banned") {
                $error = "Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ Admin.";
                include 'views/auth/login.php';
            } elseif ($result) {
                // Đăng nhập thành công -> Lưu session
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['role'] = $result['role']; 

                // Điều hướng dựa trên quyền [cite: 28]
                if ($result['role'] == 2) {
                    header("Location: index.php?controller=admin&action=dashboard");
                } elseif ($result['role'] == 1) {
                    header("Location: index.php?controller=instructor&action=dashboard");
                } else {
                    header("Location: index.php?controller=student&action=dashboard");
                }
                exit();
            } else {
                $error = "Email hoặc mật khẩu không chính xác!";
                include 'views/auth/login.php';
            }
        }
    }

    // Đăng xuất
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }

    // 1. Hiển thị form đăng ký
    public function register() {
        include 'views/auth/register.php';
    }

    // 2. Xử lý dữ liệu đăng ký
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $fullname = trim($_POST['fullname']);

            // Validate cơ bản
            if ($password !== $confirm_password) {
                $error = "Mật khẩu xác nhận không khớp!";
                include 'views/auth/register.php';
                return;
            }

            if (strlen($password) < 6) {
                $error = "Mật khẩu phải có ít nhất 6 ký tự!";
                include 'views/auth/register.php';
                return;
            }

            // Gọi Model để tạo tài khoản
            // Hàm register() trong Model User.php bạn đã có ở bước trước rồi
            if ($this->userModel->register($username, $email, $password, $fullname)) {
                // Đăng ký thành công -> Chuyển hướng về trang đăng nhập
                header("Location: index.php?controller=auth&action=login&message=registered");
            } else {
                $error = "Đăng ký thất bại! Username hoặc Email đã tồn tại.";
                include 'views/auth/register.php';
            }
        }
    }
}
?>