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

    // Lấy tất cả bài học của một khóa học
    public function getByCourseId($courseId) {
        $query = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY `order` ASC, created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy chi tiết một bài học
    public function getById($id) {
        $query = "SELECT l.*, c.instructor_id 
                  FROM lessons l
                  JOIN courses c ON l.course_id = c.id
                  WHERE l.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tạo bài học mới
    public function create($courseId, $title, $content, $videoUrl, $order) {
        $query = "INSERT INTO lessons (course_id, title, content, video_url, `order`) 
                  VALUES (:course_id, :title, :content, :video_url, :order)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':video_url', $videoUrl);
        $stmt->bindParam(':order', $order);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Cập nhật bài học
    public function update($id, $title, $content, $videoUrl, $order) {
        $query = "UPDATE lessons SET title = :title, content = :content, video_url = :video_url, `order` = :order 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':video_url', $videoUrl);
        $stmt->bindParam(':order', $order);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Xóa bài học
    public function delete($id) {
        $query = "DELETE FROM lessons WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Đếm số bài học của một khóa học
    public function countByCourseId($courseId) {
        $query = "SELECT COUNT(*) as total FROM lessons WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    // Kiểm tra bài học thuộc về khóa học của giảng viên
    public function belongsToInstructor($lessonId, $instructorId) {
        $query = "SELECT COUNT(*) as count 
                  FROM lessons l
                  JOIN courses c ON l.course_id = c.id
                  WHERE l.id = :lesson_id AND c.instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    // Lấy số thứ tự tiếp theo cho bài học mới
    public function getNextOrder($courseId) {
        $query = "SELECT MAX(`order`) as max_order FROM lessons WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        $result = $stmt->fetch();
        return ($result['max_order'] ?? 0) + 1;
    }
}
?>

