<?php
if (session_status() == PHP_SESSION_NONE) {
    @session_start();
}

// đảm bảo các biến tồn tại
$course = $course ?? null;
$lessons = $lessons ?? [];
$isEnrolled = $isEnrolled ?? false;
$progress = isset($progress) ? intval($progress) : 0;

include './views/layouts/header.php';
?>

<div class="container mt-4">
    <?php if (!$course): ?>
        <div class="alert alert-danger">Khóa học không tồn tại.</div>
    <?php else: ?>
        <h2><?= htmlspecialchars($course['title']) ?></h2>
        <p class="text-muted">Giảng viên: <?= htmlspecialchars($course['instructor_name']) ?></p>
        <img src="<?= !empty($course['image']) ? $course['image'] : 'assets/uploads/courses/default.jpg' ?>" 
             class="img-fluid mb-3 rounded" alt="<?= htmlspecialchars($course['title']) ?>">
        <p><?= htmlspecialchars($course['description']) ?></p>

        <!-- Nút đăng ký khóa học -->
        <div class="mb-4">
            <?php if ($isEnrolled): ?>
                <div class="alert alert-success mb-0">Bạn đã đăng ký khóa học này.</div>
            <?php else: ?>
                <form method="POST" action="index.php?controller=student&action=enroll">
                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                    <button type="submit" class="btn btn-success btn-lg">Đăng ký khóa học</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Tiến độ học tập -->
        <?php if ($isEnrolled): ?>
            <div class="mb-4">
                <h5>Tiến độ học tập</h5>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" 
                         style="width: <?= $progress ?>%;" 
                         aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                        <?= $progress ?>%
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Danh sách bài học -->
        <h4>Danh sách bài học</h4>
        <?php if (count($lessons) > 0): ?>
            <ul class="list-group">
                <?php foreach ($lessons as $lesson): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($lesson['title']) ?>
                        <?php if ($isEnrolled): ?>
                            <a href="index.php?controller=student&action=viewLesson&lesson_id=<?= $lesson['id'] ?>" 
                               class="btn btn-sm btn-outline-primary">Xem bài</a>
                        <?php else: ?>
                            <span class="text-muted">Đăng ký để học</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info mt-2">Chưa có bài học nào.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include './views/layouts/footer.php'; ?>
