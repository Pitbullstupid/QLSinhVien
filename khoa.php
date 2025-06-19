<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm khoa mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {;
    $ten_khoa = $_POST['ten_khoa'];
    $sql = "INSERT INTO khoa (ten_khoa) VALUES ('$ten_khoa')";
    $conn->query($sql);
    header("Location: khoa.php");
    exit();
}

// Xử lý xóa khoa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM khoa WHERE id = $id";
    $conn->query($sql);
    header("Location: khoa.php");
    exit();
}

// Xử lý cập nhật khoa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $ten_khoa = $_POST['ten_khoa'];
    $sql = "UPDATE khoa SET ten_khoa = '$ten_khoa' WHERE id = $id";
    $conn->query($sql);
    header("Location: khoa.php");
    exit();
}

// Lấy danh sách khoa
$sql = "SELECT * FROM khoa";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý khoa</title>
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
            <a href="khoa.php" class="active"><i class="bi bi-building"></i> Quản lý khoa</a>
            <a href="mon_hoc.php"><i class="bi bi-book"></i> Quản lý môn học</a>
            <a href="diem.php"><i class="bi bi-star"></i> Quản lý điểm</a>
            <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-building"></i> Quản lý khoa</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                <i class="bi bi-plus-circle"></i> Thêm khoa mới
            </button>
        </div>

        <!-- Danh sách khoa -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Danh sách khoa</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10%;"><i class="bi bi-hash"></i> ID</th>
                                <th style="width: 70%;"><i class="bi bi-building"></i> Tên Khoa</th>
                                <th style="width: 20%;"><i class="bi bi-gear"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['ten_khoa']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editKhoa(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['ten_khoa']); ?>')">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc muốn xóa khoa này?')">
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

        <!-- Modal thêm khoa -->
        <div class="modal fade" id="addFacultyModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-building-add"></i> Thêm khoa mới</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-building"></i> Tên Khoa:</label>
                                <input type="text" name="ten_khoa" class="form-control" required
                                       pattern="[A-Za-zÀ-ỹ\s]+" minlength="2" maxlength="100"
                                       title="Tên khoa phải từ 2-100 ký tự, chỉ bao gồm chữ cái">
                                <div class="invalid-feedback">Vui lòng nhập tên khoa hợp lệ</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="add" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm khoa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa khoa -->
        <div class="modal fade" id="editFacultyModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa thông tin khoa</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-building"></i> Tên Khoa:</label>
                                <input type="text" name="ten_khoa" id="edit_ten_khoa" class="form-control" required
                                       pattern="[A-Za-zÀ-ỹ\s]+" minlength="2" maxlength="100"
                                       title="Tên khoa phải từ 2-100 ký tự, chỉ bao gồm chữ cái">
                                <div class="invalid-feedback">Vui lòng nhập tên khoa hợp lệ</div>
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
        function editKhoa(id, tenKhoa) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_ten_khoa').value = tenKhoa;
            var editModal = new bootstrap.Modal(document.getElementById('editFacultyModal'));
            editModal.show();
        }
    </script>
</body>
</html> 