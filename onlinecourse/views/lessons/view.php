<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Bài học</h2>

    <ul class="list-group mb-4">
        <?php foreach ($lessons as $lesson): ?>
            <li class="list-group-item <?php echo $lesson['id'] == $lesson_id ? 'active' : ''; ?>">
                <a href="index.php?controller=lesson&action=view&lesson_id=<?= $lesson['id'] ?>&course_id=<?= $course_id ?>" class="text-decoration-none <?php echo $lesson['id'] == $lesson_id ? 'text-white' : ''; ?>">
                    <?= $lesson['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>Tài liệu</h4>
    <ul class="list-group">
        <?php foreach ($materials as $m): ?>
            <li class="list-group-item">
                <a href="<?= $m['file_path'] ?>" target="_blank"><?= $m['title'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include 'views/layouts/footer.php'; ?>
