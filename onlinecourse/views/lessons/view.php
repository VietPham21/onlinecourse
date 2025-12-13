<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Bài học</h2>

    <?php if (!empty($lessons)): ?>
        <ul class="list-group mb-4">
            <?php foreach ($lessons as $lessonItem): 
                $lessonTitle = $lessonItem['title'] ?? 'Không có tiêu đề';
                $lessonId = $lessonItem['id'] ?? 0;
            ?>
                <li class="list-group-item <?= ($lessonId == $lesson_id) ? 'active' : '' ?>">
                    <a href="index.php?controller=lesson&action=view&lesson_id=<?= $lessonId ?>&course_id=<?= $course_id ?>" 
                       class="text-decoration-none <?= ($lessonId == $lesson_id) ? 'text-white' : '' ?>">
                        <?= htmlspecialchars($lessonTitle) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Khóa học chưa có bài học nào.</p>
    <?php endif; ?>

    <h4>Nội dung bài học: <?= htmlspecialchars($lesson['title'] ?? 'Không có tiêu đề') ?></h4>
    <p><?= nl2br(htmlspecialchars($lesson['content'] ?? 'Nội dung chưa có')) ?></p>

    <h4>Tài liệu</h4>
    <?php if (!empty($materials)): ?>
        <ul class="list-group">
            <?php foreach ($materials as $materialItem): 
                $materialTitle = $materialItem['title'] ?? 'Tài liệu không tên';
                $filePath = $materialItem['file_path'] ?? '#';
            ?>
                <li class="list-group-item">
                    <a href="<?= htmlspecialchars($filePath) ?>" target="_blank"><?= htmlspecialchars($materialTitle) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Chưa có tài liệu cho bài học này.</p>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
