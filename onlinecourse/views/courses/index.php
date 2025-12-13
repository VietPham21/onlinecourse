<?php
include 'views/layouts/header.php';

// Giá trị tìm kiếm & category_id giữ trạng thái form
$search = $_GET['search'] ?? '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm text-center">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold text-primary">Chào mừng đến với TLU Online Course</h1>
        <p class="col-md-8 fs-4 mx-auto">Nền tảng học tập trực tuyến hàng đầu dành cho sinh viên Thủy Lợi.</p>
    </div>
</div>

<div class="container mt-4">
    <h2>Danh sách khóa học</h2>

    <!-- Form tìm kiếm & lọc danh mục -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="controller" value="course">
        <input type="hidden" name="action" value="index">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="0" <?= $category_id === 0 ? 'selected' : '' ?>>Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category_id === intval($cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <?php if (empty($courses)): ?>
        <div class="alert alert-warning">Không tìm thấy khóa học nào.</div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($courses as $course): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($course['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($course['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['category_name'] ?? 'Không có danh mục') ?></p>
                            <p class="card-text"><small class="text-muted">Giảng viên: <?= htmlspecialchars($course['instructor_name']) ?></small></p>
                            <p class="card-text"><strong>Giá:</strong> <?= number_format($course['price'], 0, ',', '.') ?> VND</p>
                        </div>
                        <div class="card-footer">
                            <a href="index.php?controller=course&action=detail&id=<?= $course['id'] ?>" class="btn btn-success w-100">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
