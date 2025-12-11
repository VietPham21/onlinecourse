<?php
// controllers/HomeController.php
require_once 'models/Course.php';
require_once 'models/Category.php';

class HomeController {
    public function index() {
        // Sau này sẽ gọi CourseModel để lấy danh sách khóa học hiển thị ra
        // Hiện tại cứ hiển thị giao diện mẫu đã
        include 'views/home/index.php';
    }
}
?>