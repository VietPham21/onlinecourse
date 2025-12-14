<?php
require_once 'models/User.php';
require_once 'models/Course.php';
require_once 'models/Category.php';

class AdminController {
    private $userModel;
    private $courseModel;
    private $categoryModel;

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
        $this->courseModel = new Course($db);
        $this->categoryModel = new Category($db);
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

    public function categories() {
        $categories = $this->categoryModel->getAll();
        include 'views/admin/categories/list.php'; // Đề bài đặt tên là list.php 
    }

    // 2. Hiển thị form thêm mới
    public function createCategory() {
        include 'views/admin/categories/create.php';
    }

    // 3. Xử lý lưu danh mục mới
    public function storeCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->create($name, $description);
            header("Location: index.php?controller=admin&action=categories");
        }
    }

    // 4. Hiển thị form sửa
    public function editCategory() {
        if (isset($_GET['id'])) {
            $category = $this->categoryModel->getById($_GET['id']);
            include 'views/admin/categories/edit.php';
        }
    }

    // 5. Xử lý cập nhật danh mục
    public function updateCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->update($id, $name, $description);
            header("Location: index.php?controller=admin&action=categories");
        }
    }

    // 6. Xử lý xóa danh mục
    public function deleteCategory() {
        if (isset($_GET['id'])) {
            $this->categoryModel->delete($_GET['id']);
            header("Location: index.php?controller=admin&action=categories");
        }
    }

    public function statistics() {
        // 1. Thống kê số lượng
        $countUsers = $this->userModel->countUsers();
        $countCourses = $this->courseModel->countTotalCourses();
        
        // 2. Thống kê danh sách khóa học theo danh mục (cần thêm hàm này ở Model Category nếu muốn xịn)
        // Hiện tại ta lấy số liệu cơ bản trước
        
        include 'views/admin/reports/statistics.php';
    }

    public function pendingCourses() {
        // Gọi hàm getPendingCourses từ CourseModel (Đã hướng dẫn tạo ở Model Course rồi)
        $courses = $this->courseModel->getPendingCourses();
        include 'views/admin/courses/pending.php';
    }

    // 2. Xử lý hành động Duyệt
    public function approveCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
            $course_id = $_POST['course_id'];
            
            // Gọi hàm approve từ CourseModel
            $this->courseModel->approve($course_id);
            
            // Quay lại trang danh sách với thông báo thành công
            header("Location: index.php?controller=admin&action=pendingCourses&msg=success");
        }
    }
    
    // 3. Xử lý hành động Xóa/Từ chối
    public function deleteCourse() {
        if (isset($_GET['id'])) {
            $this->courseModel->delete($_GET['id']);
            header("Location: index.php?controller=admin&action=pendingCourses&msg=deleted");
        }
    }

    // Chuyển đổi vai trò: Học viên <-> Giảng viên
    public function setRole() {
        // Kiểm tra quyền Admin trước cho chắc
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
             header("Location: index.php");
             exit();
        }

        if (isset($_GET['id']) && isset($_GET['new_role'])) {
            $id = $_GET['id'];
            $new_role = $_GET['new_role'];
            
            // Chỉ cho phép set thành 0 (Học viên) hoặc 1 (Giảng viên)
            if ($new_role == 0 || $new_role == 1) {
                $this->userModel->updateRole($id, $new_role);
            }
            
            header("Location: index.php?controller=admin&action=users");
        }
    }
}
?>