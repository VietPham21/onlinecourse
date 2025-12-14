<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Tạo Khóa Học Mới
                    </h4>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Tạo khóa học thành công! Khóa học đang chờ duyệt từ quản trị viên.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?controller=instructor&action=storeCourse" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên khóa học <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required 
                                   placeholder="Ví dụ: Lập trình PHP từ cơ bản đến nâng cao">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả khóa học</label>
                            <textarea class="form-control" id="description" name="description" rows="5" 
                                      placeholder="Mô tả chi tiết về khóa học, nội dung sẽ học, đối tượng phù hợp..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Danh mục</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php if(!empty($categories)): ?>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>">
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
                                    <option value="Beginner">Beginner (Cơ bản)</option>
                                    <option value="Intermediate">Intermediate (Trung bình)</option>
                                    <option value="Advanced">Advanced (Nâng cao)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Giá khóa học (VNĐ)</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       min="0" step="1000" value="0" placeholder="0">
                                <small class="text-muted">Nhập 0 nếu khóa học miễn phí</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duration_weeks" class="form-label">Thời lượng (tuần)</label>
                                <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" 
                                       min="1" placeholder="Ví dụ: 8">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh đại diện khóa học</label>
                            <input type="file" class="form-control" id="image" name="image" 
                                   accept="image/jpeg,image/jpg,image/png,image/gif">
                            <small class="text-muted">Chấp nhận định dạng: JPG, PNG, GIF (tối đa 5MB)</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?controller=instructor&action=dashboard" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Tạo khóa học
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

