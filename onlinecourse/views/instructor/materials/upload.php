<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Quản lý Tài liệu Học tập</h2>
            <p class="text-muted mb-0">
                Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong> | 
                Bài học: <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>
            </p>
        </div>
        <div>
            <a href="index.php?controller=instructor&action=manageLessons&course_id=<?php echo $course['id']; ?>" 
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Upload tài liệu thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Xóa tài liệu thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Có lỗi xảy ra! Vui lòng thử lại.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row">
        <!-- Form Upload -->
        <div class="col-md-5 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cloud-upload"></i> Đăng tải Tài liệu Mới
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?controller=instructor&action=storeMaterial" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">Chọn file tài liệu <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file" name="file" required>
                            <small class="text-muted">
                                Chấp nhận: PDF, Word (.doc, .docx), Excel (.xls, .xlsx), PowerPoint (.ppt, .pptx), 
                                Text (.txt), ZIP, RAR, Images (JPG, PNG, GIF)
                                <br>Kích thước tối đa: 50MB
                            </small>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-upload"></i> Upload Tài liệu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách Tài liệu -->
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark"></i> Danh sách Tài liệu 
                        <span class="badge bg-light text-dark"><?php echo count($materials); ?> file</span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(empty($materials)): ?>
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> Chưa có tài liệu nào được đăng tải.
                            <br>Hãy upload tài liệu đầu tiên ở form bên trái.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên file</th>
                                        <th>Loại</th>
                                        <th>Ngày upload</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($materials as $material): ?>
                                    <tr>
                                        <td>
                                            <i class="bi bi-file-earmark"></i>
                                            <strong><?php echo htmlspecialchars($material['filename']); ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $fileType = $material['file_type'];
                                            $typeIcons = [
                                                'application/pdf' => 'bi-file-pdf text-danger',
                                                'application/msword' => 'bi-file-word text-primary',
                                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'bi-file-word text-primary',
                                                'application/vnd.ms-excel' => 'bi-file-excel text-success',
                                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bi-file-excel text-success',
                                                'application/vnd.ms-powerpoint' => 'bi-file-ppt text-warning',
                                                'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'bi-file-ppt text-warning',
                                                'application/zip' => 'bi-file-zip text-secondary',
                                                'application/x-rar-compressed' => 'bi-file-zip text-secondary',
                                                'image/jpeg' => 'bi-file-image text-info',
                                                'image/png' => 'bi-file-image text-info',
                                                'image/gif' => 'bi-file-image text-info'
                                            ];
                                            $icon = $typeIcons[$fileType] ?? 'bi-file-earmark';
                                            ?>
                                            <i class="bi <?php echo $icon; ?>"></i>
                                            <small><?php echo htmlspecialchars($fileType); ?></small>
                                        </td>
                                        <td>
                                            <small><?php echo date('d/m/Y H:i', strtotime($material['uploaded_at'])); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo htmlspecialchars($material['file_path']); ?>" 
                                               target="_blank" 
                                               class="btn btn-sm btn-info" 
                                               title="Tải xuống">
                                                <i class="bi bi-download"></i> Tải
                                            </a>
                                            <a href="index.php?controller=instructor&action=deleteMaterial&id=<?php echo $material['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               title="Xóa"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');">
                                                <i class="bi bi-trash"></i> Xóa
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
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

