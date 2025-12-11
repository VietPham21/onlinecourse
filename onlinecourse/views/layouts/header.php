<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">TLU Online Course</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['role'] == 2): ?>
                            <li class="nav-item"><a class="nav-link text-warning" href="index.php?controller=admin&action=dashboard">Trang Quản Trị</a></li>
                        <?php endif; ?>
                        
                        <li class="nav-item"><a class="nav-link" href="#">Chào, <?php echo $_SESSION['username']; ?></a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-danger btn-sm text-white" href="index.php?controller=auth&action=logout">Đăng xuất</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=login">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=register">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="min-height: 600px;">