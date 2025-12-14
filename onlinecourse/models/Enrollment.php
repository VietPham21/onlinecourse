<?php
class Enrollment {
    private $conn;
    private $table = "enrollments";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra sinh viên đã đăng ký khóa học chưa
    public function isEnrolled($student_id, $course_id) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE student_id = :student_id 
                  AND course_id = :course_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Đăng ký khóa học
    public function enroll($student_id, $course_id) {
        $query = "INSERT INTO " . $this->table . " 
                    (student_id, course_id, progress, status, enrolled_date)
                  VALUES 
                    (:student_id, :course_id, 0, 'active', NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':course_id', $course_id);

        return $stmt->execute();
    }

    // Lấy danh sách khóa học đã đăng ký (kèm tên giảng viên)
    public function getMyCourses($student_id) {
        $query = "SELECT e.*, 
                         c.title, 
                         c.description,
                         c.image,
                         c.id AS course_id,
                         u.fullname AS instructor_name
                  FROM enrollments e
                  JOIN courses c ON e.course_id = c.id
                  LEFT JOIN users u ON c.instructor_id = u.id
                  WHERE e.student_id = :student_id
                  ORDER BY e.enrolled_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();

        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Chuẩn hóa ảnh và giảng viên
        foreach ($courses as $i => $course) {
            if (empty($course['image'])) {
                $courses[$i]['image'] = 'assets/uploads/courses/default.jpg';
            } elseif (strpos($course['image'], 'http') === false && strpos($course['image'], 'assets/') !== 0) {
                $courses[$i]['image'] = 'assets/uploads/courses/' . $course['image'];
            }

            if (empty($course['instructor_name'])) {
                $courses[$i]['instructor_name'] = 'Chưa xác định';
            }
        }

        return $courses;
    }

    // Lấy tiến độ khóa học
    public function getProgress($student_id, $course_id) {
        $query = "SELECT progress FROM " . $this->table . " 
                  WHERE student_id = :student_id 
                  AND course_id = :course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['progress'] ?? 0;
    }

    // Cập nhật tiến độ
    public function updateProgress($student_id, $course_id, $progress) {
        $query = "UPDATE " . $this->table . "
                  SET progress = :progress
                  WHERE student_id = :student_id 
                  AND course_id = :course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':progress', $progress);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':course_id', $course_id);

        return $stmt->execute();
    }
    
    // Lấy danh sách học viên đã đăng ký một khóa học
    public function getStudentsByCourseId($courseId) {
        $query = "SELECT e.*, u.id as student_id, u.username, u.email, u.fullname, u.created_at as student_created_at
                  FROM enrollments e
                  JOIN users u ON e.student_id = u.id
                  WHERE e.course_id = :course_id
                  ORDER BY e.enrolled_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Đếm số học viên đã đăng ký một khóa học
    public function countByCourseId($courseId) {
        $query = "SELECT COUNT(*) as total FROM enrollments WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Đếm số học viên theo trạng thái
    public function countByCourseIdAndStatus($courseId, $status) {
        $query = "SELECT COUNT(*) as total FROM enrollments WHERE course_id = :course_id AND status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Kiểm tra khóa học thuộc về giảng viên
    public function courseBelongsToInstructor($courseId, $instructorId) {
        $query = "SELECT COUNT(*) as count FROM courses WHERE id = :course_id AND instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    // Lấy thống kê đăng ký của một khóa học
    public function getStatisticsByCourseId($courseId) {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'dropped' THEN 1 ELSE 0 END) as dropped,
                    AVG(progress) as avg_progress
                  FROM enrollments 
                  WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy chi tiết enrollment của một học viên
    public function getEnrollmentById($enrollmentId) {
        $query = "SELECT e.*, u.id as student_id, u.username, u.email, u.fullname, c.title as course_title, c.id as course_id
                  FROM enrollments e
                  JOIN users u ON e.student_id = u.id
                  JOIN courses c ON e.course_id = c.id
                  WHERE e.id = :enrollment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':enrollment_id', $enrollmentId);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy enrollment theo course_id và student_id
    public function getEnrollmentByCourseAndStudent($courseId, $studentId) {
        $query = "SELECT * FROM enrollments WHERE course_id = :course_id AND student_id = :student_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':student_id', $studentId);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>

