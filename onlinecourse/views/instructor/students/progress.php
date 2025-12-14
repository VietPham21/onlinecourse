<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Chi tiết Tiến độ Học tập</h2>
            <p class="text-muted mb-0">
                Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong> | 
                Học viên: <strong><?php echo htmlspecialchars($enrollment['fullname']); ?></strong>
            </p>
        </div>
        <div>
            <a href="index.php?controller=instructor&action=viewStudents&course_id=<?php echo $course['id']; ?>" 
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thông tin học viên -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Thông tin Học viên</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($enrollment['fullname']); ?></p>
                    <p><strong>Username:</strong> @<?php echo htmlspecialchars($enrollment['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($enrollment['email']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trạng thái:</strong> 
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
                        $statusColor = $statusColors[$enrollment['status']] ?? 'secondary';
                        $statusLabel = $statusLabels[$enrollment['status']] ?? $enrollment['status'];
                        ?>
                        <span class="badge bg-<?php echo $statusColor; ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                    </p>
                    <p><strong>Ngày đăng ký:</strong> <?php echo date('d/m/Y H:i', strtotime($enrollment['enrolled_date'])); ?></p>
                    <p><strong>Tiến độ hiện tại:</strong> 
                        <span class="badge bg-info"><?php echo $enrollment['progress']; ?>%</span>
                        <span class="badge bg-secondary">(Tính toán: <?php echo $calculatedProgress; ?>%)</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tổng quan tiến độ -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Đã hoàn thành</h5>
                    <h3>
                        <?php 
                        $completedCount = 0;
                        foreach ($lessonsWithProgress as $item) {
                            if ($item['is_completed'] == 1) {
                                $completedCount++;
                            }
                        }
                        echo $completedCount . ' / ' . count($lessonsWithProgress);
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Chưa học</h5>
                    <h3>
                        <?php 
                        $notStartedCount = count($lessonsWithProgress) - $completedCount;
                        echo $notStartedCount;
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Tổng số bài học</h5>
                    <h3><?php echo count($lessonsWithProgress); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar tổng thể -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3">Tiến độ Tổng thể</h5>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar <?php 
                    echo $calculatedProgress >= 100 ? 'bg-success' : 
                        ($calculatedProgress >= 50 ? 'bg-info' : 'bg-warning'); 
                ?>" 
                role="progressbar" 
                style="width: <?php echo min($calculatedProgress, 100); ?>%" 
                aria-valuenow="<?php echo $calculatedProgress; ?>" 
                aria-valuemin="0" 
                aria-valuemax="100">
                    <strong><?php echo $calculatedProgress; ?>%</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách bài học và tiến độ -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-list-check"></i> Chi tiết Tiến độ từng Bài học
            </h5>
        </div>
        <div class="card-body">
            <?php if(empty($lessonsWithProgress)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Khóa học này chưa có bài học nào.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">STT</th>
                                <th>Tên bài học</th>
                                <th style="width: 150px;">Trạng thái</th>
                                <th style="width: 180px;">Ngày hoàn thành</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lessonsWithProgress as $index => $item): 
                                $isCompleted = $item['is_completed'] == 1;
                            ?>
                            <tr class="<?php echo $isCompleted ? 'table-success' : ''; ?>">
                                <td>
                                    <span class="badge bg-secondary"><?php echo $item['lesson_order'] ?: ($index + 1); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['lesson_title']); ?></strong>
                                    <?php if(!empty($item['video_url'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-play-circle"></i> 
                                            <a href="<?php echo htmlspecialchars($item['video_url']); ?>" target="_blank">Xem video</a>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($isCompleted): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Đã hoàn thành
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-circle"></i> Chưa học
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($isCompleted && !empty($item['completed_at'])): ?>
                                        <small><?php echo date('d/m/Y H:i', strtotime($item['completed_at'])); ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
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

