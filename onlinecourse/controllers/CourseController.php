<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Enrollment.php';

class CourseController {

    private $db;
    private $courseModel;
    private $enrollmentModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->courseModel = new Course($this->db);
        $this->enrollmentModel = new Enrollment($this->db);
    }

    /**
     * Hiển thị danh sách khóa học (có tìm kiếm & lọc theo danh mục)
     */
    public function index() {
        $search = $_GET['search'] ?? '';
        $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

        // Lấy danh sách khóa học đã duyệt, kèm tìm kiếm + lọc
        $courses = $this->courseModel->getApprovedCourses($search, $category_id);
        $categories = $this->courseModel->getCategories();

        include 'views/courses/index.php';
    }

    /**
     * Hiển thị chi tiết khóa học
     */
    public function detail() {
        if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
            die("Thiếu course ID hoặc không hợp lệ.");
        }

        $course_id = intval($_GET['id']);
        $course = $this->courseModel->getCourseById($course_id);
        if (!$course) die("Khóa học không tồn tại.");

        // Khởi tạo session nếu chưa
        if (session_status() == PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION['user_id'] ?? null;

        $isEnrolled = false;
        $progress = 0;
        if ($user_id) {
            $isEnrolled = $this->enrollmentModel->isEnrolled($user_id, $course_id);
            $progress = $this->enrollmentModel->getProgress($user_id, $course_id);
        }

        include 'views/courses/detail.php';
    }

    /**
     * Xử lý đăng ký khóa học (POST)
     */
    public function enroll() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        if ($course_id <= 0) die("Thiếu course_id hoặc không hợp lệ.");

        $student_id = $_SESSION['user_id'];

        $course = $this->courseModel->getCourseById($course_id);
        if (!$course) die("Khóa học không tồn tại.");

        // tránh đăng ký trùng
        if ($this->enrollmentModel->isEnrolled($student_id, $course_id)) {
            header("Location: index.php?controller=course&action=detail&id=$course_id");
            exit;
        }

        $ok = $this->enrollmentModel->enroll($student_id, $course_id);

        if ($ok) {
            header("Location: index.php?controller=course&action=detail&id=$course_id");
            exit;
        } else {
            die("Đăng ký thất bại.");
        }
    }
}
?>
