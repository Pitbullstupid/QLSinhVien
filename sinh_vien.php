<?php
require_once 'config.php';

// Xử lý thêm sinh viên
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $ma_sv = $_POST['ma_sv'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $lop_id = $_POST['lop_id'];
    
    $sql = "INSERT INTO sinh_vien (ma_sv, ho_ten, ngay_sinh, gioi_tinh, email, so_dien_thoai, lop_id) 
            VALUES ('$ma_sv', '$ho_ten', '$ngay_sinh', '$gioi_tinh', '$email', '$so_dien_thoai', $lop_id)";
    
    if ($conn->query($sql)) {
        echo "<script>alert('Thêm sinh viên thành công!'); window.location.href='sinh_vien.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}

// Xử lý sửa sinh viên
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $ma_sv = $_POST['ma_sv'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $lop_id = $_POST['lop_id'];
    
    $sql = "UPDATE sinh_vien SET 
            ma_sv = '$ma_sv',
            ho_ten = '$ho_ten',
            ngay_sinh = '$ngay_sinh',
            gioi_tinh = '$gioi_tinh',
            email = '$email',
            so_dien_thoai = '$so_dien_thoai',
            lop_id = $lop_id
            WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo "<script>alert('Cập nhật sinh viên thành công!'); window.location.href='sinh_vien.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}

// Xử lý xóa sinh viên
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM sinh_vien WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo "<script>alert('Xóa sinh viên thành công!'); window.location.href='sinh_vien.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}

// Lấy danh sách lớp
$sql = "SELECT * FROM lop";
$lop_result = $conn->query($sql);
$lop_list = [];
while ($row = $lop_result->fetch_assoc()) {
    $lop_list[] = $row;
}

// Lấy danh sách sinh viên
$sql = "SELECT sv.*, l.ten_lop, k.ten_khoa 
        FROM sinh_vien sv 
        LEFT JOIN lop l ON sv.lop_id = l.id 
        LEFT JOIN khoa k ON l.khoa_id = k.id";
$sinh_vien_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav class="nav-menu">
            <a href="index.php"><i class="bi bi-house"></i> Trang chủ</a>
            <a href="sinh_vien.php"><i class="bi bi-person-vcard"></i> Quản lý sinh viên</a>
            <a href="lop.php"><i class="bi bi-mortarboard"></i> Quản lý lớp</a>
            <a href="khoa.php"><i class="bi bi-building"></i> Quản lý khoa</a>
            <a href="mon_hoc.php"><i class="bi bi-book"></i> Quản lý môn học</a>
            <a href="diem.php"><i class="bi bi-star"></i> Quản lý điểm</a>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-person-vcard"></i> Quản lý sinh viên</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-circle"></i> Thêm sinh viên mới
            </button>
        </div>

        <!-- Danh sách sinh viên -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Danh sách sinh viên</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bi bi-person-badge"></i> Mã SV</th>
                                <th><i class="bi bi-person"></i> Họ tên</th>
                                <th><i class="bi bi-calendar"></i> Ngày sinh</th>
                                <th><i class="bi bi-gender-ambiguous"></i> Giới tính</th>
                                <th><i class="bi bi-mortarboard"></i> Lớp</th>
                                <th><i class="bi bi-building"></i> Khoa</th>
                                <th><i class="bi bi-gear"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $sinh_vien_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['ma_sv']; ?></td>
                                <td><?php echo $row['ho_ten']; ?></td>
                                <td><?php echo $row['ngay_sinh']; ?></td>
                                <td>
                                    <?php if ($row['gioi_tinh'] == 'Nam'): ?>
                                        <i class="bi bi-gender-male text-primary"></i>
                                    <?php else: ?>
                                        <i class="bi bi-gender-female text-danger"></i>
                                    <?php endif; ?>
                                    <?php echo $row['gioi_tinh']; ?>
                                </td>
                                <td><?php echo $row['ten_lop']; ?></td>
                                <td><?php echo $row['ten_khoa']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">
                                        <i class="bi bi-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal thêm sinh viên -->
        <div class="modal fade" id="addStudentModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-person-plus"></i> Thêm sinh viên mới</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-person-badge"></i> Mã sinh viên:</label>
                                    <input type="text" name="ma_sv" class="form-control" required 
                                           pattern="[A-Za-z0-9]+" minlength="5" maxlength="20"
                                           title="Mã sinh viên phải từ 5-20 ký tự, chỉ bao gồm chữ và số">
                                    <div class="invalid-feedback">Vui lòng nhập mã sinh viên hợp lệ</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-person"></i> Họ tên:</label>
                                    <input type="text" name="ho_ten" class="form-control" required
                                           pattern="[A-Za-zÀ-ỹ\s]+" minlength="2" maxlength="50"
                                           title="Họ tên phải từ 2-50 ký tự, chỉ bao gồm chữ cái">
                                    <div class="invalid-feedback">Vui lòng nhập họ tên hợp lệ</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-calendar"></i> Ngày sinh:</label>
                                    <input type="date" name="ngay_sinh" class="form-control" required
                                           max="<?php echo date('Y-m-d'); ?>">
                                    <div class="invalid-feedback">Vui lòng chọn ngày sinh hợp lệ</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Giới tính:</label>
                                    <select name="gioi_tinh" class="form-select" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn giới tính</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-envelope"></i> Email:</label>
                                    <input type="email" name="email" class="form-control" required
                                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-telephone"></i> Số điện thoại:</label>
                                    <input type="tel" name="so_dien_thoai" class="form-control" required
                                           pattern="[0-9]{10,11}" title="Số điện thoại phải có 10-11 số">
                                    <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-mortarboard"></i> Lớp:</label>
                                <select name="lop_id" class="form-select" required>
                                    <option value="">Chọn lớp</option>
                                    <?php foreach ($lop_list as $lop): ?>
                                    <option value="<?php echo $lop['id']; ?>"><?php echo $lop['ten_lop']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn lớp</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="add" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm sinh viên
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa sinh viên -->
        <div class="modal fade" id="editStudentModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa thông tin sinh viên</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="needs-validation" novalidate>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-person-badge"></i> Mã sinh viên:</label>
                                    <input type="text" name="ma_sv" id="edit_ma_sv" class="form-control" required
                                           pattern="[A-Za-z0-9]+" minlength="5" maxlength="20"
                                           title="Mã sinh viên phải từ 5-20 ký tự, chỉ bao gồm chữ và số">
                                    <div class="invalid-feedback">Vui lòng nhập mã sinh viên hợp lệ</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-person"></i> Họ tên:</label>
                                    <input type="text" name="ho_ten" id="edit_ho_ten" class="form-control" required
                                           pattern="[A-Za-zÀ-ỹ\s]+" minlength="2" maxlength="50"
                                           title="Họ tên phải từ 2-50 ký tự, chỉ bao gồm chữ cái">
                                    <div class="invalid-feedback">Vui lòng nhập họ tên hợp lệ</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-calendar"></i> Ngày sinh:</label>
                                    <input type="date" name="ngay_sinh" id="edit_ngay_sinh" class="form-control" required
                                           max="<?php echo date('Y-m-d'); ?>">
                                    <div class="invalid-feedback">Vui lòng chọn ngày sinh hợp lệ</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Giới tính:</label>
                                    <select name="gioi_tinh" id="edit_gioi_tinh" class="form-select" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn giới tính</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-envelope"></i> Email:</label>
                                    <input type="email" name="email" id="edit_email" class="form-control" required
                                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-telephone"></i> Số điện thoại:</label>
                                    <input type="tel" name="so_dien_thoai" id="edit_so_dien_thoai" class="form-control" required
                                           pattern="[0-9]{10,11}" title="Số điện thoại phải có 10-11 số">
                                    <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-mortarboard"></i> Lớp:</label>
                                <select name="lop_id" id="edit_lop_id" class="form-select" required>
                                    <option value="">Chọn lớp</option>
                                    <?php foreach ($lop_list as $lop): ?>
                                    <option value="<?php echo $lop['id']; ?>"><?php echo $lop['ten_lop']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn lớp</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="edit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Reset form when modal is closed
        document.getElementById('addStudentModal').addEventListener('hidden.bs.modal', function () {
            document.querySelector('#addStudentModal form').reset();
            document.querySelector('#addStudentModal form').classList.remove('was-validated');
        });

        // Handle edit button click
        document.querySelectorAll('a[href^="?edit="]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('href').split('=')[1];
                
                // Fetch student data
                fetch('get_student.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_id').value = data.id;
                        document.getElementById('edit_ma_sv').value = data.ma_sv;
                        document.getElementById('edit_ho_ten').value = data.ho_ten;
                        document.getElementById('edit_ngay_sinh').value = data.ngay_sinh;
                        document.getElementById('edit_gioi_tinh').value = data.gioi_tinh;
                        document.getElementById('edit_email').value = data.email;
                        document.getElementById('edit_so_dien_thoai').value = data.so_dien_thoai;
                        document.getElementById('edit_lop_id').value = data.lop_id;
                        
                        // Show modal
                        new bootstrap.Modal(document.getElementById('editStudentModal')).show();
                    });
            });
        });

        // Reset edit form when modal is closed
        document.getElementById('editStudentModal').addEventListener('hidden.bs.modal', function () {
            document.querySelector('#editStudentModal form').reset();
            document.querySelector('#editStudentModal form').classList.remove('was-validated');
        });
    </script>
</body>
</html>