<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Sửa Bài học
                    </h4>
                    <small>Khóa học: <?php echo htmlspecialchars($course['title']); ?></small>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_GET['msg'])): ?>
                        <?php if($_GET['msg'] == 'unauthorized'): ?>
                            <div class="alert alert-danger">Bạn không có quyền sửa bài học này!</div>
                        <?php elseif($_GET['msg'] == 'notfound'): ?>
                            <div class="alert alert-warning">Không tìm thấy bài học!</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="index.php?controller=instructor&action=updateLesson" method="POST">
                        <input type="hidden" name="id" value="<?php echo $lesson['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên bài học <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required 
                                   value="<?php echo htmlspecialchars($lesson['title']); ?>"
                                   placeholder="Ví dụ: Bài 1 - Giới thiệu về PHP">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung bài học</label>
                            <textarea class="form-control" id="content" name="content" rows="10" 
                                      placeholder="Nhập nội dung chi tiết của bài học..."><?php echo htmlspecialchars($lesson['content'] ?? ''); ?></textarea>
                            <small class="text-muted">Bạn có thể sử dụng HTML để định dạng nội dung</small>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="video_url" class="form-label">Link Video (YouTube, Vimeo, etc.)</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                       value="<?php echo htmlspecialchars($lesson['video_url'] ?? ''); ?>"
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">Nhập link video từ YouTube, Vimeo hoặc các nền tảng khác</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="order" class="form-label">Thứ tự bài học</label>
                                <input type="number" class="form-control" id="order" name="order" 
                                       min="1" value="<?php echo $lesson['order'] ?: 1; ?>" required>
                                <small class="text-muted">Số thứ tự hiển thị bài học trong khóa học</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?controller=instructor&action=manageLessons&course_id=<?php echo $course['id']; ?>" 
                               class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Cập nhật bài học
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

