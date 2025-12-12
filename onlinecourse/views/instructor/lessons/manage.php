<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Quản lý Bài học</h2>
            <p class="text-muted mb-0">Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
        </div>
        <div>
            <a href="index.php?controller=instructor&action=dashboard" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Về Dashboard
            </a>
            <a href="index.php?controller=instructor&action=createLesson&course_id=<?php echo $course['id']; ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm bài học mới
            </a>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Tạo bài học thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Cập nhật bài học thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Xóa bài học thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Có lỗi xảy ra! Vui lòng thử lại.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul"></i> Danh sách Bài học 
                <span class="badge bg-light text-dark"><?php echo count($lessons); ?> bài</span>
            </h5>
        </div>
        <div class="card-body">
            <?php if(empty($lessons)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Chưa có bài học nào trong khóa học này.
                    <br>
                    <a href="index.php?controller=instructor&action=createLesson&course_id=<?php echo $course['id']; ?>" class="btn btn-primary mt-2">
                        Tạo bài học đầu tiên
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">STT</th>
                                <th>Tên bài học</th>
                                <th style="width: 200px;">Video URL</th>
                                <th style="width: 150px;">Ngày tạo</th>
                                <th style="width: 200px;" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lessons as $index => $lesson): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $lesson['order'] ?: ($index + 1); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>
                                    <?php if(!empty($lesson['content'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo substr(strip_tags($lesson['content']), 0, 100); ?>
                                            <?php echo strlen($lesson['content']) > 100 ? '...' : ''; ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($lesson['video_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($lesson['video_url']); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-play-circle"></i> Xem video
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa có</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo date('d/m/Y H:i', strtotime($lesson['created_at'])); ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="index.php?controller=instructor&action=editLesson&id=<?php echo $lesson['id']; ?>" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <a href="index.php?controller=instructor&action=deleteLesson&id=<?php echo $lesson['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Xóa"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa bài học này? Hành động này không thể hoàn tác!');">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </div>
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

