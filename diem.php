<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm điểm mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $sinh_vien_id = $_POST['sinh_vien_id'];
    $mon_hoc_id = $_POST['mon_hoc_id'];
    $diem = $_POST['diem'];
    $ngay_thi = $_POST['ngay_thi'];
    $sql = "INSERT INTO diem (sinh_vien_id, mon_hoc_id, diem_so, ngay_thi) VALUES ($sinh_vien_id, $mon_hoc_id, $diem, '$ngay_thi')";
    $conn->query($sql);
    header("Location: diem.php");
    exit();
}

// Xử lý xóa điểm
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM diem WHERE id = $id";
    $conn->query($sql);
    header("Location: diem.php");
    exit();
}

// Xử lý cập nhật điểm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $sinh_vien_id = $_POST['sinh_vien_id'];
    $mon_hoc_id = $_POST['mon_hoc_id'];
    $diem = $_POST['diem'];
    $ngay_thi = $_POST['ngay_thi'];
    $sql = "UPDATE diem SET sinh_vien_id = $sinh_vien_id, mon_hoc_id = $mon_hoc_id, diem_so = $diem, ngay_thi = '$ngay_thi' WHERE id = $id";
    $conn->query($sql);
    header("Location: diem.php");
    exit();
}

// Lấy danh sách sinh viên
$sql_sv = "SELECT id, ma_sv, ho_ten FROM sinh_vien";
$result_sv = $conn->query($sql_sv);

// Lấy danh sách môn học
$sql_mh = "SELECT id, ma_mon, ten_mon FROM mon_hoc";
$result_mh = $conn->query($sql_mh);

// Lấy danh sách điểm
$sql = "SELECT d.*, sv.ma_sv, sv.ho_ten, mh.ma_mon, mh.ten_mon 
        FROM diem d 
        JOIN sinh_vien sv ON d.sinh_vien_id = sv.id 
        JOIN mon_hoc mh ON d.mon_hoc_id = mh.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý điểm</title>
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
            <a href="diem.php" class="active"><i class="bi bi-star"></i> Quản lý điểm</a>
            <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-star"></i> Quản lý điểm</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal">
                <i class="bi bi-plus-circle"></i> Thêm điểm mới
            </button>
        </div>

        <!-- Danh sách điểm -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Danh sách điểm</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-person-vcard"></i> Mã SV</th>
                                <th><i class="bi bi-person"></i> Họ Tên</th>
                                <th><i class="bi bi-book"></i> Môn Học</th>
                                <th><i class="bi bi-star"></i> Điểm</th>
                                <th><i class="bi bi-calendar"></i> Ngày thi</th>
                                <th><i class="bi bi-gear"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['ma_sv']); ?></td>
                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                <td><?php echo htmlspecialchars($row['ten_mon']); ?></td>
                                <td><?php echo isset($row['diem_so']) ? number_format($row['diem_so'], 2) : '0.00'; ?></td>
                                <td><?php echo isset($row['ngay_thi']) ? htmlspecialchars($row['ngay_thi']) : ''; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editDiem(<?php echo $row['id']; ?>, <?php echo $row['sinh_vien_id']; ?>, <?php echo $row['mon_hoc_id']; ?>, <?php echo isset($row['diem_so']) ? $row['diem_so'] : 0; ?>, '<?php echo isset($row['ngay_thi']) ? $row['ngay_thi'] : ''; ?>')">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc muốn xóa điểm này?')">
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

        <!-- Modal thêm điểm -->
        <div class="modal fade" id="addGradeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-star-plus"></i> Thêm điểm mới</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-person-vcard"></i> Sinh viên:</label>
                                <select name="sinh_vien_id" class="form-select" required>
                                    <option value="">Chọn sinh viên</option>
                                    <?php while ($sv = $result_sv->fetch_assoc()): ?>
                                    <option value="<?php echo $sv['id']; ?>">
                                        <?php echo htmlspecialchars($sv['ma_sv'] . ' - ' . $sv['ho_ten']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn sinh viên</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-book"></i> Môn học:</label>
                                <select name="mon_hoc_id" class="form-select" required>
                                    <option value="">Chọn môn học</option>
                                    <?php 
                                    $result_mh->data_seek(0);
                                    while ($mh = $result_mh->fetch_assoc()): 
                                    ?>
                                    <option value="<?php echo $mh['id']; ?>">
                                        <?php echo htmlspecialchars($mh['ma_mon'] . ' - ' . $mh['ten_mon']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn môn học</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-star"></i> Điểm:</label>
                                <input type="number" name="diem" class="form-control" required
                                       step="0.01" min="0" max="10"
                                       title="Điểm phải từ 0-10">
                                <div class="invalid-feedback">Vui lòng nhập điểm hợp lệ (0-10)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-calendar"></i> Ngày thi:</label>
                                <input type="date" name="ngay_thi" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng chọn ngày thi</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Đóng
                                </button>
                                <button type="submit" name="add" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm điểm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa điểm -->
        <div class="modal fade" id="editGradeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Sửa điểm</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-person-vcard"></i> Sinh viên:</label>
                                <select name="sinh_vien_id" id="edit_sinh_vien_id" class="form-select" required>
                                    <option value="">Chọn sinh viên</option>
                                    <?php 
                                    $result_sv->data_seek(0);
                                    while ($sv = $result_sv->fetch_assoc()): 
                                    ?>
                                    <option value="<?php echo $sv['id']; ?>">
                                        <?php echo htmlspecialchars($sv['ma_sv'] . ' - ' . $sv['ho_ten']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn sinh viên</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-book"></i> Môn học:</label>
                                <select name="mon_hoc_id" id="edit_mon_hoc_id" class="form-select" required>
                                    <option value="">Chọn môn học</option>
                                    <?php 
                                    $result_mh->data_seek(0);
                                    while ($mh = $result_mh->fetch_assoc()): 
                                    ?>
                                    <option value="<?php echo $mh['id']; ?>">
                                        <?php echo htmlspecialchars($mh['ma_mon'] . ' - ' . $mh['ten_mon']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn môn học</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-star"></i> Điểm:</label>
                                <input type="number" name="diem" id="edit_diem" class="form-control" required
                                       step="0.01" min="0" max="10"
                                       title="Điểm phải từ 0-10">
                                <div class="invalid-feedback">Vui lòng nhập điểm hợp lệ (0-10)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-calendar"></i> Ngày thi:</label>
                                <input type="date" name="ngay_thi" id="edit_ngay_thi" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng chọn ngày thi</div>
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

        // Xử lý modal sửa
        function editDiem(id, sinhVienId, monHocId, diem, ngayThi) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_sinh_vien_id').value = sinhVienId;
            document.getElementById('edit_mon_hoc_id').value = monHocId;
            document.getElementById('edit_diem').value = diem;
            document.getElementById('edit_ngay_thi').value = ngayThi;
            var editModal = new bootstrap.Modal(document.getElementById('editGradeModal'));
            editModal.show();
        }
    </script>
</body>
</html> 