<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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

                    <!-- Admin -->
                    <?php if($_SESSION['role'] == 2): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-warning" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Quản trị viên
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="index.php?controller=admin&action=dashboard">Thống kê</a></li>
                                <li><a class="dropdown-item" href="index.php?controller=admin&action=users">Quản lý Người dùng</a></li>
                                <li><a class="dropdown-item" href="index.php?controller=admin&action=categories">Quản lý Danh mục</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?controller=admin&action=pendingCourses">Duyệt khóa học</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Học viên -->
                    <?php if($_SESSION['role'] == 0): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-info" href="#" id="studentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Học viên
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="studentDropdown">
                                <li><a class="dropdown-item" href="index.php?controller=student&action=dashboard">Khóa học của tôi</a></li>
                                <li><a class="dropdown-item" href="index.php?controller=course&action=index">Danh sách khóa học</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Chào, <?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-danger btn-sm text-white" href="index.php?controller=auth&action=logout">Đăng xuất</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=login">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=register">Đăng ký</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<div class="container" style="min-height: 600px;">
