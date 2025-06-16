<?php
session_start();
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    session_destroy();
    header("Location: login.php");
    exit();
} else {
    echo '<script>
        if (confirm("Bạn có chắc chắn muốn đăng xuất?")) {
            window.location.href = "logout.php?confirm=true";
        } else {
            window.history.back();
        }
    </script>';
}
?> 