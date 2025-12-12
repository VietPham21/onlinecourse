<?php include 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Danh mục</h1>
    <a href="index.php?controller=admin&action=createCategory" class="btn btn-primary">Thêm danh mục mới</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?php echo $cat['id']; ?></td>
                <td><?php echo htmlspecialchars($cat['name']); ?></td>
                <td><?php echo htmlspecialchars($cat['description']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editCategory&id=<?php echo $cat['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                    
                    <a href="index.php?controller=admin&action=deleteCategory&id=<?php echo $cat['id']; ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các khóa học thuộc danh mục này cũng có thể bị ảnh hưởng.');">
                       Xóa
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layouts/footer.php'; ?>