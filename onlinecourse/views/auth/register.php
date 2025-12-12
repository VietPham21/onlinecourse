<?php include 'views/layouts/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4>ĐĂNG KÝ TÀI KHOẢN</h4>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?controller=auth&action=processRegister" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Họ và tên:</label>
                        <input type="text" name="fullname" class="form-control" required placeholder="Nguyễn Văn A">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập (Username):</label>
                        <input type="text" name="username" class="form-control" required placeholder="nguyenvana">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nhập lại mật khẩu:</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Đăng Ký</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Đã có tài khoản? <a href="index.php?controller=auth&action=login">Đăng nhập ngay</a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>