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
    // Lấy tất cả tài liệu của một bài học
    public function getByLessonId($lessonId) {
        $query = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY uploaded_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy chi tiết một tài liệu
    public function getById($id) {
        $query = "SELECT m.*, l.course_id, c.instructor_id 
                  FROM materials m
                  JOIN lessons l ON m.lesson_id = l.id
                  JOIN courses c ON l.course_id = c.id
                  WHERE m.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tạo tài liệu mới
    public function create($lessonId, $filename, $filePath, $fileType) {
        $query = "INSERT INTO materials (lesson_id, filename, file_path, file_type) 
                  VALUES (:lesson_id, :filename, :file_path, :file_type)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':file_path', $filePath);
        $stmt->bindParam(':file_type', $fileType);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Xóa tài liệu
    public function delete($id) {
        $query = "DELETE FROM materials WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Đếm số tài liệu của một bài học
    public function countByLessonId($lessonId) {
        $query = "SELECT COUNT(*) as total FROM materials WHERE lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Kiểm tra tài liệu thuộc về bài học của giảng viên
    public function belongsToInstructor($materialId, $instructorId) {
        $query = "SELECT COUNT(*) as count 
                  FROM materials m
                  JOIN lessons l ON m.lesson_id = l.id
                  JOIN courses c ON l.course_id = c.id
                  WHERE m.id = :material_id AND c.instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':material_id', $materialId);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
?>

