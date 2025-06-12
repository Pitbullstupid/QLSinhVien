<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM sinh_vien WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode($student);
    } else {
        echo json_encode(['error' => 'Không tìm thấy sinh viên']);
    }
} else {
    echo json_encode(['error' => 'Thiếu ID sinh viên']);
}
?> 