<?php
class Lesson {
    private $conn;
    private $table = "lessons";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả bài học của 1 khóa học
    public function getLessonsByCourse($course_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE course_id = :course_id
                  ORDER BY `order` ASC, id ASC"; // `order` bao quanh để tránh lỗi SQL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 bài học theo ID
    public function getLessonById($lesson_id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :lesson_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
