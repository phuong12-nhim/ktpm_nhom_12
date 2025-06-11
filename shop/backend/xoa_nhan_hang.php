<?php
include 'header.php';
require_once 'connect.php';
// lấy id trên tham số url đã gửi tù nút xóa
$id = !empty($_GET['Catid']) ? (int)$_GET['Catid'] : 0;
$deleted = mysqli_query($conn, "DELETE FROM catelog WHERE catelogid = $id");
if ($deleted) {
    header('location: nhan_hang.php');
} else {
    echo 'Có lỗi, vui lòng kiểm tra lại';
}
?>