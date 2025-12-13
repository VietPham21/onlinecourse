<?php
// controllers/HomeController.php
require_once 'config/Database.php'; // Include Database
require_once 'models/Course.php';   // Include Model Course

class HomeController {
    public function index() {
        // 1. Khởi tạo session nếu chưa
        if (session_status() == PHP_SESSION_NONE) session_start();

        // 2. Kiểm tra role
        $role = $_SESSION['role'] ?? null;

        if ($role === 'student') {
            // Nếu là học viên, chuyển về Student dashboard
            header('Location: index.php?controller=student&action=myCourses');
            exit;
        }

        // 3. Nếu không phải học viên, load trang home chung

        // Kết nối Database
        $database = new Database();
        $db = $database->connect();
        
        // Gọi Model Course
        $courseModel = new Course($db);

        // Lấy giá trị tìm kiếm & category_id từ GET
        $search = $_GET['search'] ?? '';
        $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

        // Lấy danh sách khóa học đã duyệt
        $courses = $courseModel->getApprovedCourses($search, $category_id);

        // Lấy danh mục để hiển thị dropdown lọc
        $categories = $courseModel->getCategories();

        // Gửi dữ liệu sang view
        include 'views/courses/index.php';
    }
}
?>
