<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm môn học mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $ma_mon = $_POST['ma_mon'];
    $ten_mon = $_POST['ten_mon'];
    $so_tin_chi = $_POST['so_tin_chi'];
    $sql = "INSERT INTO mon_hoc (ma_mon, ten_mon, so_tin_chi) VALUES ('$ma_mon', '$ten_mon', $so_tin_chi)";
    $conn->query($sql);
    header("Location: mon_hoc.php");
    exit();
}

// Xử lý xóa môn học
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM mon_hoc WHERE id = $id";
    $conn->query($sql);
    header("Location: mon_hoc.php");
    exit();
}

// Xử lý cập nhật môn học
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $ma_mon = $_POST['ma_mon'];
    $ten_mon = $_POST['ten_mon'];
    $so_tin_chi = $_POST['so_tin_chi'];
    $sql = "UPDATE mon_hoc SET ma_mon = '$ma_mon', ten_mon = '$ten_mon', so_tin_chi = $so_tin_chi WHERE id = $id";
    $conn->query($sql);
    header("Location: mon_hoc.php");
    exit();
}

// Lấy danh sách môn học
$sql = "SELECT * FROM mon_hoc";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý môn học</title>
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
            <a href="mon_hoc.php" class="active"><i class="bi bi-book"></i> Quản lý môn học</a>
            <a href="diem.php"><i class="bi bi-star"></i> Quản lý điểm</a>
            <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-book"></i> Quản lý môn học</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="bi bi-plus-circle"></i> Thêm môn học mới
            </button>
        </div>

        <!-- Danh sách môn học -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Danh sách môn học</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10%;"><i class="bi bi-hash"></i> ID</th>
                                <th style="width: 20%;"><i class="bi bi-upc"></i> Mã Môn</th>
                                <th style="width: 35%;"><i class="bi bi-book"></i> Tên Môn</th>
                                <th style="width: 15%;"><i class="bi bi-123"></i> Số Tín Chỉ</th>
                                <th style="width: 20%;"><i class="bi bi-gear"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['ma_mon']); ?></td>
                                <td><?php echo htmlspecialchars($row['ten_mon']); ?></td>
                                <td><?php echo $row['so_tin_chi']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editMonHoc(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['ma_mon']); ?>', '<?php echo htmlspecialchars($row['ten_mon']); ?>', <?php echo $row['so_tin_chi']; ?>)">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc muốn xóa môn học này?')">
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

        <!-- Modal thêm môn học -->
        <div class="modal fade" id="addSubjectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-book-plus"></i> Thêm môn học mới</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-upc"></i> Mã Môn:</label>
                                <input type="text" name="ma_mon" class="form-control" required
                                       pattern="[A-Za-z0-9]+" minlength="2" maxlength="20"
                                       title="Mã môn phải từ 2-20 ký tự, chỉ bao gồm chữ và số">
                                <div class="invalid-feedback">Vui lòng nhập mã môn hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-book"></i> Tên Môn:</label>
                                <input type="text" name="ten_mon" class="form-control" required
                                       pattern="[A-Za-zÀ-ỹ\s]+" minlength="2" maxlength="100"
                                       title="Tên môn phải từ 2-100 ký tự, chỉ bao gồm chữ cái">
                                <div class="invalid-feedback">Vui lòng nhập tên môn hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-123"></i> Số Tín Chỉ:</label>
                                <input type="number" name="so_tin_chi" class="form-control" required
                                       min="1" max="10" title="Số tín chỉ phải từ 1-10">
                                <div class="invalid-feedback">Vui lòng nhập số tín chỉ hợp lệ</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="add" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm môn học
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa môn học -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa thông tin môn học</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-upc"></i> Mã Môn:</label>
                                <input type="text" name="ma_mon" id="edit_ma_mon" class="form-control" required
                                       pattern="[A-Za-z0-9]+" minlength="2" maxlength="20"
                                       title="Mã môn phải từ 2-20 ký tự, chỉ bao gồm chữ và số">
                                <div class="invalid-feedback">Vui lòng nhập mã môn hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-book"></i> Tên Môn:</label>
                                <input type="text" name="ten_mon" id="edit_ten_mon" class="form-control" required
                                       pattern="[A-Za-zÀ-ỹ\s]+" minlength="2" maxlength="100"
                                       title="Tên môn phải từ 2-100 ký tự, chỉ bao gồm chữ cái">
                                <div class="invalid-feedback">Vui lòng nhập tên môn hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-123"></i> Số Tín Chỉ:</label>
                                <input type="number" name="so_tin_chi" id="edit_so_tin_chi" class="form-control" required
                                       min="1" max="10" title="Số tín chỉ phải từ 1-10">
                                <div class="invalid-feedback">Vui lòng nhập số tín chỉ hợp lệ</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="update" class="btn btn-primary">
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

        // Xử lý modal sửa
        function editMonHoc(id, maMon, tenMon, soTinChi) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_ma_mon').value = maMon;
            document.getElementById('edit_ten_mon').value = tenMon;
            document.getElementById('edit_so_tin_chi').value = soTinChi;
            var editModal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
            editModal.show();
        }
    </script>
</body>
</html> 