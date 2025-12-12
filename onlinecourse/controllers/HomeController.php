<?php
// controllers/HomeController.php
require_once 'models/Course.php'; // Gọi Model Course

class HomeController {
    public function index() {
        // 1. Kết nối Database
        $database = new Database();
        $db = $database->connect();
        
        // 2. Gọi Model để lấy danh sách khóa học đã duyệt
        $courseModel = new Course($db);
        $courses = $courseModel->getApprovedCourses();

        // 3. Gửi dữ liệu $courses sang View
        include 'views/home/index.php';
    }
}
?>