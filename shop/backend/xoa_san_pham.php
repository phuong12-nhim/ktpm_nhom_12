<?php
include 'header.php';
require_once 'connect.php';
// lấy id trên tham số url đã gửi tù nút xóa
$id = !empty($_GET['Proid']) ? (int)$_GET['Proid'] : 0;
$deleted = mysqli_query($conn, "DELETE FROM sanpham WHERE idsanpham = $id");
if ($deleted) {
    header('location: san_pham.php');
} else {
    echo 'Có lỗi, vui lòng kiểm tra lại';
}
?>