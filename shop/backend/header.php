<?php
session_start();
ob_start();
    if (!isset($_SESSION['admin']['username'])) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="/shop/backend/">Trang chủ</a></li>
                <li><a href="/shop/backend/nhan_hang.php/">Nhãn hàng</a></li>
                <li><a href="/shop/backend/san_pham.php/">Sản phẩm sách</a></li>
                <li><a href="/shop/backend/order.php">Quản lý đơn hàng</a></li>
                
                <br>
                <?php
                if (isset($_SESSION['admin']['username'])) {
                    echo "<li style=\"font-size: 20px; margin-top: 10px; color:white;\">Xin chào Admin <b style=\"font-weight: bold; color: #337AB7 ;\">{$_SESSION['admin']['username']}</b></li> 
                    <li><a href=\"logout.php\" style=\"font-size: 20px;\">Đăng xuất</a></li>";
                } else {
                    echo "<li style=\"font-size: 18px; margin-top: 12px; color:white;\">AD Đăng nhập</li><li><a href=\"login.php\" style=\"font-size: 20px; \">Tại đây</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container"> <!-- Thẻ mở .container -->