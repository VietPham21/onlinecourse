<?php
class Enrollment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
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

