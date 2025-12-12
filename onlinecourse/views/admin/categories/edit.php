<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Chỉnh sửa Danh Mục</h2>
    
    <form action="index.php?controller=admin&action=updateCategory" method="POST">
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
        
        <div class="mb-3">
            <label class="form-label">Tên danh mục:</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Mô tả:</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        
        <a href="index.php?controller=admin&action=categories" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php include 'views/layouts/footer.php'; ?>