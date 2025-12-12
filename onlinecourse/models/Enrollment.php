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
}
?>
