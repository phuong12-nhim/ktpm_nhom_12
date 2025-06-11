<?php
session_start();
ob_start();
include_once 'ketnoi.php';

$idsp = isset($_GET['id']) ? $_GET['id'] : null;

$action = isset($_GET['action']) ? $_GET['action'] : 'add';
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;

if ($quantity < 0) {
    $action = 'delete';
}

// Use prepared statement to prevent SQL injection
$query = mysqli_prepare($conn, "SELECT * FROM `sanpham` WHERE `idsanpham`= ?");
mysqli_stmt_bind_param($query, "i", $idsp);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

if ($result) {
    $product = mysqli_fetch_assoc($result);
}

$item = [
    'id' => $product['idsanpham'],
    'name' => $product['tensanpham'],
    'image' => $product['imgae'],
    'price' => ($product['giadaura'] > 0) ? $product['giadaura'] : $product['giadauvao'],
    'quantity' => $quantity
];

// Rest of your code...



//thêm
if ($action == 'add') {
	if (isset($_SESSION['cart'][$idsp])) {
		$_SESSION['cart'][$idsp]['quantity'] += $quantity; //1
	} else {
		$_SESSION['cart'][$idsp] = $item;
	}
}

//cập nhật
if ($action == 'update') {
	$_SESSION['cart'][$idsp]['quantity'] = $quantity;
}

//xóa
if ($action == 'delete') {
	unset($_SESSION['cart'][$idsp]);
}
	header("location: view_cart.php");

	//thêm mới vào giỏ hàng

	//cập nhật giỏ hàng

	//xóa sản phẩm giỏ hàng
