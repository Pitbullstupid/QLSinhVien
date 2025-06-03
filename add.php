<?php
require_once 'config.php';

$query = "SELECT id, ten_lop FROM lop";
$lopResult = $conn->query($query);
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $masv = $_POST['ma_sv'];
    $hoten = $_POST['ho_ten'];
    $ngaysinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $lop_id = $_POST['lop_id'];

    $sql = "INSERT INTO sinh_vien (ma_sv, ho_ten, ngay_sinh, gioi_tinh, email, so_dien_thoai, lop_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $masv, $hoten, $ngaysinh, $gioi_tinh, $email, $so_dien_thoai, $lop_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên mới</title>
</head>
    <style>
    table {
        width: 400px;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid black;
    }

    th {
        text-align: left;
    }
    </style>
<body>
    <h1>Thêm sinh viên</h1>
<form method="post">
    <table>
        <tr>
            <td><label for="ma_sv">Mã SV:</label></td>
            <td><input type="text" name="ma_sv" id="ma_sv" required></td>
        </tr>
        <tr>
            <td><label for="ho_ten">Họ tên:</label></td>
            <td><input type="text" name="ho_ten" id="ho_ten" required></td>
        </tr>
        <tr>
            <td><label for="ngay_sinh">Ngày sinh:</label></td>
            <td><input type="date" name="ngay_sinh" id="ngay_sinh"></td>
        </tr>
        <tr>
            <td><label for="gioi_tinh">Giới tính:</label></td>
            <td>
                <select name="gioi_tinh" id="gioi_tinh">
                    <option value="Nam">Nam</option>
                    <option value="Nu">Nữ</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" name="email" id="email"></td>
        </tr>
        <tr>
            <td><label for="so_dien_thoai">Số điện thoại:</label></td>
            <td><input type="text" name="so_dien_thoai" id="so_dien_thoai"></td>
        </tr>
        <tr>
            <td><label for="lop_id">Lớp:</label></td>
            <td>
                <select name="lop_id" id="lop_id" required>
                    <option value="">-- Chọn lớp --</option>
                    <?php
                    if ($lopResult->num_rows > 0) {
                        while ($lop = $lopResult->fetch_assoc()) {
                            echo "<option value='{$lop['id']}'>{$lop['ten_lop']}</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <button type="submit">Thêm sinh viên</button>
            </td>
        </tr>
    </table>
</form>

    <br>
    <a href="index.php">Quay lại danh sách</a>
</body>
</html>