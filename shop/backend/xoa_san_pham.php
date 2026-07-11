<?php
include 'header.php';
require_once 'connect.php';

$id = (int)$_GET['Proid'];

// Xóa chi tiết đơn hàng
mysqli_query($conn, "DELETE FROM chitietdonhang WHERE idsanpham = $id");

// Xóa chi tiết sản phẩm
mysqli_query($conn, "DELETE FROM chitietsanpham WHERE idsanpham = $id");

// Xóa sản phẩm
$deleted = mysqli_query($conn, "DELETE FROM sanpham WHERE idsanpham = $id");

if ($deleted) {
    header("Location: san_pham.php");
} else {
    echo mysqli_error($conn);
}
?>