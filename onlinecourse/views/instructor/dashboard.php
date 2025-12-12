<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard Giảng Viên</h2>
        <a href="index.php?controller=instructor&action=createCourse" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo khóa học mới
        </a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Tạo khóa học thành công! Khóa học đang chờ duyệt từ quản trị viên.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Tổng khóa học</h5>
                    <h3><?php echo count($courses); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Đã duyệt</h5>
                    <h3><?php echo count(array_filter($courses, function($c) { return $c['is_approved'] == 1; })); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Chờ duyệt</h5>
                    <h3><?php echo count(array_filter($courses, function($c) { return $c['is_approved'] == 0; })); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Danh sách khóa học của tôi</h3>
    
    <?php if(empty($courses)): ?>
        <div class="alert alert-info">
            <p class="mb-0">Bạn chưa có khóa học nào. <a href="index.php?controller=instructor&action=createCourse">Tạo khóa học ngay</a></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên khóa học</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Cấp độ</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($courses as $course): ?>
                    <tr>
                        <td><?php echo $course['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($course['category_name'] ?? 'Chưa phân loại'); ?></td>
                        <td><?php echo number_format($course['price']); ?> VNĐ</td>
                        <td>
                            <?php 
                            $levelColors = [
                                'Beginner' => 'success',
                                'Intermediate' => 'warning',
                                'Advanced' => 'danger'
                            ];
                            $levelColor = $levelColors[$course['level']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $levelColor; ?>">
                                <?php echo htmlspecialchars($course['level'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($course['is_approved'] == 1): ?>
                                <span class="badge bg-success">Đã duyệt</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Chờ duyệt</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($course['created_at'])); ?></td>
                        <td>
                            <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>" 
                               class="btn btn-sm btn-info">Xem</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>

