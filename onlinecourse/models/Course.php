<?php
class Course {
    private $conn;
    private $table = "courses";

    public function __construct($db) {
        $this->conn = $db;
    }
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
    /**
     * Lấy tất cả khóa học đã duyệt, hỗ trợ tìm kiếm và lọc theo danh mục
     */
    public function getApprovedCourses($search = '', $category_id = 0) {
        $query = "
            SELECT 
                c.*, 
                u.fullname AS instructor_name, 
                cat.name AS category_name
            FROM {$this->table} c
            LEFT JOIN users u ON c.instructor_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.is_approved = 1
        ";

        if (!empty($search)) {
            $query .= " AND c.title LIKE :search ";
        }

        if ($category_id > 0) {
            $query .= " AND c.category_id = :category_id ";
        }

        $query .= " ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }

        if ($category_id > 0) {
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết 1 khóa học theo ID
     */
    public function getCourseById($id) {
        $query = "
            SELECT 
                c.*, 
                u.fullname AS instructor_name, 
                cat.name AS category_name
            FROM {$this->table} c
            LEFT JOIN users u ON c.instructor_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.id = :id
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$course) return null;

        if (empty($course['image'])) $course['image'] = 'assets/uploads/courses/default.jpg';
        if (empty($course['instructor_name'])) $course['instructor_name'] = 'Chưa xác định';
        if (empty($course['category_name'])) $course['category_name'] = 'Không có danh mục';

        return $course;
    }

    /**
     * Lấy danh sách tất cả danh mục
     */
    public function getCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // 7. Hàm lấy chi tiết khóa học theo ID
    public function getById($id) {
        $query = "SELECT c.*, cat.name as category_name 
                  FROM courses c
                  LEFT JOIN categories cat ON c.category_id = cat.id
                  WHERE c.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // 8. Hàm cập nhật khóa học
    public function update($id, $title, $description, $categoryId, $price, $durationWeeks, $level, $image = null) {
        if ($image) {
            $query = "UPDATE courses SET title = :title, description = :description, category_id = :category_id, 
                      price = :price, duration_weeks = :duration_weeks, level = :level, image = :image 
                      WHERE id = :id";
        } else {
            $query = "UPDATE courses SET title = :title, description = :description, category_id = :category_id, 
                      price = :price, duration_weeks = :duration_weeks, level = :level 
                      WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $durationWeeks);
        $stmt->bindParam(':level', $level);
        
        if ($image) {
            $stmt->bindParam(':image', $image);
        }
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // 9. Hàm xóa khóa học của giảng viên (kiểm tra quyền sở hữu)
    public function deleteByInstructor($id, $instructorId) {
        $query = "DELETE FROM courses WHERE id = :id AND instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':instructor_id', $instructorId);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // 10. Kiểm tra quyền sở hữu khóa học
    public function isOwner($courseId, $instructorId) {
        $query = "SELECT COUNT(*) as count FROM courses WHERE id = :id AND instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $courseId);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
?>
