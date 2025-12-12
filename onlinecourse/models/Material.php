<?php
class Material {
    private $conn;
    private $table = "materials";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tài liệu của 1 bài học
    public function getMaterialsByLesson($lesson_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE lesson_id = :lesson_id
                  ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
