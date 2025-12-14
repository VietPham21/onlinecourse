<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Đảm bảo các biến tồn tại để tránh warning
$course = $course ?? null;
$isEnrolled = $isEnrolled ?? false;
$lessons = $lessons ?? []; // Danh sách bài học (có thể rỗng)

if (!$course) {
    echo "<div class='alert alert-danger'>Khóa học không tồn tại.</div>";
    return;
}

// Chuẩn hóa hình ảnh (nếu không có thì dùng default)
$image = $course['image'] ?? 'assets/uploads/courses/default.jpg';
if (strpos($image, 'http') === false && strpos($image, 'assets/') !== 0) {
    $image = 'assets/uploads/courses/' . $image;
}
?>

<?php include './views/layouts/header.php'; ?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($course['title']) ?></h2>
    <p class="text-muted">
        Giảng viên: <?= htmlspecialchars($course['instructor_name'] ?? 'Chưa xác định') ?>
    </p>
    <p>Danh mục: <?= htmlspecialchars($course['category_name'] ?? 'Không có danh mục') ?></p>
    <p>Giá: <?= number_format($course['price'], 0, ',', '.') ?> VND</p>
    <p>Thời lượng: <?= intval($course['duration_weeks']) ?> tuần | Trình độ: <?= htmlspecialchars($course['level'] ?? '') ?></p>

    <img src="<?= $image ?>" class="img-fluid mb-3 rounded shadow-sm">

    <p><?= nl2br(htmlspecialchars($course['description'] ?? '')) ?></p>

    <div class="mt-3 mb-4 p-3 border rounded bg-light">
        <?php if (!empty($_SESSION['user_id'])): ?>
            <?php if ($isEnrolled): ?>
                <div class="alert alert-success mb-0">
                    <strong>Bạn đã đăng ký khóa học này.</strong>
                </div>
            <?php else: ?>
                <form method="POST" action="index.php?controller=course&action=enroll">
                    <input type="hidden" name="course_id" value="<?= intval($course['id']) ?>">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        Đăng ký khóa học
                    </button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <a href="index.php?controller=auth&action=login" class="btn btn-warning btn-lg w-100">
                Đăng nhập để đăng ký
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($lessons)): ?>
        <h4 class="mt-4">Danh sách bài học</h4>
        <ul class="list-group mb-4">
            <?php foreach ($lessons as $lesson): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($lesson['title']) ?>
                    <a href="index.php?controller=lesson&action=view&lesson_id=<?= intval($lesson['id']) ?>&course_id=<?= intval($course['id']) ?>" 
                       class="btn btn-sm btn-outline-primary">
                        Xem bài
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info">Khóa học hiện chưa có bài học.</div>
    <?php endif; ?>
</div>

<?php include './views/layouts/footer.php'; ?>
