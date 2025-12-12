<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Sửa Khóa Học
                    </h4>
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
                            <div class="alert alert-danger">Bạn không có quyền sửa khóa học này!</div>
                        <?php elseif($_GET['msg'] == 'notfound'): ?>
                            <div class="alert alert-warning">Không tìm thấy khóa học!</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="index.php?controller=instructor&action=updateCourse" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên khóa học <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required 
                                   value="<?php echo htmlspecialchars($course['title']); ?>"
                                   placeholder="Ví dụ: Lập trình PHP từ cơ bản đến nâng cao">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả khóa học</label>
                            <textarea class="form-control" id="description" name="description" rows="5" 
                                      placeholder="Mô tả chi tiết về khóa học, nội dung sẽ học, đối tượng phù hợp..."><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Danh mục</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php if(!empty($categories)): ?>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                <?php echo ($course['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="level" class="form-label">Cấp độ</label>
                                <select class="form-select" id="level" name="level">
                                    <option value="">-- Chọn cấp độ --</option>
                                    <option value="Beginner" <?php echo ($course['level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner (Cơ bản)</option>
                                    <option value="Intermediate" <?php echo ($course['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate (Trung bình)</option>
                                    <option value="Advanced" <?php echo ($course['level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced (Nâng cao)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Giá khóa học (VNĐ)</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       min="0" step="1000" value="<?php echo $course['price']; ?>" placeholder="0">
                                <small class="text-muted">Nhập 0 nếu khóa học miễn phí</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duration_weeks" class="form-label">Thời lượng (tuần)</label>
                                <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" 
                                       min="1" value="<?php echo $course['duration_weeks'] ?? ''; ?>" placeholder="Ví dụ: 8">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh đại diện khóa học</label>
                            <?php if(!empty($course['image'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars($course['image']); ?>" 
                                         alt="Ảnh hiện tại" 
                                         style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
                                    <p class="text-muted small mt-1">Ảnh hiện tại</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" 
                                   accept="image/jpeg,image/jpg,image/png,image/gif">
                            <small class="text-muted">Chấp nhận định dạng: JPG, PNG, GIF (tối đa 5MB). Để trống nếu không muốn thay đổi ảnh.</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?controller=instructor&action=dashboard" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Cập nhật khóa học
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

