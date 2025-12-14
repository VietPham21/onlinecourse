<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include './views/layouts/header.php';
?>
<div class="container mt-4">
    <h2>Học viên</h2>
    <p>Chào mừng <?= $_SESSION['fullname'] ?? 'học viên' ?></p>

    <h4>Khóa học đã đăng ký</h4>
    <?php if (!empty($myCourses)): ?>
        <div class="row">
            <?php foreach ($myCourses as $course): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($course['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($course['title']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text text-muted">Giảng viên: <?= htmlspecialchars($course['instructor_name']) ?></p>
                            <p class="card-text mb-2">
                                Tiến độ: <?= intval($course['progress']) ?>%
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?= intval($course['progress']) ?>%;" aria-valuenow="<?= intval($course['progress']) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </p>
                            <div class="mt-auto">
                                <a href="index.php?controller=course&action=detail&id=<?= intval($course['course_id']) ?>" class="btn btn-primary btn-sm mb-2">Xem chi tiết</a>
                                <?php
                                // Nếu có bài học đầu tiên, hiển thị nút "Vào học"
                                $lessons = (new Lesson((new Database())->connect()))->getLessonsByCourse($course['course_id']);
                                if (!empty($lessons)):
                                    $firstLesson = $lessons[0];
                                ?>
                                    <a href="index.php?controller=student&action=viewLesson&course_id=<?= $course['course_id'] ?>&lesson_id=<?= $firstLesson['id'] ?>" class="btn btn-success btn-sm">Vào học</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Bạn chưa đăng ký khóa học nào.</p>
    <?php endif; ?>
</div>

<?php include './views/layouts/footer.php'; ?>
