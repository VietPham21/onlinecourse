<?php
require_once 'models/Course.php';
require_once 'models/Category.php';

class InstructorController {
    private $courseModel;
    private $categoryModel;

    public function __construct() {
        // Kiểm tra đăng nhập và quyền Giảng viên
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        // Kết nối CSDL
        $database = new Database();
        $db = $database->connect();
        $this->courseModel = new Course($db);
        $this->categoryModel = new Category($db);
    }

    // Dashboard của giảng viên
    public function dashboard() {
        $instructorId = $_SESSION['user_id'];
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);
        include 'views/instructor/dashboard.php';
    }

    // Hiển thị form tạo khóa học
    public function createCourse() {
        $categories = $this->categoryModel->getAll();
        include 'views/instructor/course/create.php';
    }

    // Xử lý lưu khóa học mới
    public function storeCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructorId = $_SESSION['user_id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $categoryId = isset($_POST['category_id']) ? $_POST['category_id'] : null;
            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
            $durationWeeks = isset($_POST['duration_weeks']) ? intval($_POST['duration_weeks']) : null;
            $level = isset($_POST['level']) ? $_POST['level'] : null;
            $image = null;

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = __DIR__ . '/../uploads/courses/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        // Lưu đường dẫn tương đối để hiển thị
                        $image = 'uploads/courses/' . $fileName;
                    }
                }
            }

            // Validate dữ liệu
            if (empty($title)) {
                $error = "Vui lòng nhập tên khóa học!";
                $categories = $this->categoryModel->getAll();
                include 'views/instructor/course/create.php';
                return;
            }

            // Lưu khóa học
            $result = $this->courseModel->create(
                $title,
                $description,
                $instructorId,
                $categoryId,
                $price,
                $durationWeeks,
                $level,
                $image
            );

            if ($result) {
                header("Location: index.php?controller=instructor&action=dashboard&msg=success");
            } else {
                $error = "Có lỗi xảy ra khi tạo khóa học!";
                $categories = $this->categoryModel->getAll();
                include 'views/instructor/course/create.php';
            }
        } else {
            header("Location: index.php?controller=instructor&action=createCourse");
        }
    }

    // Xem danh sách khóa học của giảng viên
    public function myCourses() {
        $instructorId = $_SESSION['user_id'];
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);
        include 'views/instructor/my_courses.php';
    }
}
?>

