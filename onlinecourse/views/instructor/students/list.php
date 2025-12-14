<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Danh sách Học viên Đã Đăng ký</h2>
            <p class="text-muted mb-0">Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
        </div>
        <div>
            <a href="index.php?controller=instructor&action=dashboard" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Về Dashboard
            </a>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Tổng học viên</h5>
                    <h3><?php echo $statistics['total'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Đang học</h5>
                    <h3><?php echo $statistics['active'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Đã hoàn thành</h5>
                    <h3><?php echo $statistics['completed'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Tiến độ TB</h5>
                    <h3><?php echo round($statistics['avg_progress'] ?? 0, 1); ?>%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách học viên -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-people"></i> Danh sách Học viên 
                <span class="badge bg-light text-dark"><?php echo count($students); ?> người</span>
            </h5>
        </div>
        <div class="card-body">
            <?php if(empty($students)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Chưa có học viên nào đăng ký khóa học này.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">STT</th>
                                <th>Họ và tên</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th style="width: 120px;">Trạng thái</th>
                                <th style="width: 120px;">Tiến độ</th>
                                <th style="width: 150px;">Ngày đăng ký</th>
                                <th style="width: 120px;" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $index => $student): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($student['fullname'] ?? 'Chưa cập nhật'); ?></strong>
                                </td>
                                <td>
                                    <span class="text-muted">@<?php echo htmlspecialchars($student['username']); ?></span>
                                </td>
                                <td>
                                    <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($student['email']); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'active' => 'success',
                                        'completed' => 'info',
                                        'dropped' => 'danger'
                                    ];
                                    $statusLabels = [
                                        'active' => 'Đang học',
                                        'completed' => 'Hoàn thành',
                                        'dropped' => 'Đã hủy'
                                    ];
                                    $statusColor = $statusColors[$student['status']] ?? 'secondary';
                                    $statusLabel = $statusLabels[$student['status']] ?? $student['status'];
                                    ?>
                                    <span class="badge bg-<?php echo $statusColor; ?>">
                                        <?php echo htmlspecialchars($statusLabel); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar <?php 
                                            echo $student['progress'] >= 100 ? 'bg-success' : 
                                                ($student['progress'] >= 50 ? 'bg-info' : 'bg-warning'); 
                                        ?>" 
                                        role="progressbar" 
                                        style="width: <?php echo min($student['progress'], 100); ?>%" 
                                        aria-valuenow="<?php echo $student['progress']; ?>" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                            <?php echo $student['progress']; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small><?php echo date('d/m/Y H:i', strtotime($student['enrolled_date'])); ?></small>
                                </td>
                                <td class="text-center">
                                    <a href="index.php?controller=instructor&action=viewStudentProgress&enrollment_id=<?php echo $student['id']; ?>" 
                                       class="btn btn-sm btn-info" title="Xem chi tiết tiến độ">
                                        <i class="bi bi-graph-up"></i> Tiến độ
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

