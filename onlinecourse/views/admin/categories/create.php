<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Thêm Danh Mục Mới</h2>
    
    <form action="index.php?controller=admin&action=storeCategory" method="POST">
        <div class="mb-3">
            <label class="form-label">Tên danh mục:</label>
            <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Lập trình Web">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Mô tả:</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả ngắn gọn về danh mục..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-success">Lưu lại</button>
        
        <a href="index.php?controller=admin&action=categories" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php include 'views/layouts/footer.php'; ?>