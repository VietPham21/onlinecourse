<?php include 'views/layouts/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm text-center">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold text-primary">Chào mừng đến với TLU Online Course</h1>
        <p class="col-md-8 fs-4 mx-auto">Nền tảng học tập trực tuyến hàng đầu dành cho sinh viên Thủy Lợi.</p>
    </div>
</div>

<div class="container">
    <h2 class="mb-4 border-bottom pb-2">Các khóa học mới nhất</h2>
    
    <div class="row">
        <?php if(empty($courses)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Chưa có khóa học nào được xuất bản.</p>
            </div>
        <?php else: ?>
            <?php foreach($courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div style="height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <?php if(!empty($course['image'])): ?>
                            <img src="uploads/courses/<?php echo $course['image']; ?>" alt="Course Image" style="width: 100%; height: auto;">
                        <?php else: ?>
                            <span class="text-muted">No Image</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="card-text text-muted">
                            <small>Giảng viên: <?php echo htmlspecialchars($course['instructor_name']); ?></small>
                        </p>
                        <p class="card-text">
                            <?php echo substr(htmlspecialchars($course['description']), 0, 100); ?>...
                        </p>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                        <span class="text-primary fw-bold">
                            <?php echo number_format($course['price']); ?> VNĐ
                        </span>
                        <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>