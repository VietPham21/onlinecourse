<?php include 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="index.php?controller=admin&action=dashboard" class="list-group-item list-group-item-action active">Dashboard</a>
            <a href="index.php?controller=admin&action=users" class="list-group-item list-group-item-action">Quản lý Người dùng</a>
            <a href="#" class="list-group-item list-group-item-action disabled">Quản lý Khóa học (Sắp có)</a>
        </div>
    </div>

    <div class="col-md-9">
        <h2>Tổng quan hệ thống</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Người dùng</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $totalUsers; ?> Tài khoản</h5>
                        <p class="card-text">Tổng số học viên và giảng viên.</p>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>