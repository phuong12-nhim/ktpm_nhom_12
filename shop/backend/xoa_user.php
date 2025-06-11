<?php
require_once 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Chú ý: Phải là bảng khachang và cột idkhachhang
    $sql = "DELETE FROM khachang WHERE idkhachhang = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: user.php"); // hoặc đúng tên file đang hiển thị danh sách
        exit();
    } else {
        echo "Xóa thất bại: " . mysqli_error($conn);
    }
} else {
    echo "Thiếu ID cần xóa!";
}
?>
