<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Khóa học của tôi</h2>

    <?php if (!empty($courses)): ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($course['image']) ?>" class="card-img-top" alt="Hình khóa học">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text">Giảng viên: <?= htmlspecialchars($course['instructor_name']) ?></p>
                            <p>Tiến độ: 
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $course['progress'] ?>%;" aria-valuenow="<?= $course['progress'] ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $course['progress'] ?>%
                                    </div>
                                </div>
                            </p>
                            <a href="index.php?controller=student&action=viewLesson&course_id=<?= $course['course_id'] ?>&lesson_id=<?= $course['first_lesson_id'] ?? 0 ?>" class="btn btn-primary">
                                Xem khóa học
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Bạn chưa đăng ký khóa học nào.</p>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
