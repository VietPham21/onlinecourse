<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- PHẦN 1: CHO AUTH (Đăng ký / Đăng nhập) ---

    // Đăng ký tài khoản mới
    public function register($username, $email, $password, $fullname) {
        // Mã hóa mật khẩu (Yêu cầu bắt buộc)
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Câu lệnh SQL insert
        $query = "INSERT INTO users (username, email, password, fullname, role, status) VALUES (:username, :email, :password, :fullname, 0, 1)";
        $stmt = $this->conn->prepare($query);
        
        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':fullname', $fullname);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            // Lỗi thường gặp là trùng email hoặc username
            return false;
        }
    }

    // Kiểm tra đăng nhập
    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        // Kiểm tra mật khẩu hash và trạng thái tài khoản (phải active = 1 mới cho vào)
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] == 0) {
                return "banned"; // Tài khoản bị khóa
            }
            return $user; // Đăng nhập thành công
        }
        return false; // Sai email hoặc pass
    }

    // --- PHẦN 2: CHO ADMIN (Quản lý) ---

    // Lấy danh sách user
    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Đếm tổng user
    public function countUsers() {
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    // Cập nhật trạng thái (Khóa/Mở khóa)
    public function updateStatus($id, $status) {
        $query = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>