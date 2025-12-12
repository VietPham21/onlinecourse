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
                  ORDER BY position ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
