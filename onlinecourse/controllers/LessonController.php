<?php
require_once 'config/Database.php';
require_once 'models/Lesson.php';
require_once 'models/Material.php';
require_once 'models/Enrollment.php';

class LessonController {
    private $db;
    private $lessonModel;
    private $materialModel;
    private $enrollmentModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->lessonModel = new Lesson($this->db);
        $this->materialModel = new Material($this->db);
        $this->enrollmentModel = new Enrollment($this->db);
    }

    // Xem bài học
    public function view() {
        if (!isset($_GET['lesson_id']) || !isset($_GET['course_id'])) {
            die("Thiếu lesson_id hoặc course_id.");
        }

        $lesson_id = intval($_GET['lesson_id']);
        $course_id = intval($_GET['course_id']);

        // Kiểm tra học viên đã đăng ký khóa học chưa
        if (session_status() == PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!$this->enrollmentModel->isEnrolled($user_id, $course_id)) {
            die("Bạn chưa đăng ký khóa học này.");
        }

        // Lấy danh sách bài học và tài liệu
        $lessons = $this->lessonModel->getLessonsByCourse($course_id);
        $materials = $this->materialModel->getMaterialsByLesson($lesson_id);

        include 'views/lessons/view.php';
    }
}
?>
