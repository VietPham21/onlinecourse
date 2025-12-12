<?php
class Course {
    private $conn;
    private $table = "courses";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả khóa học đã duyệt
     * Có thể tìm kiếm theo từ khóa và lọc theo danh mục
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

        $query .= " ORDER BY c.created_at DESC ";

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

        // Gán ảnh mặc định nếu chưa có
        if (empty($course['image'])) {
            $course['image'] = 'assets/uploads/courses/default.jpg';
        }

        // Nếu không có giảng viên
        if (empty($course['instructor_name'])) {
            $course['instructor_name'] = 'Chưa xác định';
        }

        // Nếu không có danh mục
        if (empty($course['category_name'])) {
            $course['category_name'] = 'Không có danh mục';
        }

        return $course;
    }

    /**
     * Lấy danh sách tất cả danh mục
     */
    public function getCategories() {
        $query = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
