<?php
require_once 'config.php';
$stt = 1;
$sql = "SELECT sv.id, sv.ma_sv, sv.ho_ten, sv.ngay_sinh, sv.gioi_tinh, sv.email, sv.so_dien_thoai, l.ten_lop, k.ten_khoa
        FROM sinh_vien sv 
        JOIN lop l ON sv.lop_id = l.id
        JOIN khoa k ON l.khoa_id = k.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thong tin sinh viên</title>
</head>

<body>
    <h1>Danh sách các sinh viên</h1>
    <a href="add.php">Thêm nhân viên mới</a> | 
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Mã SV</th>
            <th>Họ Tên</th>
            <th>Ngày Sinh</th>
            <th>Giới Tính</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Tên Lớp</th>
            <th>Tên Khoa</th>
        </tr>
        <?php
         if($result->num_rows >  0) {
            while ($rows = $result->fetch_array()) {
                echo "<tr>";
                echo "<td>" . $stt++ . "</td>";
                echo "<td>" . $rows['ma_sv'] . "</td>";
                echo "<td>" . $rows['ho_ten'] . "</td>";
                echo "<td>" . $rows['ngay_sinh'] . "</td>";
                echo "<td>" . $rows['gioi_tinh'] . "</td>";
                echo "<td>" . $rows['email'] . "</td>";
                echo "<td>" . $rows['so_dien_thoai'] . "</td>";
                echo "<td>" . $rows['ten_lop'] . "</td>";
                echo "<td>" . $rows['ten_khoa'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Không có dữ liệu sinh viên</td></tr>";
        }
        ?>
    </table>
</body>

</html>