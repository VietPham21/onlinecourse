<?php
class Course {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Hàm lấy danh sách chờ duyệt (Sửa lỗi getPendingCourses)
    public function getPendingCourses() {
        // Sử dụng JOIN để lấy tên Giảng viên và tên Danh mục thay vì hiện mỗi ID
        $query = "SELECT c.*, u.fullname as instructor_name, cat.name as category_name 
                  FROM courses c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  LEFT JOIN categories cat ON c.category_id = cat.id
                  WHERE c.is_approved = 0 
                  ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getApprovedCourses() {
        // Lấy thông tin khóa học + Tên giảng viên
        $query = "SELECT c.*, u.fullname as instructor_name 
                  FROM courses c
                  JOIN users u ON c.instructor_id = u.id
                  WHERE c.is_approved = 1 
                  ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Hàm duyệt khóa học (Sửa lỗi approve)
    public function approve($id) {
        $query = "UPDATE courses SET is_approved = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 3. Hàm xóa khóa học (Sửa lỗi delete)
    public function delete($id) {
        $query = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 4. Hàm đếm (Cho thống kê)
    public function countTotalCourses() {
        $query = "SELECT COUNT(*) as total FROM courses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    // 5. Hàm tạo khóa học mới (Cho giảng viên)
    public function create($title, $description, $instructorId, $categoryId, $price, $durationWeeks, $level, $image) {
        $query = "INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image, is_approved) 
                  VALUES (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image, 0)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $durationWeeks);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':image', $image);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // 6. Hàm lấy danh sách khóa học của giảng viên
    public function getCoursesByInstructor($instructorId) {
        $query = "SELECT c.*, cat.name as category_name 
                  FROM courses c
                  LEFT JOIN categories cat ON c.category_id = cat.id
                  WHERE c.instructor_id = :instructor_id 
                  ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>