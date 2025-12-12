<?php
require_once 'config/Database.php';
require_once 'models/Enrollment.php';
require_once 'models/Course.php';

class StudentController {

    // Hiển thị Dashboard của học viên
    public function dashboard() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // Chưa đăng nhập
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $student_id = $_SESSION['user_id'];

        // Kết nối database
        $database = new Database();
        $db = $database->connect();

        // Lấy danh sách khóa học đã đăng ký
        $enrollmentModel = new Enrollment($db);
        $myCourses = $enrollmentModel->getMyCourses($student_id);

        // Thêm tiến độ cho từng khóa học
        foreach ($myCourses as $i => $course) {
            $myCourses[$i]['progress'] = $enrollmentModel->getProgress($student_id, $course['course_id']);
        }

        // Gửi dữ liệu sang view
        include 'views/student/dashboard.php';
    }

    // Xử lý đăng ký khóa học
    public function registerCourse() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Yêu cầu không hợp lệ.");
        }

        $student_id = $_SESSION['user_id'];
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;

        if ($course_id <= 0) {
            die("Thiếu course_id hoặc không hợp lệ.");
        }

        $database = new Database();
        $db = $database->connect();

        $enrollmentModel = new Enrollment($db);
        $courseModel = new Course($db);

        // Kiểm tra khóa học tồn tại
        $course = $courseModel->getCourseById($course_id);
        if (!$course) {
            die("Khóa học không tồn tại.");
        }

        // Kiểm tra học viên đã đăng ký chưa
        if ($enrollmentModel->isEnrolled($student_id, $course_id)) {
            header("Location: index.php?controller=student&action=dashboard");
            exit;
        }

        // Thực hiện đăng ký
        $ok = $enrollmentModel->enroll($student_id, $course_id);

        if ($ok) {
            header("Location: index.php?controller=student&action=dashboard");
            exit;
        } else {
            die("Đăng ký thất bại. Vui lòng thử lại.");
        }
    }
}
?>
