<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include './views/layouts/header.php';
?>

<div class="container mt-4">
    <h2>Học viên</h2>
    <p>Chào mừng, <strong><?= $_SESSION['fullname'] ?? 'Học viên' ?></strong></p>

    <h4>Khóa học đã đăng ký</h4>
    <?php if (!empty($myCourses)): ?>
        <div class="row">
            <?php foreach ($myCourses as $course): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?= $course['image'] ?>" class="card-img-top" alt="<?= $course['title'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $course['title'] ?></h5>
                            <p class="card-text text-muted">Giảng viên: <?= $course['instructor_name'] ?></p>
                            <p class="card-text">
                                Tiến độ: <?= $course['progress'] ?>%
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $course['progress'] ?>%;" aria-valuenow="<?= $course['progress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </p>
                            <a href="index.php?controller=course&action=detail&id=<?= $course['course_id'] ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
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
