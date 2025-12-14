<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Tạo Bài học Mới
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

                    <form action="index.php?controller=instructor&action=storeLesson" method="POST">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên bài học <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required 
                                   placeholder="Ví dụ: Bài 1 - Giới thiệu về PHP">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung bài học</label>
                            <textarea class="form-control" id="content" name="content" rows="10" 
                                      placeholder="Nhập nội dung chi tiết của bài học..."></textarea>
                            <small class="text-muted">Bạn có thể sử dụng HTML để định dạng nội dung</small>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="video_url" class="form-label">Link Video (YouTube, Vimeo, etc.)</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">Nhập link video từ YouTube, Vimeo hoặc các nền tảng khác</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="order" class="form-label">Thứ tự bài học</label>
                                <input type="number" class="form-control" id="order" name="order" 
                                       min="1" value="<?php echo $nextOrder; ?>" required>
                                <small class="text-muted">Số thứ tự hiển thị bài học trong khóa học</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?controller=instructor&action=manageLessons&course_id=<?php echo $course['id']; ?>" 
                               class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Tạo bài học
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

