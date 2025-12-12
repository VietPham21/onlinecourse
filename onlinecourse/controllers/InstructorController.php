<?php
require_once 'models/Course.php';
require_once 'models/Category.php';
require_once 'models/Lesson.php';
require_once 'models/Material.php';
require_once 'models/Enrollment.php';

class InstructorController {
    private $courseModel;
    private $categoryModel;
    private $lessonModel;
    private $materialModel;
    private $enrollmentModel;

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
        $this->lessonModel = new Lesson($db);
        $this->materialModel = new Material($db);
        $this->enrollmentModel = new Enrollment($db);
    }

    // Dashboard của giảng viên
    public function dashboard() {
        $instructorId = $_SESSION['user_id'];
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);
        
        // Đếm số bài học và số học viên cho mỗi khóa học
        foreach($courses as &$course) {
            $course['lesson_count'] = $this->lessonModel->countByCourseId($course['id']);
            $course['student_count'] = $this->enrollmentModel->countByCourseId($course['id']);
        }
        unset($course);
        
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

    // Hiển thị form sửa khóa học
    public function editCourse() {
        $instructorId = $_SESSION['user_id'];
        $courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$courseId || !$this->courseModel->isOwner($courseId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        $course = $this->courseModel->getById($courseId);
        if (!$course) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $categories = $this->categoryModel->getAll();
        include 'views/instructor/course/edit.php';
    }

    // Xử lý cập nhật khóa học
    public function updateCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructorId = $_SESSION['user_id'];
            $courseId = intval($_POST['id']);
            
            // Kiểm tra quyền sở hữu
            if (!$this->courseModel->isOwner($courseId, $instructorId)) {
                header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
                exit();
            }
            
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $categoryId = isset($_POST['category_id']) && $_POST['category_id'] != '' ? $_POST['category_id'] : null;
            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
            $durationWeeks = isset($_POST['duration_weeks']) && $_POST['duration_weeks'] != '' ? intval($_POST['duration_weeks']) : null;
            $level = isset($_POST['level']) && $_POST['level'] != '' ? $_POST['level'] : null;
            
            // Lấy thông tin khóa học hiện tại
            $currentCourse = $this->courseModel->getById($courseId);
            $image = $currentCourse['image']; // Giữ nguyên ảnh cũ nếu không upload mới
            
            // Xử lý upload ảnh mới (nếu có)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = __DIR__ . '/../uploads/courses/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    // Xóa ảnh cũ nếu có
                    if ($currentCourse['image'] && file_exists(__DIR__ . '/../' . $currentCourse['image'])) {
                        unlink(__DIR__ . '/../' . $currentCourse['image']);
                    }
                    
                    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        $image = 'uploads/courses/' . $fileName;
                    }
                }
            }
            
            // Validate
            if (empty($title)) {
                $error = "Vui lòng nhập tên khóa học!";
                $course = $currentCourse;
                $categories = $this->categoryModel->getAll();
                include 'views/instructor/course/edit.php';
                return;
            }
            
            // Cập nhật khóa học
            $result = $this->courseModel->update(
                $courseId,
                $title,
                $description,
                $categoryId,
                $price,
                $durationWeeks,
                $level,
                $image
            );
            
            if ($result) {
                header("Location: index.php?controller=instructor&action=dashboard&msg=updated");
            } else {
                $error = "Có lỗi xảy ra khi cập nhật khóa học!";
                $course = $currentCourse;
                $categories = $this->categoryModel->getAll();
                include 'views/instructor/course/edit.php';
            }
        } else {
            header("Location: index.php?controller=instructor&action=dashboard");
        }
    }

    // Xóa khóa học
    public function deleteCourse() {
        $instructorId = $_SESSION['user_id'];
        $courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$courseId) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=error");
            exit();
        }
        
        // Kiểm tra quyền sở hữu
        if (!$this->courseModel->isOwner($courseId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        // Lấy thông tin khóa học để xóa ảnh
        $course = $this->courseModel->getById($courseId);
        if ($course && $course['image'] && file_exists(__DIR__ . '/../' . $course['image'])) {
            unlink(__DIR__ . '/../' . $course['image']);
        }
        
        // Xóa khóa học
        $result = $this->courseModel->deleteByInstructor($courseId, $instructorId);
        
        if ($result) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=deleted");
        } else {
            header("Location: index.php?controller=instructor&action=dashboard&msg=error");
        }
        exit();
    }

    // ========== QUẢN LÝ BÀI HỌC (LESSONS) ==========

    // Hiển thị danh sách bài học của một khóa học
    public function manageLessons() {
        $instructorId = $_SESSION['user_id'];
        $courseId = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
        
        if (!$courseId || !$this->courseModel->isOwner($courseId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        $course = $this->courseModel->getById($courseId);
        if (!$course) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $lessons = $this->lessonModel->getByCourseId($courseId);
        
        // Đếm số tài liệu cho mỗi bài học
        foreach($lessons as &$lesson) {
            $lesson['material_count'] = $this->materialModel->countByLessonId($lesson['id']);
        }
        unset($lesson);
        
        include 'views/instructor/lessons/manage.php';
    }

    // Hiển thị form tạo bài học mới
    public function createLesson() {
        $instructorId = $_SESSION['user_id'];
        $courseId = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
        
        if (!$courseId || !$this->courseModel->isOwner($courseId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        $course = $this->courseModel->getById($courseId);
        if (!$course) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $nextOrder = $this->lessonModel->getNextOrder($courseId);
        include 'views/instructor/lessons/create.php';
    }

    // Xử lý lưu bài học mới
    public function storeLesson() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructorId = $_SESSION['user_id'];
            $courseId = intval($_POST['course_id']);
            
            // Kiểm tra quyền sở hữu
            if (!$this->courseModel->isOwner($courseId, $instructorId)) {
                header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
                exit();
            }
            
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $videoUrl = trim($_POST['video_url']);
            $order = isset($_POST['order']) ? intval($_POST['order']) : $this->lessonModel->getNextOrder($courseId);
            
            // Validate
            if (empty($title)) {
                $error = "Vui lòng nhập tên bài học!";
                $course = $this->courseModel->getById($courseId);
                $nextOrder = $this->lessonModel->getNextOrder($courseId);
                include 'views/instructor/lessons/create.php';
                return;
            }
            
            // Lưu bài học
            $result = $this->lessonModel->create($courseId, $title, $content, $videoUrl, $order);
            
            if ($result) {
                header("Location: index.php?controller=instructor&action=manageLessons&course_id=" . $courseId . "&msg=success");
            } else {
                $error = "Có lỗi xảy ra khi tạo bài học!";
                $course = $this->courseModel->getById($courseId);
                $nextOrder = $this->lessonModel->getNextOrder($courseId);
                include 'views/instructor/lessons/create.php';
            }
        } else {
            header("Location: index.php?controller=instructor&action=dashboard");
        }
    }

    // Hiển thị form sửa bài học
    public function editLesson() {
        $instructorId = $_SESSION['user_id'];
        $lessonId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$lessonId || !$this->lessonModel->belongsToInstructor($lessonId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        $lesson = $this->lessonModel->getById($lessonId);
        if (!$lesson) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $course = $this->courseModel->getById($lesson['course_id']);
        include 'views/instructor/lessons/edit.php';
    }

    // Xử lý cập nhật bài học
    public function updateLesson() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructorId = $_SESSION['user_id'];
            $lessonId = intval($_POST['id']);
            
            // Kiểm tra quyền sở hữu
            if (!$this->lessonModel->belongsToInstructor($lessonId, $instructorId)) {
                header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
                exit();
            }
            
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $videoUrl = trim($_POST['video_url']);
            $order = intval($_POST['order']);
            
            // Validate
            if (empty($title)) {
                $error = "Vui lòng nhập tên bài học!";
                $lesson = $this->lessonModel->getById($lessonId);
                $course = $this->courseModel->getById($lesson['course_id']);
                include 'views/instructor/lessons/edit.php';
                return;
            }
            
            // Cập nhật bài học
            $result = $this->lessonModel->update($lessonId, $title, $content, $videoUrl, $order);
            
            if ($result) {
                $lesson = $this->lessonModel->getById($lessonId);
                header("Location: index.php?controller=instructor&action=manageLessons&course_id=" . $lesson['course_id'] . "&msg=updated");
            } else {
                $error = "Có lỗi xảy ra khi cập nhật bài học!";
                $lesson = $this->lessonModel->getById($lessonId);
                $course = $this->courseModel->getById($lesson['course_id']);
                include 'views/instructor/lessons/edit.php';
            }
        } else {
            header("Location: index.php?controller=instructor&action=dashboard");
        }
    }

    // Xóa bài học
    public function deleteLesson() {
        $instructorId = $_SESSION['user_id'];
        $lessonId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$lessonId) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=error");
            exit();
        }
        
        // Kiểm tra quyền sở hữu
        if (!$this->lessonModel->belongsToInstructor($lessonId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        // Lấy thông tin bài học để lấy course_id
        $lesson = $this->lessonModel->getById($lessonId);
        if (!$lesson) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $courseId = $lesson['course_id'];
        
        // Xóa bài học
        $result = $this->lessonModel->delete($lessonId);
        
        if ($result) {
            header("Location: index.php?controller=instructor&action=manageLessons&course_id=" . $courseId . "&msg=deleted");
        } else {
            header("Location: index.php?controller=instructor&action=manageLessons&course_id=" . $courseId . "&msg=error");
        }
        exit();
    }

    // ========== QUẢN LÝ TÀI LIỆU HỌC TẬP (MATERIALS) ==========

    // Hiển thị form upload tài liệu
    public function uploadMaterial() {
        $instructorId = $_SESSION['user_id'];
        $lessonId = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
        
        if (!$lessonId || !$this->lessonModel->belongsToInstructor($lessonId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        $lesson = $this->lessonModel->getById($lessonId);
        if (!$lesson) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $course = $this->courseModel->getById($lesson['course_id']);
        $materials = $this->materialModel->getByLessonId($lessonId);
        
        include 'views/instructor/materials/upload.php';
    }

    // Xử lý upload tài liệu
    public function storeMaterial() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructorId = $_SESSION['user_id'];
            $lessonId = intval($_POST['lesson_id']);
            
            // Kiểm tra quyền sở hữu
            if (!$this->lessonModel->belongsToInstructor($lessonId, $instructorId)) {
                header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
                exit();
            }
            
            // Kiểm tra có file được upload không
            if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
                $error = "Vui lòng chọn file để upload!";
                $lesson = $this->lessonModel->getById($lessonId);
                $course = $this->courseModel->getById($lesson['course_id']);
                $materials = $this->materialModel->getByLessonId($lessonId);
                include 'views/instructor/materials/upload.php';
                return;
            }
            
            $file = $_FILES['file'];
            $allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain',
                'application/zip',
                'application/x-rar-compressed',
                'image/jpeg',
                'image/png',
                'image/gif'
            ];
            
            // Kiểm tra loại file
            if (!in_array($file['type'], $allowedTypes)) {
                $error = "Loại file không được hỗ trợ! Chỉ chấp nhận: PDF, Word, Excel, PowerPoint, Text, ZIP, RAR, Images";
                $lesson = $this->lessonModel->getById($lessonId);
                $course = $this->courseModel->getById($lesson['course_id']);
                $materials = $this->materialModel->getByLessonId($lessonId);
                include 'views/instructor/materials/upload.php';
                return;
            }
            
            // Kiểm tra kích thước file (tối đa 50MB)
            $maxSize = 50 * 1024 * 1024; // 50MB
            if ($file['size'] > $maxSize) {
                $error = "File quá lớn! Kích thước tối đa là 50MB";
                $lesson = $this->lessonModel->getById($lessonId);
                $course = $this->courseModel->getById($lesson['course_id']);
                $materials = $this->materialModel->getByLessonId($lessonId);
                include 'views/instructor/materials/upload.php';
                return;
            }
            
            // Tạo thư mục lưu trữ nếu chưa có
            $uploadDir = __DIR__ . '/../uploads/materials/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Tạo tên file duy nhất
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            // Upload file
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Lưu đường dẫn tương đối
                $relativePath = 'uploads/materials/' . $fileName;
                
                // Lưu vào database
                $result = $this->materialModel->create(
                    $lessonId,
                    $file['name'],
                    $relativePath,
                    $file['type']
                );
                
                if ($result) {
                    header("Location: index.php?controller=instructor&action=uploadMaterial&lesson_id=" . $lessonId . "&msg=success");
                } else {
                    // Xóa file nếu lưu database thất bại
                    unlink($filePath);
                    $error = "Có lỗi xảy ra khi lưu thông tin file!";
                    $lesson = $this->lessonModel->getById($lessonId);
                    $course = $this->courseModel->getById($lesson['course_id']);
                    $materials = $this->materialModel->getByLessonId($lessonId);
                    include 'views/instructor/materials/upload.php';
                }
            } else {
                $error = "Có lỗi xảy ra khi upload file!";
                $lesson = $this->lessonModel->getById($lessonId);
                $course = $this->courseModel->getById($lesson['course_id']);
                $materials = $this->materialModel->getByLessonId($lessonId);
                include 'views/instructor/materials/upload.php';
            }
        } else {
            header("Location: index.php?controller=instructor&action=dashboard");
        }
    }

    // Xóa tài liệu
    public function deleteMaterial() {
        $instructorId = $_SESSION['user_id'];
        $materialId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$materialId) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=error");
            exit();
        }
        
        // Kiểm tra quyền sở hữu
        if (!$this->materialModel->belongsToInstructor($materialId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        // Lấy thông tin tài liệu
        $material = $this->materialModel->getById($materialId);
        if (!$material) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $lessonId = $material['lesson_id'];
        
        // Xóa file vật lý
        $filePath = __DIR__ . '/../' . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Xóa trong database
        $result = $this->materialModel->delete($materialId);
        
        if ($result) {
            header("Location: index.php?controller=instructor&action=uploadMaterial&lesson_id=" . $lessonId . "&msg=deleted");
        } else {
            header("Location: index.php?controller=instructor&action=uploadMaterial&lesson_id=" . $lessonId . "&msg=error");
        }
        exit();
    }

    // ========== QUẢN LÝ HỌC VIÊN ĐÃ ĐĂNG KÝ ==========

    // Xem danh sách học viên đã đăng ký khóa học
    public function viewStudents() {
        $instructorId = $_SESSION['user_id'];
        $courseId = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
        
        if (!$courseId || !$this->courseModel->isOwner($courseId, $instructorId)) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=unauthorized");
            exit();
        }
        
        $course = $this->courseModel->getById($courseId);
        if (!$course) {
            header("Location: index.php?controller=instructor&action=dashboard&msg=notfound");
            exit();
        }
        
        $students = $this->enrollmentModel->getStudentsByCourseId($courseId);
        $statistics = $this->enrollmentModel->getStatisticsByCourseId($courseId);
        
        include 'views/instructor/students/list.php';
    }
}
?>

