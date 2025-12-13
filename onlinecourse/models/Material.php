<?php
class Material {
    private $conn;
    private $table = "materials";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả tài liệu của 1 bài học
    public function getMaterialsByLesson($lesson_id) {
        $query = "SELECT id, lesson_id, filename, file_path, file_type, uploaded_at
                  FROM {$this->table} 
                  WHERE lesson_id = :lesson_id
                  ORDER BY uploaded_at ASC, id ASC"; // dùng uploaded_at để sắp xếp
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nếu muốn, gán lại trường 'title' để dùng trong view
        foreach ($materials as &$m) {
            $m['title'] = $m['filename'] ?? 'Tài liệu không tên';
        }

        return $materials;
    }
}
?>
