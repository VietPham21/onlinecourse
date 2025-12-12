<?php
if (session_status() == PHP_SESSION_NONE) {
    @session_start();
}

// đảm bảo các biến tồn tại để tránh warning
$courses = $courses ?? [];
$categories = $categories ?? []; // nếu có danh mục để filter
include './views/layouts/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Danh sách khóa học</h2>

    <!-- Form tìm kiếm & lọc -->
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="Tìm kiếm khóa học..." 
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" 
                            <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <!-- Danh sách khóa học -->
    <div class="row">
        <?php if (count($courses) > 0): ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= !empty($course['image']) ? $course['image'] : 'assets/uploads/courses/default.jpg' ?>" 
                             class="card-img-top" alt="<?= htmlspecialchars($course['title']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text text-truncate"><?= htmlspecialchars($course['description']) ?></p>
                            <p class="text-muted mb-2"><small>Giảng viên: <?= htmlspecialchars($course['instructor_name']) ?></small></p>
                            <a href="index.php?controller=student&action=courseDetail&id=<?= $course['id'] ?>" 
                               class="btn btn-primary mt-auto">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Không tìm thấy khóa học nào.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include './views/layouts/footer.php'; ?>
