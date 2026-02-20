<?php
require_once 'connect.php';

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "approve") {
        $sql = "UPDATE hoadon 
                SET status = 2, ngay_cap_nhat = NOW() 
                WHERE idhoadon = $id";
    }

    if ($action == "cancel") {
        $sql = "UPDATE hoadon 
                SET status = 3, ngay_cap_nhat = NOW() 
                WHERE idhoadon = $id";
    }

    mysqli_query($conn, $sql);

    header("Location: order.php"); // hoặc trang đang hiển thị
    exit();
}
