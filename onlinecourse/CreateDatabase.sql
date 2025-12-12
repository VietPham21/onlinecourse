-- 1. Tạo Database
CREATE DATABASE IF NOT EXISTS onlinecourse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE onlinecourse;

-- 2. Tạo bảng Users (Người dùng) [cite: 22-29]
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255),
    role INT DEFAULT 0 COMMENT '0: học viên, 1: giảng viên, 2: quản trị viên',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tạo bảng Categories (Danh mục khóa học) [cite: 42-46]
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tạo bảng Courses (Khóa học) [cite: 30-41]
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT NOT NULL,
    category_id INT,
    price DECIMAL(10,2) DEFAULT 0,
    duration_weeks INT,
    level VARCHAR(50) COMMENT 'Beginner, Intermediate, Advanced',
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 5. Tạo bảng Enrollments (Đăng ký học) [cite: 47-53]
CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'active' COMMENT 'active, completed, dropped',
    progress INT DEFAULT 0,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. Tạo bảng Lessons (Bài học) [cite: 54-61]
CREATE TABLE lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    video_url VARCHAR(255),
    `order` INT DEFAULT 0, -- Dùng dấu huyền vì order là từ khóa trong SQL
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- 7. Tạo bảng Materials (Tài liệu bài học) [cite: 62-68]
CREATE TABLE materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

-- Thêm 3 user mẫu (Mật khẩu là: 123456)
-- Lưu ý: Mật khẩu này đã được mã hóa bằng BCRYPT theo yêu cầu 
INSERT INTO users (username, email, password, fullname, role) VALUES 
('admin', 'admin@tlu.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản Trị Viên', 2),
('giangvien', 'gv@tlu.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Thầy Giáo A', 1),
('hocvien', 'hv@tlu.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bạn Sinh Viên', 0);

-- Thêm danh mục mẫu
INSERT INTO categories (name, description) VALUES 
('Lập trình Web', 'Các khóa học về PHP, HTML, CSS, JS'),
('Khoa học dữ liệu', 'Python, Machine Learning, AI');

-- Thêm khóa học mẫu (Do giảng viên ID=2 tạo)
INSERT INTO courses (title, description, instructor_id, category_id, price, level) VALUES 
('Lập trình PHP MVC', 'Học xây dựng web từ A-Z với mô hình MVC', 2, 1, 500000, 'Intermediate');