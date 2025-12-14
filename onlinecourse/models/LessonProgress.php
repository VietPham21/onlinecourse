<?php
class LessonProgress {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tiến độ học tập của một học viên trong một khóa học
    public function getProgressByEnrollmentId($enrollmentId) {
        // Lấy course_id từ enrollment
        $enrollmentQuery = "SELECT course_id FROM enrollments WHERE id = :enrollment_id";
        $enrollmentStmt = $this->conn->prepare($enrollmentQuery);
        $enrollmentStmt->bindParam(':enrollment_id', $enrollmentId);
        $enrollmentStmt->execute();
        $enrollment = $enrollmentStmt->fetch();
        
        if (!$enrollment) {
            return [];
        }
        
        $courseId = $enrollment['course_id'];
        
        // Lấy tất cả bài học và tiến độ (nếu có)
        $query = "SELECT l.id as lesson_id, l.title as lesson_title, l.`order` as lesson_order, l.video_url,
                         lp.id as progress_id, lp.is_completed, lp.completed_at
                  FROM lessons l
                  LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.enrollment_id = :enrollment_id
                  WHERE l.course_id = :course_id
                  ORDER BY l.`order` ASC, l.created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':enrollment_id', $enrollmentId);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy tiến độ học tập theo course_id và student_id
    public function getProgressByCourseAndStudent($courseId, $studentId) {
        // Lấy enrollment_id trước
        $enrollmentQuery = "SELECT id FROM enrollments WHERE course_id = :course_id AND student_id = :student_id";
        $enrollmentStmt = $this->conn->prepare($enrollmentQuery);
        $enrollmentStmt->bindParam(':course_id', $courseId);
        $enrollmentStmt->bindParam(':student_id', $studentId);
        $enrollmentStmt->execute();
        $enrollment = $enrollmentStmt->fetch();
        
        if (!$enrollment) {
            return [];
        }
        
        return $this->getProgressByEnrollmentId($enrollment['id']);
    }

    // Tính toán tiến độ dựa trên số bài học đã hoàn thành
    public function calculateProgress($courseId, $studentId) {
        // Lấy tổng số bài học
        $totalLessonsQuery = "SELECT COUNT(*) as total FROM lessons WHERE course_id = :course_id";
        $totalStmt = $this->conn->prepare($totalLessonsQuery);
        $totalStmt->bindParam(':course_id', $courseId);
        $totalStmt->execute();
        $totalResult = $totalStmt->fetch();
        $totalLessons = $totalResult['total'];
        
        if ($totalLessons == 0) {
            return 0;
        }
        
        // Lấy số bài học đã hoàn thành
        $completedQuery = "SELECT COUNT(*) as completed 
                          FROM lesson_progress lp
                          JOIN enrollments e ON lp.enrollment_id = e.id
                          WHERE e.course_id = :course_id AND e.student_id = :student_id AND lp.is_completed = 1";
        $completedStmt = $this->conn->prepare($completedQuery);
        $completedStmt->bindParam(':course_id', $courseId);
        $completedStmt->bindParam(':student_id', $studentId);
        $completedStmt->execute();
        $completedResult = $completedStmt->fetch();
        $completedLessons = $completedResult['completed'];
        
        return round(($completedLessons / $totalLessons) * 100);
    }

    // Đánh dấu bài học đã hoàn thành
    public function markAsCompleted($enrollmentId, $lessonId) {
        $query = "INSERT INTO lesson_progress (enrollment_id, lesson_id, is_completed, completed_at) 
                  VALUES (:enrollment_id, :lesson_id, 1, NOW())
                  ON DUPLICATE KEY UPDATE is_completed = 1, completed_at = NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':enrollment_id', $enrollmentId);
        $stmt->bindParam(':lesson_id', $lessonId);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Đánh dấu bài học chưa hoàn thành
    public function markAsIncomplete($enrollmentId, $lessonId) {
        $query = "UPDATE lesson_progress SET is_completed = 0, completed_at = NULL 
                  WHERE enrollment_id = :enrollment_id AND lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':enrollment_id', $enrollmentId);
        $stmt->bindParam(':lesson_id', $lessonId);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
}
?>

