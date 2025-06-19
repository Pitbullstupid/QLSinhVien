<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm lớp mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $ten_lop = $_POST['ten_lop'];
    $khoa_id = $_POST['khoa_id'];
    $sql = "INSERT INTO lop (ten_lop, khoa_id) VALUES ('$ten_lop', $khoa_id)";
    $conn->query($sql);
    header("Location: lop.php");
    exit();
}

// Xử lý xóa lớp
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM lop WHERE id = $id";
    $conn->query($sql);
    header("Location: lop.php");
    exit();
}

// Xử lý cập nhật lớp
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $ten_lop = $_POST['ten_lop'];
    $khoa_id = $_POST['khoa_id'];
    $sql = "UPDATE lop SET ten_lop = '$ten_lop', khoa_id = $khoa_id WHERE id = $id";
    $conn->query($sql);
    header("Location: lop.php");
    exit();
}

// Lấy danh sách khoa
$sql_khoa = "SELECT * FROM khoa";
$result_khoa = $conn->query($sql_khoa);

// Lấy danh sách lớp
$sql = "SELECT l.*, k.ten_khoa 
        FROM lop l 
        LEFT JOIN khoa k ON l.khoa_id = k.id";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý lớp</title>
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
            <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-mortarboard"></i> Quản lý lớp</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                <i class="bi bi-plus-circle"></i> Thêm lớp mới
            </button>
        </div>

        <!-- Danh sách lớp -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-mortarboard"></i> Danh sách lớp</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10%;"><i class="bi bi-hash"></i> ID</th>
                                <th style="width: 20%;"><i class="bi bi-mortarboard"></i> Tên Lớp</th>
                                <th style="width: 50%;"><i class="bi bi-building"></i> Khoa</th>
                                <th style="width: 20%;"><i class="bi bi-gear"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['ten_lop']); ?></td>
                                <td><?php echo htmlspecialchars($row['ten_khoa']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editLop(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['ten_lop']); ?>', <?php echo $row['khoa_id']; ?>)">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc muốn xóa lớp này?')">
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

        <!-- Modal thêm lớp -->
        <div class="modal fade" id="addClassModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-mortarboard-fill"></i> Thêm lớp mới</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-mortarboard"></i> Tên Lớp:</label>
                                <input type="text" name="ten_lop" class="form-control" required
                                       pattern="[A-Za-z0-9\s]+" minlength="2" maxlength="100"
                                       title="Tên lớp phải từ 2-100 ký tự">
                                <div class="invalid-feedback">Vui lòng nhập tên lớp hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-building"></i> Khoa:</label>
                                <select name="khoa_id" class="form-select" required>
                                    <option value="">Chọn Khoa</option>
                                    <?php while ($khoa = $result_khoa->fetch_assoc()): ?>
                                        <option value="<?php echo $khoa['id']; ?>">
                                            <?php echo htmlspecialchars($khoa['ten_khoa']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn khoa</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="add" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm lớp
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa lớp -->
        <div class="modal fade" id="editClassModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa thông tin lớp</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-mortarboard"></i> Tên Lớp:</label>
                                <input type="text" name="ten_lop" id="edit_ten_lop" class="form-control" required
                                       pattern="[A-Za-z0-9\s]+" minlength="2" maxlength="100"
                                       title="Tên lớp phải từ 2-100 ký tự">
                                <div class="invalid-feedback">Vui lòng nhập tên lớp hợp lệ</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-building"></i> Khoa:</label>
                                <select name="khoa_id" id="edit_khoa_id" class="form-select" required>
                                    <?php 
                                    $result_khoa->data_seek(0);
                                    while ($khoa = $result_khoa->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $khoa['id']; ?>">
                                            <?php echo htmlspecialchars($khoa['ten_khoa']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn khoa</div>
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
        function editLop(id, tenLop, khoaId) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_ten_lop').value = tenLop;
            document.getElementById('edit_khoa_id').value = khoaId;
            var editModal = new bootstrap.Modal(document.getElementById('editClassModal'));
            editModal.show();
        }
    </script>
</body>
</html> 