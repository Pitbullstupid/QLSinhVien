<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Quản lý sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="nav-menu">
            <a href="index.php" class="active"><i class="bi bi-house"></i> Trang chủ</a>
            <a href="sinh_vien.php"><i class="bi bi-person-vcard"></i> Quản lý sinh viên</a>
            <a href="lop.php"><i class="bi bi-mortarboard"></i> Quản lý lớp</a>
            <a href="khoa.php"><i class="bi bi-building"></i> Quản lý khoa</a>
            <a href="mon_hoc.php"><i class="bi bi-book"></i> Quản lý môn học</a>
            <a href="diem.php"><i class="bi bi-star"></i> Quản lý điểm</a>
            <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
        </nav>

        <h1 class="section-title">Hệ thống quản lý sinh viên</h1>
        
        <div class="dashboard">
            <?php
            // Thống kê số lượng sinh viên
            $sql = "SELECT COUNT(*) as total FROM sinh_vien";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            ?>
            <div class="dashboard-item " onclick="location.href='sinh_vien.php';" style="cursor: pointer;">
                <i class="fas fa-user-graduate fa-2x" style="color: var(--primary-color); margin-bottom: 15px;"></i>
                <h2>Tổng số sinh viên</h2>
                <p><?php echo $row['total']; ?></p>
            </div>

            <?php
            // Thống kê số lượng lớp
            $sql = "SELECT COUNT(*) as total FROM lop";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            ?>
            <div class="dashboard-item " onclick="location.href='lop.php';" style="cursor: pointer;">
                <i class="fas fa-chalkboard fa-2x" style="color: var(--success-color); margin-bottom: 15px;"></i>
                <h2>Tổng số lớp</h2>
                <p><?php echo $row['total']; ?></p>
            </div>

            <?php
            // Thống kê số lượng khoa
            $sql = "SELECT COUNT(*) as total FROM khoa";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            ?>
            <div class="dashboard-item" onclick="location.href='khoa.php';" style="cursor: pointer;">
                <i class="fas fa-university fa-2x" style="color: var(--warning-color); margin-bottom: 15px;"></i>
                <h2>Tổng số khoa</h2>
                <p><?php echo $row['total']; ?></p>
            </div>

            <?php
            // Thống kê số lượng môn học
            $sql = "SELECT COUNT(*) as total FROM mon_hoc";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            ?>
            <div class="dashboard-item " onclick="location.href='mon_hoc.php';" style="cursor: pointer;">
                <i class="fas fa-book fa-2x" style="color: var(--danger-color); margin-bottom: 15px;"></i>
                <h2>Tổng số môn học</h2>
                <p><?php echo $row['total']; ?></p>
            </div>
        </div>

        <div class="dashboard" style="margin-top: 30px;">
            <?php
            // Thống kê sinh viên theo khoa
            $sql = "SELECT k.ten_khoa, COUNT(sv.id) as total 
                    FROM khoa k 
                    LEFT JOIN lop l ON k.id = l.khoa_id 
                    LEFT JOIN sinh_vien sv ON l.id = sv.lop_id 
                    GROUP BY k.id";
            $result = $conn->query($sql);
            ?>
            <div class="dashboard-item" style="grid-column: 1 / -1;">
                <h2 class="section-title">Thống kê sinh viên theo khoa</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <h3 style="color: var(--primary-color); margin-bottom: 10px;"><?php echo $row['ten_khoa']; ?></h3>
                        <p style="font-size: 1.5em; font-weight: bold;"><?php echo $row['total']; ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 