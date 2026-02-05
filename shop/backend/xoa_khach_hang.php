<?php
require_once 'connect.php';

// 1. Kiểm tra có id không
if (!isset($_GET['idkhachhang'])) {
    header('Location: users.php');
    exit;
}

$id = $_GET['idkhachhang'];

// 2. Ép kiểu để an toàn
$id = (int)$id;

// 3. Kiểm tra khách hàng có tồn tại không
$check = mysqli_query($conn, "SELECT idkhachhang FROM khachang WHERE idkhachhang = $id");

if (mysqli_num_rows($check) == 0) {
    // Không tồn tại → quay về
    header('Location: users.php');
    exit;
}

// 4. Xóa khách hàng
$sql = "DELETE FROM khachang WHERE idkhachhang = $id";

if (mysqli_query($conn, $sql)) {
    header('Location: users.php');
    exit;
} else {
    echo "Xóa không thành công!";
}