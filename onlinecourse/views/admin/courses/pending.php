<?php include 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Duyệt Khóa học mới</h1>
</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div class="alert alert-success">Đã duyệt khóa học thành công!</div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Tên khóa học</th>
                <th>Giảng viên</th>
                <th>Danh mục</th>
                <th>Giá tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($courses)): ?>
                <tr>
                    <td colspan="5" class="text-center">Hiện không có khóa học nào chờ duyệt.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars($course['instructor_name']); ?></td>
                    <td><?php echo htmlspecialchars($course['category_name']); ?></td>
                    <td><?php echo number_format($course['price']); ?> đ</td>
                    <td>
                        <form action="index.php?controller=admin&action=approveCourse" method="POST" class="d-inline">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layouts/footer.php'; ?>