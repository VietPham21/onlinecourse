<?php include 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý người dùng</h1>
    <a href="index.php?controller=admin&action=dashboard" class="btn btn-secondary">Quay lại Dashboard</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <?php 
                        if($user['role'] == 2) echo '<span class="badge bg-danger">Admin</span>';
                        elseif($user['role'] == 1) echo '<span class="badge bg-primary">Giảng viên</span>';
                        else echo '<span class="badge bg-secondary">Học viên</span>';
                    ?>
                </td>
                <td>
                    <?php 
                        // Kiểm tra status (mặc định database là 1)
                        if(isset($user['status']) && $user['status'] == 0) 
                            echo '<span class="badge bg-warning text-dark">Đã khóa</span>';
                        else 
                            echo '<span class="badge bg-success">Hoạt động</span>';
                    ?>
                </td>
                <td>
                    <form action="index.php?controller=admin&action=toggleUserStatus" method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="hidden" name="current_status" value="<?php echo isset($user['status']) ? $user['status'] : 1; ?>">
                        
                        <?php if(isset($user['status']) && $user['status'] == 0): ?>
                            <button type="submit" class="btn btn-success btn-sm">Mở khóa</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')">Khóa</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layouts/footer.php'; ?>