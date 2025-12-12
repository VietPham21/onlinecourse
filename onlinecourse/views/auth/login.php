<?php include 'views/layouts/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>ĐĂNG NHẬP</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['message']) && $_GET['message'] == 'registered'): ?>
                    <div class="alert alert-success text-center">
                        Đăng ký thành công! Vui lòng đăng nhập.
                    </div>
                <?php endif; ?>               
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?controller=auth&action=processLogin" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required placeholder="admin@tlu.edu.vn">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Chưa có tài khoản? <a href="index.php?controller=auth&action=register">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>