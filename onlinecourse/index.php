<?php
session_start();
require_once 'config/Database.php';
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerPath = "controllers/$controllerClass.php";

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controllerObject = new $controllerClass();
    if (method_exists($controllerObject, $actionName)) {
        $controllerObject->$actionName();
    } else {
        echo "Lỗi 404: Action '$actionName' không tồn tại trong $controllerClass.";
    }
} else {
    echo "Lỗi 404: Controller '$controllerClass' không tìm thấy.";
}
?>



<?php
// // fix_all_pass.php
// require_once 'config/Database.php';

// $database = new Database();
// $db = $database->connect();

// // Mật khẩu chung muốn đặt lại cho TẤT CẢ user
// $new_password_raw = '123456'; 
// $new_password_hash = password_hash($new_password_raw, PASSWORD_BCRYPT);

// try {
//     // Cập nhật toàn bộ bảng users
//     $sql = "UPDATE users SET password = :password";
//     $stmt = $db->prepare($sql);
//     $stmt->bindParam(':password', $new_password_hash);
    
//     if($stmt->execute()) {
//         echo "<div style='font-family: sans-serif; padding: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;'>";
//         echo "<h2>✅ Đã sửa thành công!</h2>";
//         echo "<p>Tất cả tài khoản trong database đã được đổi mật khẩu thành: <b>$new_password_raw</b></p>";
//         echo "<p>Bây giờ bạn có thể quay lại đăng nhập:</p>";
//         echo "<a href='index.php?controller=auth&action=login' style='background: #198754; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Về trang Đăng nhập</a>";
//         echo "</div>";
//     } else {
//         echo "Lỗi SQL: Không thể cập nhật.";
//     }
// } catch(PDOException $e) {
//     echo "Lỗi: " . $e->getMessage();
// }
?>
