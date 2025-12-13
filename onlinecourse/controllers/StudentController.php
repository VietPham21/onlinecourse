<?php
require_once 'config/Database.php';
require_once 'models/Enrollment.php';
require_once 'models/Lesson.php';
require_once 'models/Material.php';

class StudentController {
    private $db;
    private $enrollmentModel;
    private $lessonModel;
    private $materialModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->enrollmentModel = new Enrollment($this->db);
        $this->lessonModel = new Lesson($this->db);
        $this->materialModel = new Material($this->db);
    }

    // Trang chính của học viên
    public function dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $student_id = $_SESSION['user_id'] ?? null;

        if (!$student_id) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Lấy danh sách khóa học đã đăng ký
        $myCourses = $this->enrollmentModel->getMyCourses($student_id);

        include 'views/student/dashboard.php';
    }

    // Xem bài học & tài liệu của 1 khóa học
    public function viewLesson() {
        if (!isset($_GET['course_id']) || !isset($_GET['lesson_id'])) {
            die("Thiếu course_id hoặc lesson_id.");
        }

        if (session_status() == PHP_SESSION_NONE) session_start();
        $student_id = $_SESSION['user_id'] ?? null;
        if (!$student_id) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $course_id = intval($_GET['course_id']);
        $lesson_id = intval($_GET['lesson_id']);

        // Kiểm tra đã đăng ký khóa học chưa
        if (!$this->enrollmentModel->isEnrolled($student_id, $course_id)) {
            die("Bạn chưa đăng ký khóa học này.");
        }

        $lessons = $this->lessonModel->getLessonsByCourse($course_id);
        $materials = $this->materialModel->getMaterialsByLesson($lesson_id);

        // Lấy tiến độ
        $progress = $this->enrollmentModel->getProgress($student_id, $course_id);

        include 'views/lessons/view.php';
    }
}
?>
